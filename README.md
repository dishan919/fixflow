# FixFlow

FixFlow is a Community Repair & Maintenance Tracker built using PHP, MySQL, HTML, CSS, JavaScript, and Docker.

## Features

- Report new maintenance issues
- View all reported issues
- Edit issue information
- Update issue status
- Delete issues
- Search issues
- Filter issues by status
- Dashboard issue counts
- Persistent MySQL data using Docker volumes

## Issue Status

- Reported
- In Progress
- Resolved

## Technologies

- HTML
- CSS
- JavaScript
- PHP 8.2
- MySQL 8.0
- Apache
- Docker
- Docker Compose
- phpMyAdmin

## Project Structure

```text
fixflow/
├── database/
│   └── init.sql
├── src/
│   ├── assets/
│   │   ├── css/
│   │   └── js/
│   ├── config/
│   │   └── database.php
│   ├── index.php
│   ├── create.php
│   ├── edit.php
│   └── delete.php
├── .env.example
├── .gitignore
├── Dockerfile
├── docker-compose.yml
└── README.md