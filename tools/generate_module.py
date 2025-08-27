#!/usr/bin/env python3
from dataclasses import dataclass, field
from pathlib import Path
import logging
import argparse
from typing import Dict, Optional

__version__ = "1.0.0"

logging.basicConfig(level=logging.INFO, format="[%(levelname)s]: %(message)s")


@dataclass
class TemplateConfig:
    templates: Dict[str, str] = field(
        default_factory=lambda: {
            "controller": "controller.php",
            "model": "model.php",
            "view": "view.php",
        }
    )


class ModuleGenerator:
    def __init__(self, template_dir: Path):
        self.template_dir = template_dir
        self.config = TemplateConfig()

    def read_template(
        self, template_path: Path, module_name: str, class_name: Optional[str] = None
    ) -> str:
        """Reads a template file and replaces placeholders with the module name and class name."""
        try:
            logging.info(f"Reading template: {template_path}")
            content = template_path.read_text(encoding="utf-8")

            base_name = class_name if class_name else module_name

            replacements = {
                "{{module_name}}": base_name.capitalize(),
                "{{module_view}}": base_name.lower(),
                "{{table_name}}": base_name.lower() + "s",
            }

            for placeholder, value in replacements.items():
                content = content.replace(placeholder, value)

            return content
        except Exception as e:
            logging.error(f"Error reading template {template_path}: {e}")
            raise

    def ensure_directory(self, path: Path) -> None:
        """Creates a directory if it does not exist."""
        try:
            path.mkdir(parents=True, exist_ok=True)
            logging.info(f"Directory ensured: {path}")
        except Exception as e:
            logging.error(f"Error creating directory {path}: {e}")
            raise

    def generate_single(
        self,
        module_name: str,
        base_dir: Path,
        module_type: str,
        class_name: Optional[str] = None,
    ) -> None:
        """Generates a single module component (controller, model, or view)."""
        try:
            logging.info(f"Generating {module_type}: {module_name}")
            type_dir = base_dir / module_name / f"{module_type}s"
            self.ensure_directory(type_dir)

            template_path = self.template_dir / self.config.templates[module_type]
            if not template_path.is_file():
                raise FileNotFoundError(f"Template file '{template_path}' not found")

            content = self.read_template(template_path, module_name, class_name)

            base_name = class_name if class_name else module_name

            if module_type == "view":
                output_filename = f"{base_name.lower()}.php"
            elif module_type == "model":
                output_filename = f"{base_name.capitalize()}_model.php"
            else:
                output_filename = f"{base_name.capitalize()}.php"

            output_path = type_dir / output_filename

            logging.info(f"Writing to: {output_path}")
            output_path.write_text(content, encoding="utf-8")
            logging.info(
                f"{module_type.capitalize()} '{output_filename}' created successfully"
            )

        except Exception as e:
            logging.error(f"Error generating {module_type} {module_name}: {e}")
            raise

    def generate_all(
        self, module_name: str, base_dir: Path, class_name: Optional[str] = None
    ) -> None:
        """Generates all components (controller, model, and view) for a module."""
        try:
            logging.info(f"Generating complete module: {module_name}")
            for module_type in self.config.templates.keys():
                self.generate_single(module_name, base_dir, module_type, class_name)
            logging.info(f"Complete module '{module_name}' created successfully")
        except Exception as e:
            logging.error(f"Error generating complete module {module_name}: {e}")
            raise


def prepare_project_paths(ci3_modules_dir: Path, module_name: str) -> None:
    """Validates the required directories exist and the module doesn't already exist."""
    if not ci3_modules_dir.parent.is_dir():
        raise NotADirectoryError(
            f"Directory '{ci3_modules_dir.parent}' does not exist."
        )
    if not ci3_modules_dir.is_dir():
        logging.warning(
            f"Directory '{ci3_modules_dir}'does not exist. Creating it now."
        )
        ci3_modules_dir.mkdir(parents=True, exist_ok=True)

    (ci3_modules_dir / module_name).mkdir(exist_ok=True)


def main():
    try:
        parser = argparse.ArgumentParser(
            description="Generate CodeIgniter 3 module components",
            formatter_class=argparse.RawDescriptionHelpFormatter,
            epilog="""
usage format:
  python3 %(prog)s make:<type_mvc> <module_name> [class_name]

examples:
  # Create component without custom class name
  python3 %(prog)s make:controller blog
  python3 %(prog)s make:model blog
  python3 %(prog)s make:view blog

  # Create component with custom class name
  python3 %(prog)s make:controller blog Post
  python3 %(prog)s make:model blog Post  
  python3 %(prog)s make:view blog post

  # Create all components at once
  python3 %(prog)s make:all blog
  python3 %(prog)s make:all blog post""",
        )
        parser.add_argument(
            "-v", "--version", action="version", version=f"%(prog)s {__version__}"
        )
        subparsers = parser.add_subparsers(
            dest="command", help="Available commands", required=True
        )

        # Logika parser yang disederhanakan
        commands = {
            "make:controller": "controller",
            "make:model": "model",
            "make:view": "view",
            "make:all": "all modul",
        }

        for command, help_text in commands.items():
            cmd_parser = subparsers.add_parser(command, help=f"Generate a {help_text}")
            cmd_parser.add_argument("module_name", help="Name of the module")
            cmd_parser.add_argument(
                "class_name", nargs="?", help="Name of the class (optional)"
            )

        args = parser.parse_args()

        script_dir = Path(__file__).parent
        template_dir = script_dir / "templates"
        ci3_modules_dir = Path.cwd() / "application" / "modules"

        prepare_project_paths(ci3_modules_dir, args.module_name)

        generator = ModuleGenerator(template_dir)

        if args.command == "make:all":
            generator.generate_all(args.module_name, ci3_modules_dir, args.class_name)
        else:
            module_type = args.command.split(":")[1]
            generator.generate_single(
                args.module_name, ci3_modules_dir, module_type, args.class_name
            )

    except (NotADirectoryError, FileNotFoundError) as e:
        logging.error(f"Error Path: {e}")
        exit(1)
    except Exception as e:
        logging.error(f"Unexpected error: {e}")
        exit(1)


if __name__ == "__main__":
    main()
