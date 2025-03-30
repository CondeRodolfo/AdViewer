# Laravel API Data Importer

A simple Laravel application that imports data from an external API.

## Requirements

- Docker and Docker Compose

## Setup Instructions

1. Clone this repository
2. Copy `src/.env.example` to `src/.env` and add the API_KEY to it
3. Run Docker containers:
   ```
   docker-compose up -d
   ```
4. Access the application at http://localhost:8000

## Features

- Simple web interface with a "Refresh Data" button
- Imports data from an external API
- Stores the data in a MySQL database