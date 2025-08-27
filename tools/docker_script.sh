#!/bin/bash

set -e

source "$(dirname "$0")/lib/logger.sh"
source "$(dirname "$0")/lib/docker_utils.sh"

main() {
	case $1 in
	delete)
		cleanup_docker
		;;
	compose)
		run_compose
		;;
	db)
		import_database
		;;
	db_table)
		import_database_table "$2"
		;;
	db_data)
		import_data "$2"
		;;
	app)
		enter_container
		;;
	*)
		echo "Usage: $0 [delete|compose|db|db_table|app]"
		echo "  delete  : Delete all containers and images"
		echo "  compose : Run docker compose"
		echo "  db      : Import database"
		echo "  db_table: Import database table"
		echo "  db_data : Import database data"
		echo "  app     : Enter container"
		exit 1
		;;
	esac
}

main "$@"
