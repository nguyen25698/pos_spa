# Nail Spa Salon POS System

A complete Point of Sale system designed specifically for nail spa salons.

## Features

- **Service Management**: Manicure, pedicure, nail art, and other spa services with pricing and duration
- **Appointment Booking**: Schedule and manage customer appointments with staff assignment
- **Customer Management**: Track customer information, visit history, and lifetime value
- **Staff Management**: Manage technicians, roles, and commission rates
- **Inventory Tracking**: Monitor nail polishes, supplies, and retail products with low-stock alerts
- **POS Terminal**: Process transactions with multiple payment methods and receipt generation
- **Sales Reporting**: Daily sales summaries, popular services, and business analytics

## Quick Start

```bash
python main.py
```

## Project Structure

```
/workspace/
├── main.py              # Main application entry point
├── models/              # Database models
│   ├── service.py       # Service management (manicure, pedicure, etc.)
│   ├── customer.py      # Customer database and history
│   ├── staff.py         # Staff/technician management
│   ├── appointment.py   # Appointment scheduling
│   ├── inventory.py     # Inventory tracking (polishes, supplies, retail)
│   └── transaction.py   # POS transactions and sales records
├── services/            # Business logic layer (for future expansion)
├── utils/               # Helper utilities
└── README.md
```

## Default Services Included

| Service | Price | Duration | Category |
|---------|-------|----------|----------|
| Classic Manicure | $25 | 30 min | Manicure |
| Gel Manicure | $40 | 45 min | Manicure |
| Deluxe Manicure | $35 | 45 min | Manicure |
| Classic Pedicure | $35 | 45 min | Pedicure |
| Spa Pedicure | $50 | 60 min | Pedicure |
| Acrylic Full Set | $55 | 90 min | Nail Extensions |
| Gel Full Set | $60 | 90 min | Nail Extensions |
| Nail Art (per nail) | $5 | 10 min | Nail Art |
| Paraffin Wax Treatment | $15 | 15 min | Add-ons |

## Usage

1. **Run the application**: `python main.py`
2. **Initialize data**: Default services, staff, and inventory are auto-populated on first run
3. **Navigate menus**: Use numbered menu options to access different features
4. **Process sales**: Use the POS Terminal to checkout customers with services and retail products

## Technology Stack

- Python 3.x
- SQLite (embedded database - no setup required)
- Command-line interface (CLI)

## Database

The system uses SQLite with the following tables:
- `services` - Available spa services
- `customers` - Customer records and statistics
- `staff` - Employee information
- `appointments` - Scheduled appointments
- `inventory` - Products and supplies
- `transactions` - Sales records
- `transaction_items` - Line items for each transaction
