#!/bin/bash

# Navigate to the directory of your Laravel application
cd /Users/rootoole/Desktop/laravel-react-test-app/my-laravel-project


# Check if Docker is running
if ! docker info >/dev/null 2>&1; then
    echo "Docker is not running. Please start Docker and try again."
    exit 1
fi

# Start the Docker containers using docker-compose
echo "Starting Docker containers..."
docker-compose up -d

# Check if the containers started successfully
if [ $? -eq 0 ]; then
    echo "Docker containers started successfully."
else
    echo "Failed to start Docker containers."
    exit 1
fi

# Additional commands to set up your Laravel application
# For example, migrations and seeders
echo "Running migrations and seeders..."
docker-compose exec app php artisan migrate --seed

echo "Setup complete. Your Laravel application should now be running."
