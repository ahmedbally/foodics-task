# Foodics Task

## Overview

Foodics Task is a Laravel-based API application designed to manage ingredients and their stock levels. The application sends notifications when the stock of an ingredient falls below a specified threshold.

## Requirements

- PHP 8.2 or higher
- Composer
- MySQL

## Installation

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

## Usage

1. **Start the local development server:**
    ```sh
    php artisan serve
    ```
2. **Run the queue worker:**
    ```sh
    php artisan queue:work
    ```
3. **Access the API:**
   Use tools like Postman or cURL to interact with the API at `http://localhost:8000`.

## Features

- **Ingredient Management:** Add, update, and delete ingredients.
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
