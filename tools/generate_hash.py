import subprocess
import re
import secrets
import string
import logging
from pathlib import Path

logging.basicConfig(level=logging.INFO, format="[%(levelname)s] %(message)s")

BASE_DIR = Path("apps")


def replace_file_reference(original_path, new_filename, php_file_path):
    """Replace old file reference with new hashed filename in PHP file."""
    logging.info(f"Replacing {original_path} with {new_filename} in {php_file_path}")
    with open(php_file_path, "r") as file:
        content = file.read()

    updated_content = content.replace(str(original_path), str(new_filename))

    with open(php_file_path, "w") as file:
        file.write(updated_content)


def generate_hash() -> str:
    """Generate a random hash for file naming."""
    return "".join(
        secrets.choice(string.ascii_letters + string.digits) for _ in range(7)
    )


def process_file_references(app_dir, assets_dir):
    """Process file references in PHP files."""
    command = ["rg", "local/", str(app_dir / "views"), str(app_dir / "modules")]
    try:
        result = subprocess.run(command, check=True, text=True, capture_output=True)
        result = result.stdout.strip().split(sep="\n")
        for line in result:
            if line and not line.startswith("#"):
                process_line(line, assets_dir)
    except subprocess.CalledProcessError as e:
        logging.error(f"Command failed with exit code {e.returncode}: {e.stderr}")


def extract_file_path(text):
    """Extract file path from a line of text."""
    # Multiple patterns to match different formats
    patterns = [
        r"'(local/[^']+)'",  # For 'local/path/file.js'
        r'"(local/[^"]+)"',  # For "local/path/file.js"
        r"\[(local/[^\]]+)\]",  # For [local/path/file.js]
        r"=> \[(local/[^]]+)\]",  # For => [local/path/file.js]
    ]

    for pattern in patterns:
        match = re.search(pattern, text)
        if match:
            return match.group(1)

    # Direct match without quotes as fallback
    direct_match = re.search(r'(local/[^\s,\'"]+)', text)
    if direct_match:
        return direct_match.group(1)

    return None


def process_line(line, assets_dir):
    """Process each line to find and replace file references."""
    parts = line.split(":", 1)
    if len(parts) < 2:
        logging.warning(f"Invalid line format: {line}")
        return

    php_file = parts[0]
    content = parts[1]

    file_path = extract_file_path(content)
    if file_path:
        logging.info(f"Found file path: {file_path}")
        file_src = assets_dir / file_path

        if file_src.exists():
            new_name = create_hashed_file(file_src)
            if new_name:
                original_path = Path(file_path)
                new_path = original_path.parent / new_name
                replace_file_reference(original_path, new_path, php_file)
        else:
            logging.warning(f"File not found: {file_src}")
    else:
        logging.warning(f"No file path found in: {content}")


def create_hashed_file(file_src):
    """Create a hashed version of the file."""
    random_suffix = generate_hash()
    new_name = f"{file_src.stem}_{random_suffix}.min{file_src.suffix}"
    new_path = file_src.parent / new_name

    if file_src.suffix == ".js":
        shell_command = f"terser {file_src} -c -m -o {new_path}"
    elif file_src.suffix == ".css":
        shell_command = f"cleancss -O0 -o {new_path} {file_src}"
    else:
        logging.error(f"Unsupported file type: {file_src.suffix}")
        return None

    logging.info(f"Creating hashed file: {new_path}")
    subprocess.run(shell_command, shell=True)
    return new_name


def main():
    try:
        app_dir = BASE_DIR / "application"
        assets_dir = BASE_DIR / "assets"
        if not app_dir.exists():
            raise FileNotFoundError(f"Directory {app_dir} not found")

        process_file_references(app_dir, assets_dir)

    except FileNotFoundError as e:
        logging.error(f"Error: {e}")
    except Exception as e:
        logging.exception("Unexpected error occurred")


if __name__ == "__main__":
    main()
