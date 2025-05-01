#!/bin/sh
set -e

# Generate Apache configuration using the Python script
python3 /usr/local/bin/configure-apache.py

# Execute the CMD passed to the container (e.g., httpd-foreground)
exec "$@"
