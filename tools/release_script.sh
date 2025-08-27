#!/bin/bash

set -e

source "$(dirname "$0")/lib/logger.sh"
source "$(dirname "$0")/lib/release_utils.sh"

main() {
	case $1 in
	make)
		log_message "Starting release process..."
		check_dependencies
		generate_hash
		create_gz
		log_message "Release process completed!"
		;;
	clean)
		log_message "Starting cleanup process..."
		clean_apps
		log_message "Cleanup process completed!"
		;;
	*)
		echo "Usage: $0 [make|clean]"
		echo "  make    : Build application"
		echo "  clean   : Clean application"
		exit 1
		;;
	esac
}

main "$@"
