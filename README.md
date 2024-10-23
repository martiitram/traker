
# TRACKER

This project contains a Symfony application that serves as a simple task tracker.

## Prerequisites

- **Database**: You need a **MariaDB 10.4.32** instance running on port **3306** with **no password**. Alternatively, you can modify the `.env` file and set your database configuration in the `DATABASE_URL` environment variable.

## Setup Instructions

1. **Install Dependencies**:

   ```bash
   composer install -n

2. **Run Database Migrations**:

   ```bash
   bin/console doctrine:migrations:migrate --no-interaction

3. **Start the Server:**

   ```bash
   symfony server:start
You can now access the application at http://localhost:8080/tracker.

## Available Console Commands
You have three custom commands available to interact with the tracker:

1. Start a Task:
    ```bash
   php bin/console app:start TASK_NAME


2. Stop the Current Task:
    ```bash
    php bin/console app:stop

3. List Tracked Tasks:

   ```bash
    php bin/console app:trackerList