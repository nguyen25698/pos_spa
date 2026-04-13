# Nail Spa POS System - Laravel + PHP + MySQL

A complete Point of Sale (POS) system for nail spa salons built with Laravel, PHP, and MySQL.

## Features

- **Service Management**: Manage nail services (manicure, pedicure, nail art, etc.) with pricing and duration
- **Customer Database**: Track customer information, visit history, and preferences
- **Staff Management**: Manage technicians with commission tracking
- **Appointment Booking**: Schedule and manage appointments
- **POS Terminal**: Process sales transactions with receipt generation
- **Inventory Management**: Track polishes, supplies, and retail products
- **Reports & Analytics**: Sales reports, staff performance, inventory alerts

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL 5.7+ or MariaDB 10.3+
- Node.js & NPM (for frontend assets)

## Installation

### 1. Clone or Setup Project

```bash
cd /workspace/nail-spa-pos
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Configuration

Copy the example environment file and configure your database:

```bash
cp .env.example .env
```

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nail_spa_pos
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations

Create the database first, then run:

```bash
php artisan migrate --seed
```

### 6. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Default Data

The seeder creates sample data including:
- 15 nail spa services (manicures, pedicures, nail art, treatments)
- 5 staff members (technicians)
- Sample customers
- Sample inventory items

## Project Structure

```
nail-spa-pos/
├── app/
│   ├── Models/
│   │   ├── Service.php
│   │   ├── Customer.php
│   │   ├── Staff.php
│   │   ├── Appointment.php
│   │   ├── Inventory.php
│   │   ├── Transaction.php
│   │   └── TransactionItem.php
│   └── Http/
│       └── Controllers/
│           ├── ServiceController.php
│           ├── CustomerController.php
│           ├── StaffController.php
│           ├── AppointmentController.php
│           ├── PosController.php
│           ├── InventoryController.php
│           └── ReportController.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
├── routes/
│   └── web.php
└── public/
```

## API Endpoints

- `GET /services` - List all services
- `POST /services` - Create new service
- `GET /customers` - List customers
- `POST /customers` - Create customer
- `GET /staff` - List staff members
- `POST /appointments` - Book appointment
- `POST /pos/checkout` - Process sale
- `GET /inventory` - View inventory
- `GET /reports/sales` - Sales reports

## License

MIT License
