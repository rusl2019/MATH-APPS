#!/bin/bash

source "$(dirname "$0")/lib/define.sh"
source "$(dirname "$0")/lib/logger.sh"

check_dependencies() {
	log_message "Checking dependencies..."

	if ! command -v npm &>/dev/null; then
		log_message "npm not found, please install it first"
		exit 1
	fi

	if ! command -v terser &>/dev/null; then
		log_message "Installing terser..."
		npm install terser -g
	fi

	if ! command -v cleancss &>/dev/null; then
		log_message "Installing clean-css-cli..."
		npm install clean-css-cli -g
	fi
}

generate_hash() {
	log_message "Running generate hash..."
	python3 ./tools/generate_hash.py
}

create_zip() {
	local timestamp=$(date +%Y%m%d%H%M%S)
	local zip_name="${GENERAL_APP_NAME}-${timestamp}.zip"

	log_message "Creating zip file: ${zip_name}"
	zip -r "./${zip_name}" $APP_PATH

	if [ $? -eq 0 ]; then
		log_message "zip file created successfully: ${zip_name}"
		return 0
	else
		log_message "Error: Failed to create zip file"
		return 1
	fi
}

create_gz() {
	local timestamp=$(date +%Y%m%d%H%M%S)
	local gz_name="${GENERAL_APP_NAME}-${timestamp}.tar.gz"

	log_message "Creating tar.gz file: ${gz_name}"
	tar -c $APP_PATH | pigz >"./${gz_name}"

	if [ $? -eq 0 ]; then
		log_message "tar.gz file created successfully: ${gz_name}"
		return 0
	else
		log_message "Error: Failed to create tar.gz file"
		return 1
	fi
}

clean_apps() {
	log_message "Starting apps directory cleanup..."

	log_message "Restoring git changes..."
	git restore .

	log_message "Removing untracked files..."
	untracked_files=$(git status | grep "$APP_PATH")

	if [ -n "$untracked_files" ]; then
		echo "$untracked_files" | xargs rm -rf
		log_message "Untracked files successfully removed"
	else
		log_message "No files need to be removed"
	fi
}

export -f check_dependencies generate_hash create_zip clean_apps
