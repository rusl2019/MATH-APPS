#!/usr/bin/env python3
import argparse
import logging
from pathlib import Path
from typing import Dict

__version__ = "2.0.0"

logging.basicConfig(level=logging.INFO, format="[%(levelname)s]: %(message)s")


class MasterGenerator:
    """
    Menghasilkan modul master data lengkap untuk CodeIgniter 3
    dengan pola AJAX, Modal, dan DataTables.
    """

    def __init__(self, template_dir: Path):
        self.template_dir = template_dir
        # Daftar file template dan nama file output yang akan dihasilkan
        self.templates = {
            "controller.php.tpl": "{E_CAP}.php",
            "model.php.tpl": "{E_CAP}_model.php",
            "view_main.php.tpl": "{e_lower}.php",
            "view_create.php.tpl": "{e_lower}_create.php",
            "view_edit.php.tpl": "{e_lower}_edit.php",
            "view_show.php.tpl": "{e_lower}_show.php",
        }

    def read_and_replace(self, template_path: Path, replacements: Dict[str, str]) -> str:
        """Membaca konten template dan mengganti semua placeholder."""
        try:
            content = template_path.read_text(encoding="utf-8")
            for placeholder, value in replacements.items():
                content = content.replace(placeholder, value)
            return content
        except Exception as e:
            logging.error(f"Gagal membaca atau mengganti template {template_path}: {e}")
            raise

    def generate(self, module_name: str, entity_name: str, base_dir: Path):
        """Menjalankan proses pembuatan semua file modul."""
        logging.info(f"Memulai pembuatan modul '{module_name}/{entity_name}'...")

        # Menyiapkan semua variasi nama yang dibutuhkan
        entity_lower = entity_name.lower()
        entity_capital = entity_name.capitalize()
        table_name = f"apps_{entity_lower}s" # Konvensi: apps_ + nama_entitas_jamak
        datatable_id = f"{module_name.replace('_', '-')}-{entity_lower}"

        replacements = {
            "{{module_name}}": module_name,
            "{{entity_name_lower}}": entity_lower,
            "{{entity_name_capital}}": entity_capital,
            "{{table_name}}": table_name,
            "{{datatable_id}}": datatable_id,
        }

        # Menentukan direktori target
        module_path = base_dir / module_name
        dirs = {
            "controllers": module_path / "controllers",
            "models": module_path / "models",
            "views": module_path / "views",
        }

        # Membuat direktori jika belum ada
        for d in dirs.values():
            d.mkdir(parents=True, exist_ok=True)
            logging.info(f"Direktori dipastikan ada: {d}")

        # Proses pembuatan file dari template
        for template_file, output_pattern in self.templates.items():
            try:
                # Menentukan direktori target berdasarkan nama template
                if "controller" in template_file:
                    target_dir = dirs["controllers"]
                elif "model" in template_file:
                    target_dir = dirs["models"]
                else: # view
                    target_dir = dirs["views"]

                template_path = self.template_dir / template_file
                if not template_path.is_file():
                    raise FileNotFoundError(f"File template tidak ditemukan: {template_path}")

                logging.info(f"Membaca template: {template_path}")
                content = self.read_and_replace(template_path, replacements)

                # Menentukan nama file output
                output_filename = output_pattern.format(E_CAP=entity_capital, e_lower=entity_lower)
                output_path = target_dir / output_filename

                logging.info(f"Menulis file ke: {output_path}")
                output_path.write_text(content, encoding="utf-8")
                logging.info(f"Berhasil membuat file: {output_filename}")

            except Exception as e:
                logging.error(f"Terjadi kesalahan saat memproses {template_file}: {e}")
                # Hentikan proses jika ada satu file yang gagal
                return

        logging.info(f"🎉 Modul '{module_name}/{entity_name}' berhasil dibuat!")


def main():
    parser = argparse.ArgumentParser(
        description="Generator untuk Modul Master Data CodeIgniter 3 (AJAX & Modal).",
        epilog="Contoh: python3 %(prog)s master_data student"
    )
    parser.add_argument("module_name", help="Nama modul (folder utama), cth: master_data")
    parser.add_argument("entity_name", help="Nama entitas (controller/model/view), cth: student")
    parser.add_argument("-v", "--version", action="version", version=f"%(prog)s {__version__}")
    args = parser.parse_args()

    try:
        script_dir = Path(__file__).parent
        # Gunakan direktori template yang berbeda untuk generator ini
        template_dir = script_dir / "templates_master"
        ci_modules_dir = Path.cwd() / "application" / "modules"

        if not template_dir.is_dir():
            logging.error(f"Direktori template '{template_dir}' tidak ditemukan.")
            logging.error("Pastikan Anda sudah membuat folder 'templates_master' dan mengisinya.")
            exit(1)

        generator = MasterGenerator(template_dir)
        generator.generate(args.module_name, args.entity_name, ci_modules_dir)

    except Exception as e:
        logging.error(f"Terjadi error tak terduga: {e}")
        exit(1)


if __name__ == "__main__":
    main()