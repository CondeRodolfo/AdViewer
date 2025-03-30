# Laravel API Data Importer

A simple Laravel application that imports data from an external API.

## Requirements

- Docker and Docker Compose

## Setup Instructions

1. Clone this repository
2. Copy `src/.env.example` to `src/.env` for the Laravel application and Docker configuration
3. Run Docker containers:
   ```
   docker-compose up -d
   ```
4. Access the application at http://localhost:8000

## Features

- Simple web interface with a "Refresh Data" button
- Imports data from an external API
- Stores the data in a MySQL database

## Development

To work on this project:

1. Clone the repository
2. Configure `src/.env` as needed
3. Run the Docker containers with `docker-compose up -d`
4. Make your changes in the `src` directory
5. Access the Laravel container shell with:
   ```
   docker-compose exec app bash
   ``` 