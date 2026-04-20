<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Inventory;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Users
        $this->call(UserSeeder::class);

        // Seed Services
        $services = [
            ['name' => 'Classic Manicure', 'category' => 'Manicure', 'price' => 25.00, 'duration_minutes' => 30, 'description' => 'Basic nail shaping, cuticle care, and polish'],
            ['name' => 'Deluxe Manicure', 'category' => 'Manicure', 'price' => 35.00, 'duration_minutes' => 45, 'description' => 'Includes exfoliation, massage, and premium polish'],
            ['name' => 'Gel Manicure', 'category' => 'Manicure', 'price' => 45.00, 'duration_minutes' => 60, 'description' => 'Long-lasting gel polish with UV curing'],
            ['name' => 'Acrylic Full Set', 'category' => 'Artificial Nails', 'price' => 55.00, 'duration_minutes' => 90, 'description' => 'Full set of acrylic nails'],
            ['name' => 'Acrylic Fill-In', 'category' => 'Artificial Nails', 'price' => 35.00, 'duration_minutes' => 60, 'description' => 'Fill-in for existing acrylic nails'],
            ['name' => 'Classic Pedicure', 'category' => 'Pedicure', 'price' => 35.00, 'duration_minutes' => 45, 'description' => 'Foot soak, nail shaping, cuticle care, and polish'],
            ['name' => 'Spa Pedicure', 'category' => 'Pedicure', 'price' => 50.00, 'duration_minutes' => 60, 'description' => 'Luxurious pedicure with mask and extended massage'],
            ['name' => 'Gel Pedicure', 'category' => 'Pedicure', 'price' => 55.00, 'duration_minutes' => 75, 'description' => 'Pedicure with long-lasting gel polish'],
            ['name' => 'Nail Art (per nail)', 'category' => 'Add-ons', 'price' => 5.00, 'duration_minutes' => 15, 'description' => 'Custom nail art design'],
            ['name' => 'French Tips', 'category' => 'Add-ons', 'price' => 10.00, 'duration_minutes' => 20, 'description' => 'Classic French tip design'],
            ['name' => 'Paraffin Wax Treatment', 'category' => 'Add-ons', 'price' => 15.00, 'duration_minutes' => 20, 'description' => 'Moisturizing paraffin wax for hands or feet'],
            ['name' => 'Callus Removal', 'category' => 'Add-ons', 'price' => 10.00, 'duration_minutes' => 15, 'description' => 'Professional callus removal treatment'],
            ['name' => 'Nail Repair', 'category' => 'Services', 'price' => 10.00, 'duration_minutes' => 15, 'description' => 'Repair a broken or cracked nail'],
            ['name' => 'Polish Change', 'category' => 'Services', 'price' => 15.00, 'duration_minutes' => 20, 'description' => 'Remove old polish and apply new'],
            ['name' => 'Kids Manicure', 'category' => 'Special Services', 'price' => 15.00, 'duration_minutes' => 20, 'description' => 'Gentle manicure for children'],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // Seed Staff
        $staff = [
            ['first_name' => 'Maria', 'last_name' => 'Garcia', 'email' => 'maria@nailspa.com', 'phone' => '555-0101', 'role' => 'technician', 'commission_rate' => 15.00],
            ['first_name' => 'Jenny', 'last_name' => 'Kim', 'email' => 'jenny@nailspa.com', 'phone' => '555-0102', 'role' => 'technician', 'commission_rate' => 12.00],
            ['first_name' => 'Sarah', 'last_name' => 'Johnson', 'email' => 'sarah@nailspa.com', 'phone' => '555-0103', 'role' => 'senior_technician', 'commission_rate' => 18.00],
            ['first_name' => 'Lisa', 'last_name' => 'Chen', 'email' => 'lisa@nailspa.com', 'phone' => '555-0104', 'role' => 'manager', 'commission_rate' => 10.00],
        ];

        foreach ($staff as $member) {
            Staff::create($member);
        }

        // Seed Customers
        $customers = [
            ['first_name' => 'Emily', 'last_name' => 'Davis', 'email' => 'emily.d@email.com', 'phone' => '555-1001', 'total_visits' => 5, 'total_spent' => 175.00],
            ['first_name' => 'Jessica', 'last_name' => 'Wilson', 'email' => 'jessica.w@email.com', 'phone' => '555-1002', 'total_visits' => 3, 'total_spent' => 120.00],
            ['first_name' => 'Amanda', 'last_name' => 'Brown', 'email' => 'amanda.b@email.com', 'phone' => '555-1003', 'total_visits' => 8, 'total_spent' => 340.00],
            ['first_name' => 'Michelle', 'last_name' => 'Taylor', 'email' => 'michelle.t@email.com', 'phone' => '555-1004', 'total_visits' => 2, 'total_spent' => 80.00],
            ['first_name' => 'Rachel', 'last_name' => 'Anderson', 'email' => 'rachel.a@email.com', 'phone' => '555-1005', 'total_visits' => 10, 'total_spent' => 450.00],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        // Seed Inventory
        $inventory = [
            ['name' => 'OPI Nail Lacquer - Red', 'category' => 'Polish', 'sku' => 'OPI-RED-001', 'quantity' => 24, 'unit_price' => 9.99, 'reorder_level' => 10, 'supplier' => 'OPI Distributor'],
            ['name' => 'Essie Gel Couture', 'category' => 'Polish', 'sku' => 'ESS-GEL-002', 'quantity' => 18, 'unit_price' => 12.99, 'reorder_level' => 10, 'supplier' => 'Essie Direct'],
            ['name' => 'CND Shellac', 'category' => 'Gel Polish', 'sku' => 'CND-SHEL-003', 'quantity' => 15, 'unit_price' => 15.99, 'reorder_level' => 8, 'supplier' => 'CND Official'],
            ['name' => 'Acrylic Powder - Clear', 'category' => 'Supplies', 'sku' => 'ACR-POW-004', 'quantity' => 12, 'unit_price' => 24.99, 'reorder_level' => 5, 'supplier' => 'Beauty Supply Co'],
            ['name' => 'Acrylic Liquid', 'category' => 'Supplies', 'sku' => 'ACR-LIQ-005', 'quantity' => 8, 'unit_price' => 18.99, 'reorder_level' => 5, 'supplier' => 'Beauty Supply Co'],
            ['name' => 'UV/LED Lamp', 'category' => 'Equipment', 'sku' => 'UV-LAMP-006', 'quantity' => 5, 'unit_price' => 89.99, 'reorder_level' => 2, 'supplier' => 'Salon Equipment Inc'],
            ['name' => 'Nail Files (100 pack)', 'category' => 'Supplies', 'sku' => 'FILE-100-007', 'quantity' => 30, 'unit_price' => 19.99, 'reorder_level' => 10, 'supplier' => 'Beauty Supply Co'],
            ['name' => 'Cuticle Oil', 'category' => 'Care Products', 'sku' => 'CUT-OIL-008', 'quantity' => 20, 'unit_price' => 8.99, 'reorder_level' => 15, 'supplier' => 'Spa Essentials'],
            ['name' => 'Hand Cream', 'category' => 'Retail', 'sku' => 'HND-CRM-009', 'quantity' => 25, 'unit_price' => 14.99, 'reorder_level' => 10, 'supplier' => 'Spa Essentials'],
            ['name' => 'Foot Scrub', 'category' => 'Retail', 'sku' => 'FT-SCR-010', 'quantity' => 15, 'unit_price' => 16.99, 'reorder_level' => 8, 'supplier' => 'Spa Essentials'],
        ];

        foreach ($inventory as $item) {
            Inventory::create($item);
        }
    }
}
