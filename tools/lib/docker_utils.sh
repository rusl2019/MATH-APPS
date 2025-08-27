#!/bin/bash

source "$(dirname "$0")/lib/logger.sh"
source "$(dirname "$0")/lib/define.sh"

cleanup_docker() {
	log_message "Stopping running containers..."
	docker ps -a --format "{{.ID}}" | xargs -r docker stop

	log_message "Removing containers..."
	docker ps -a --format "{{.ID}}" | xargs -r docker rm

	log_message "Removing volumes..."
	docker volume ls -q | xargs -r docker volume rm

	log_message "Removing $GENERAL_APP_NAME images..."
	docker images | grep $GENERAL_APP_NAME | awk '{print $3}' | xargs -r docker rmi
}

run_compose() {
	log_message "Running docker compose..."
	docker compose up -d
}

import_database() {
	log_message "Importing database..."
	docker exec -it $DB_CONTAINER bash -c "mariadb -u $DB_USER -p'$DB_PASSWORD' $DB_NAME < ~/database/$DB_FILE"
}

import_database_table() {
	log_message "Importing database table..."
	table="$1"
	DB_FILE="table.sql"
	if [ -z "$table" ]; then
		log_error "Table name is required"
		exit 1
	fi
	docker exec -it $DB_CONTAINER bash -c "mariadb -u $DB_USER -p'$DB_PASSWORD' $DB_NAME < ~/database/$table/$DB_FILE"
}

import_data() {
	log_message "Importing data to database..."
	table="$1"
	DB_FILE="data.sql"
	if [ -z "$table" ]; then
		log_error "Directory name is required"
		exit 1
	fi
	docker exec -it $DB_CONTAINER bash -c "mariadb -u $DB_USER -p'$DB_PASSWORD' $DB_NAME < ~/data/$table/$DB_FILE"
}

enter_container() {
	docker exec -it $APP_CONTAINER bash
}

export -f cleanup_docker run_compose import_database enter_container
