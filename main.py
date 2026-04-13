#!/usr/bin/env python3
"""
Nail Spa Salon POS System
Main Application Entry Point
"""

import sqlite3
from datetime import datetime, date
import sys

from models.service import Service
from models.customer import Customer
from models.staff import Staff
from models.appointment import Appointment
from models.inventory import Inventory
from models.transaction import Transaction


class NailSpaPOS:
    def __init__(self, db_path='nail_spa.db'):
        self.db_path = db_path
        self.service = Service(db_path)
        self.customer = Customer(db_path)
        self.staff = Staff(db_path)
        self.appointment = Appointment(db_path)
        self.inventory = Inventory(db_path)
        self.transaction = Transaction(db_path)
        self.initialize_defaults()
    
    def initialize_defaults(self):
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        cursor.execute('SELECT COUNT(*) FROM services')
        if cursor.fetchone()[0] == 0:
            print("Initializing default services...")
            self.service.initialize_default_services()
        cursor.execute('SELECT COUNT(*) FROM staff')
        if cursor.fetchone()[0] == 0:
            print("Initializing default staff...")
            self.staff.initialize_default_staff()
        cursor.execute('SELECT COUNT(*) FROM inventory')
        if cursor.fetchone()[0] == 0:
            print("Initializing default inventory...")
            self.inventory.initialize_default_inventory()
        conn.close()
    
    def display_menu(self):
        print("\n" + "="*60)
        print("       NAIL SPA SALON POS SYSTEM")
        print("="*60)
        print("1. Services Management")
        print("2. Customer Management")
        print("3. Staff Management")
        print("4. Appointments")
        print("5. POS Terminal (Checkout)")
        print("6. Inventory Management")
        print("7. Reports")
        print("8. Exit")
        print("="*60)
    
    def run(self):
        print("\n" + "="*60)
        print("   Welcome to Nail Spa Salon POS System!")
        print("="*60)
        while True:
            self.display_menu()
            choice = input("\nEnter your choice (1-8): ")
            if choice == '1':
                self.services_menu()
            elif choice == '2':
                self.customers_menu()
            elif choice == '3':
                self.staff_menu()
            elif choice == '4':
                self.appointments_menu()
            elif choice == '5':
                self.pos_terminal()
            elif choice == '6':
                self.inventory_menu()
            elif choice == '7':
                self.reports_menu()
            elif choice == '8':
                print("\nThank you for using Nail Spa POS. Goodbye!")
                break
            else:
                print("Invalid choice. Please try again.")

    def services_menu(self):
        while True:
            print("\n--- SERVICES MANAGEMENT ---")
            print("1. View All Services")
            print("2. Add New Service")
            print("3. Update Service")
            print("4. Deactivate Service")
            print("5. Back")
            choice = input("Choice: ")
            if choice == '1':
                services = self.service.get_all_services()
                print("\n{:<5} {:<30} {:<10} {:<15}".format("ID", "Name", "Price", "Duration"))
                for s in services:
                    print("{:<5} {:<30} ${:<9.2f} {:<15}".format(s[0], s[1], s[3], f"{s[4]} min"))
            elif choice == '2':
                name = input("Name: ")
                price = float(input("Price: "))
                duration = int(input("Duration (min): "))
                self.service.add_service(name, price, duration)
                print("Added!")
            elif choice == '5':
                break

    def customers_menu(self):
        while True:
            print("\n--- CUSTOMERS ---")
            print("1. View All")
            print("2. Add Customer")
            print("3. Search")
            print("4. Back")
            choice = input("Choice: ")
            if choice == '1':
                for c in self.customer.get_all_customers():
                    print(f"{c[0]}. {c[1]} {c[2]} - {c[3]}")
            elif choice == '2':
                fn = input("First: ")
                ln = input("Last: ")
                phone = input("Phone: ")
                self.customer.add_customer(fn, ln, phone)
                print("Added!")
            elif choice == '4':
                break

    def staff_menu(self):
        while True:
            print("\n--- STAFF ---")
            print("1. View All")
            print("2. Add Staff")
            print("3. Back")
            choice = input("Choice: ")
            if choice == '1':
                for s in self.staff.get_all_staff():
                    print(f"{s[0]}. {s[1]} {s[2]} - {s[5]}")
            elif choice == '2':
                fn = input("First: ")
                ln = input("Last: ")
                role = input("Role: ")
                self.staff.add_staff(fn, ln, role=role)
                print("Added!")
            elif choice == '3':
                break

    def appointments_menu(self):
        while True:
            print("\n--- APPOINTMENTS ---")
            print("1. View All")
            print("2. Book")
            print("3. Back")
            choice = input("Choice: ")
            if choice == '1':
                for a in self.appointment.get_all_appointments():
                    print(f"{a[0]}. {a[7]} {a[8]} - {a[9]} @ {a[5]} ({a[6]})")
            elif choice == '2':
                cid = int(input("Customer ID: "))
                sid = int(input("Service ID: "))
                d = input("Date (YYYY-MM-DD): ")
                t = input("Time (HH:MM): ")
                self.appointment.book_appointment(cid, sid, d, t)
                print("Booked!")
            elif choice == '3':
                break

    def pos_terminal(self):
        print("\n--- POS TERMINAL ---")
        items = []
        while True:
            print("\n1. Add Service")
            print("2. Checkout")
            c = input("Choice: ")
            if c == '1':
                for s in self.service.get_all_services():
                    print(f"{s[0]}. {s[1]} - ${s[3]:.2f}")
                sid = int(input("Service ID: "))
                svc = self.service.get_service_by_id(sid)
                items.append({'name': svc[1], 'price': svc[3]})
            elif c == '2':
                break
        if not items:
            return
        total = sum(i['price'] for i in items)
        tax = total * 0.08
        grand = total + tax
        print("\n=== RECEIPT ===")
        for i in items:
            print(f"{i['name']}: ${i['price']:.2f}")
        print(f"Subtotal: ${total:.2f}")
        print(f"Tax: ${tax:.2f}")
        print(f"TOTAL: ${grand:.2f}")
        pm = input("Payment (cash/credit): ")
        tid = self.transaction.create_transaction(payment_method=pm)
        for i in items:
            self.transaction.add_transaction_item(tid, 'service', 0, i['name'], 1, i['price'])
        self.transaction.complete_transaction(tid)
        print("Done!")

    def inventory_menu(self):
        while True:
            print("\n--- INVENTORY ---")
            print("1. View All")
            print("2. Low Stock")
            print("3. Back")
            c = input("Choice: ")
            if c == '1':
                for i in self.inventory.get_all_items():
                    print(f"{i[0]}. {i[1]} - Qty: {i[3]}")
            elif c == '2':
                for i in self.inventory.get_low_stock_items():
                    print(f"{i[0]}. {i[1]} - Qty: {i[3]} (LOW!)")
            elif c == '3':
                break

    def reports_menu(self):
        print("\n--- REPORTS ---")
        sales = self.transaction.get_daily_sales()
        print(f"Today: {sales['transaction_count']} transactions, ${sales['total_sales']:.2f}")
        input("Press Enter to go back")

if __name__ == "__main__":
    try:
        pos = NailSpaPOS()
        pos.run()
    except KeyboardInterrupt:
        print("\nGoodbye!")
