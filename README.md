# TRACKER

This project contains a Symfony application that serves as a simple task tracker. The application is dockerized, and for proper interaction, it is recommended to use **Make**.


## Prerequisites

- Ensure **Docker** and **Docker Compose** are installed and running on your machine.
- Ensure you have **Make** installed.

## Setup Instructions

1. **Build Docker Images**:

   ```bash
   make build

2. **Install Composer Dependencies and Run Symfony Migrations**:
   ```bash
   make prepare

3. **Run Docker Instances:**
   ```bash
   make run

You can now access the application at http://localhost:300/tracker.

You also have access to http://localhost:300/task, which provides a controller with CRUD functionality for manually setting up data for testing purposes.

## Available Console Commands

You have three custom commands available to interact with the tracker via the console:

1. Start a Task:
    ```bash
   docker exec -it docker-symfony-be php bin/console app:stop TASK_NAME


2. Stop the Current Task:
    ```bash
    docker exec -it docker-symfony-be php bin/console app:stop

3. List Tracked Tasks:

   ```bash
    docker exec -it docker-symfony-be php bin/console app:trackerList