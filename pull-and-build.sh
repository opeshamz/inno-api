#!/bin/bash

# Pull the PHP image first
echo "Pulling php:8.2-fpm-alpine image..."
docker pull php:8.2-fpm-alpine

# Now, build the Docker image using Docker Compose (or direct Docker commands)
echo "Building the Docker image..."
sudo docker-compose up
