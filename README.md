# Foodics Task

## Overview

Foodics Task is a Laravel-based API application designed to manage ingredients and their stock levels. The application sends notifications when the stock of an ingredient falls below a specified threshold.

## Requirements

- Docker
- Docker Compose
- PHP 8.2 or higher (for manual deployment)
- Composer (for manual deployment)
- MySQL (for manual deployment)

## Installation

### Deploy with Docker

1. **Clone the repository:**
    ```sh
    git clone https://github.com/yourusername/foodics-task.git
    cd foodics-task
    ```

2. **Copy the `.env` file:**
    ```sh
    cp .env.example .env
    ```

3. **Build and start the containers:**
    ```sh
    docker-compose up --build -d
    ```

### Manual Deployment

1. **Clone the repository:**
    ```sh
    git clone https://github.com/yourusername/foodics-task.git
    cd foodics-task
    ```

2. **Install PHP dependencies:**
    ```sh
    composer install
    ```

3. **Copy the `.env` file:**
    ```sh
    cp .env.example .env
    ```

4. **Generate the application key:**
    ```sh
    php artisan key:generate
    ```

5. **Configure the `.env` file:**
    - Set your database credentials.
    - Set your mail configuration.

6. **Run database migrations:**
    ```sh
    php artisan migrate
    ```

7. **Seed the database:**
    ```sh
    php artisan db:seed
    ```

## Usage

### Docker

1. **Start the containers:**
    ```sh
    docker-compose up -d
    ```

2. **Access the API:**
   Use tools like Postman or cURL to interact with the API at `http://127.0.0.1:8000`.

3. **Access Mailpit:**
   Open your browser and go to `http://127.0.0.1:8025`.

4. **Access Adminer:**
   Open your browser and go to `http://127.0.0.1:8080`.

### Manual

1. **Start the local development server:**
    ```sh
    php artisan serve
    ```

2. **Run the queue worker:**
    ```sh
    php artisan queue:work
    ```

3. **Access the API:**
   Use tools like Postman or cURL to interact with the API at `http://127.0.0.1:8000`.

## Features

- **Ordering:** Creating orders.
- **Stock Monitoring:** Automatically monitor stock levels and send notifications when stock is low.
- **Email Notifications:** Send email notifications when an ingredient's stock falls below the threshold.

## Configuration

- **Stock Minimum Percentage:** Set the minimum stock percentage in the `.env` file:
    ```dotenv
    STOCK_MIN_PERCENTAGE=50
    ```

- **Notification Email:** Set the notification email address in the `.env` file:
    ```dotenv
    STOCK_NOTIFICATION_EMAIL=mail@example.com
    ```
  
## Postman Collection

To facilitate testing and interacting with the API, a Postman collection is provided.

### Importing the Collection

1. **Download the Postman collection file:**
    - [Foodics Task Postman Collection](https://github.com/ahmedbally/foodics-task/blob/main/postman_collection.json)

2. **Open Postman.**

3. **Import the collection:**
    - Click on the `Import` button in the top left corner.
    - Select the `File` tab.
    - Choose the downloaded `postman_collection.json` file.
    - Click `Import`.
4. **Use the collection to test the API.**
