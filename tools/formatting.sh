#!/usr/bin/env bash

source "$(dirname "$0")/lib/define.sh"
source "$(dirname "$0")/lib/logger.sh"

APP_PATH="pkl"

format_php_code() {
	log_message "== Formatting PHP code =="

	log_message "Checking apps directory..."
	if ! cd "$APP_PATH"; then
		log_message "Error: Failed to enter apps directory"
		exit 1
	fi

	log_message "Checking pretty-php.phar..."
	if command -v pretty-php.phar . >/dev/null; then
		log_message "Formatting PHP code..."
		if ! pretty-php.phar; then
			log_message "Error: Failed to format PHP code"
			cd ..
			exit 1
		fi
		log_message "PHP code formatted"
	else
		log_message "Error: pretty-php.phar is not installed"
		cd ..
		exit 1
	fi

	cd ..
}

format_tools_code() {
	log_message "== Formatting shell code =="

	log_message "Checking tools directory..."
	if ! cd tools; then
		log_message "Error: Failed to enter tools directory"
		exit 1
	fi

	log_message "Checking fd..."
	if ! command -v fd >/dev/null; then
		log_message "Error: fd is not installed"
		exit 1
	fi

	log_message "Checking shfmt..."
	if ! command -v shfmt >/dev/null; then
		log_message "Error: shfmt is not installed"
		exit 1
	fi

	log_message "Checking black..."
	if ! command -v black >/dev/null; then
		log_message "Error: black is not installed"
		exit 1
	fi

	log_message "Formatting shell code..."
	if ! fd -e sh --full-path tools/ | xargs shfmt -w; then
		log_message "Error: Failed to format shell code"
		exit 1
	fi

	log_message "Formatting python code..."
	if ! fd -e py --full-path tools/ | xargs black; then
		log_message "Error: Failed to format python code"
		exit 1
	fi

	log_message "Shell code formatted"
}

main() {
	format_php_code
	format_tools_code
}

main
