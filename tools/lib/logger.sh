#!/bin/bash

# Fungsi untuk log pesan
log_message() {
	echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1"
}

export -f log_message
