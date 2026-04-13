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
        // Seed Services
        $services = [
            ['name' => 'Classic Manicure', 'description' => 'Basic manicure with nail shaping and polish', 'price' => 25.00, 'duration_minutes' => 30, 'category' => 'Manicure'],
            ['name' => 'Gel Manicure', 'description' => 'Long-lasting gel polish manicure', 'price' => 40.00, 'duration_minutes' => 45, 'category' => 'Manicure'],
            ['name' => 'French Manicure', 'description' => 'Classic French tip manicure', 'price' => 35.00, 'duration_minutes' => 40, 'category' => 'Manicure'],
            ['name' => 'Deluxe Pedicure', 'description' => 'Full pedicure with foot massage', 'price' => 45.00, 'duration_minutes' => 60, 'category' => 'Pedicure'],
            ['name' => 'Spa Pedicure', 'description' => 'Luxury pedicure with paraffin treatment', 'price' => 60.00, 'duration_minutes' => 75, 'category' => 'Pedicure'],
            ['name' => 'Gel Pedicure', 'description' => 'Pedicure with gel polish application', 'price' => 55.00, 'duration_minutes' => 60, 'category' => 'Pedicure'],
            ['name' => 'Nail Art (per nail)', 'description' => 'Custom nail art design', 'price' => 5.00, 'duration_minutes' => 15, 'category' => 'Nail Art'],
            ['name' => 'Acrylic Full Set', 'description' => 'Full set of acrylic nails', 'price' => 55.00, 'duration_minutes' => 90, 'category' => 'Nail Art'],
            ['name' => 'Acrylic Fill-In', 'description' => 'Fill-in for acrylic nails', 'price' => 35.00, 'duration_minutes' => 60, 'category' => 'Nail Art'],
            ['name' => 'Dip Powder Nails', 'description' => 'Dip powder manicure', 'price' => 50.00, 'duration_minutes' => 60, 'category' => 'Nail Art'],
            ['name' => 'Paraffin Treatment', 'description' => 'Warm paraffin wax hand treatment', 'price' => 15.00, 'duration_minutes' => 15, 'category' => 'Treatment'],
            ['name' => 'Callus Removal', 'description' => 'Professional callus removal treatment', 'price' => 20.00, 'duration_minutes' => 20, 'category' => 'Treatment'],
            ['name' => 'Hand Massage', 'description' => 'Relaxing hand and arm massage', 'price' => 15.00, 'duration_minutes' => 15, 'category' => 'Treatment'],
            ['name' => 'Eyebrow Waxing', 'description' => 'Professional eyebrow shaping', 'price' => 15.00, 'duration_minutes' => 15, 'category' => 'Waxing'],
            ['name' => 'Lip Waxing', 'description' => 'Upper lip waxing', 'price' => 10.00, 'duration_minutes' => 10, 'category' => 'Waxing'],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // Seed Staff
        $staff = [
            ['first_name' => 'Maria', 'last_name' => 'Garcia', 'email' => 'maria@nailspa.com', 'phone' => '555-0101', 'position' => 'Senior Technician', 'commission_rate' => 15.00],
            ['first_name' => 'Jenny', 'last_name' => 'Kim', 'email' => 'jenny@nailspa.com', 'phone' => '555-0102', 'position' => 'Technician', 'commission_rate' => 12.00],
            ['first_name' => 'Sarah', 'last_name' => 'Johnson', 'email' => 'sarah@nailspa.com', 'phone' => '555-0103', 'position' => 'Technician', 'commission_rate' => 12.00],
            ['first_name' => 'Lisa', 'last_name' => 'Chen', 'email' => 'lisa@nailspa.com', 'phone' => '555-0104', 'position' => 'Nail Artist', 'commission_rate' => 15.00],
            ['first_name' => 'Amy', 'last_name' => 'Nguyen', 'email' => 'amy@nailspa.com', 'phone' => '555-0105', 'position' => 'Manager', 'commission_rate' => 10.00],
        ];

        foreach ($staff as $member) {
            Staff::create($member);
        }

        // Seed Customers
        $customers = [
            ['first_name' => 'Emily', 'last_name' => 'Wilson', 'email' => 'emily@email.com', 'phone' => '555-0201'],
            ['first_name' => 'Jessica', 'last_name' => 'Brown', 'email' => 'jessica@email.com', 'phone' => '555-0202'],
            ['first_name' => 'Amanda', 'last_name' => 'Davis', 'email' => 'amanda@email.com', 'phone' => '555-0203'],
            ['first_name' => 'Michelle', 'last_name' => 'Miller', 'email' => 'michelle@email.com', 'phone' => '555-0204'],
            ['first_name' => 'Stephanie', 'last_name' => 'Taylor', 'email' => 'stephanie@email.com', 'phone' => '555-0205'],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        // Seed Inventory
        $inventory = [
            ['name' => 'OPI Nail Polish - Red', 'category' => 'Polish', 'quantity' => 24, 'min_quantity' => 6, 'cost_price' => 8.00, 'sale_price' => 15.00, 'unit' => 'bottle'],
            ['name' => 'Essie Nail Polish - Nude', 'category' => 'Polish', 'quantity' => 18, 'min_quantity' => 6, 'cost_price' => 7.50, 'sale_price' => 14.00, 'unit' => 'bottle'],
            ['name' => 'Gel Top Coat', 'category' => 'Polish', 'quantity' => 12, 'min_quantity' => 4, 'cost_price' => 12.00, 'sale_price' => 22.00, 'unit' => 'bottle'],
            ['name' => 'Cuticle Oil', 'category' => 'Supplies', 'quantity' => 30, 'min_quantity' => 10, 'cost_price' => 3.00, 'sale_price' => 8.00, 'unit' => 'bottle'],
            ['name' => 'Nail Files (pack)', 'category' => 'Supplies', 'quantity' => 50, 'min_quantity' => 20, 'cost_price' => 5.00, 'sale_price' => 10.00, 'unit' => 'pack'],
            ['name' => 'Acrylic Powder - Clear', 'category' => 'Supplies', 'quantity' => 8, 'min_quantity' => 3, 'cost_price' => 15.00, 'sale_price' => 28.00, 'unit' => 'jar'],
            ['name' => 'UV/LED Lamp', 'category' => 'Equipment', 'quantity' => 5, 'min_quantity' => 2, 'cost_price' => 40.00, 'sale_price' => 75.00, 'unit' => 'piece'],
            ['name' => 'Hand Cream', 'category' => 'Retail', 'quantity' => 20, 'min_quantity' => 8, 'cost_price' => 6.00, 'sale_price' => 15.00, 'unit' => 'tube'],
            ['name' => 'Foot Scrub', 'category' => 'Retail', 'quantity' => 15, 'min_quantity' => 5, 'cost_price' => 8.00, 'sale_price' => 18.00, 'unit' => 'jar'],
            ['name' => 'Nail Strengthener', 'category' => 'Retail', 'quantity' => 4, 'min_quantity' => 6, 'cost_price' => 10.00, 'sale_price' => 20.00, 'unit' => 'bottle'],
        ];

        foreach ($inventory as $item) {
            Inventory::create($item);
        }
    }
}
