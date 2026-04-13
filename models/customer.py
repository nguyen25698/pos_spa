import sqlite3
from datetime import datetime

class Customer:
    """Model for customer management."""
    
    def __init__(self, db_path='nail_spa.db'):
        self.db_path = db_path
        self.create_table()
    
    def create_table(self):
        """Create the customers table if it doesn't exist."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS customers (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                first_name TEXT NOT NULL,
                last_name TEXT NOT NULL,
                phone TEXT,
                email TEXT,
                notes TEXT,
                total_visits INTEGER DEFAULT 0,
                total_spent REAL DEFAULT 0.0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ''')
        
        conn.commit()
        conn.close()
    
    def add_customer(self, first_name, last_name, phone='', email='', notes=''):
        """Add a new customer to the database."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            INSERT INTO customers (first_name, last_name, phone, email, notes)
            VALUES (?, ?, ?, ?, ?)
        ''', (first_name, last_name, phone, email, notes))
        
        customer_id = cursor.lastrowid
        conn.commit()
        conn.close()
        
        return customer_id
    
    def get_customer_by_id(self, customer_id):
        """Get a customer by ID."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('SELECT * FROM customers WHERE id = ?', (customer_id,))
        customer = cursor.fetchone()
        
        conn.close()
        return customer
    
    def get_all_customers(self):
        """Retrieve all customers from the database."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('SELECT * FROM customers ORDER BY last_name, first_name')
        customers = cursor.fetchall()
        
        conn.close()
        return customers
    
    def search_customers(self, search_term):
        """Search customers by name, phone, or email."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        search_pattern = f'%{search_term}%'
        cursor.execute('''
            SELECT * FROM customers 
            WHERE first_name LIKE ? OR last_name LIKE ? OR phone LIKE ? OR email LIKE ?
            ORDER BY last_name, first_name
        ''', (search_pattern, search_pattern, search_pattern, search_pattern))
        
        customers = cursor.fetchall()
        conn.close()
        
        return customers
    
    def update_customer(self, customer_id, first_name=None, last_name=None, 
                       phone=None, email=None, notes=None):
        """Update customer information."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        updates = []
        values = []
        
        if first_name is not None:
            updates.append('first_name = ?')
            values.append(first_name)
        if last_name is not None:
            updates.append('last_name = ?')
            values.append(last_name)
        if phone is not None:
            updates.append('phone = ?')
            values.append(phone)
        if email is not None:
            updates.append('email = ?')
            values.append(email)
        if notes is not None:
            updates.append('notes = ?')
            values.append(notes)
        
        updates.append('updated_at = CURRENT_TIMESTAMP')
        
        if updates:
            values.append(customer_id)
            query = f"UPDATE customers SET {', '.join(updates)} WHERE id = ?"
            cursor.execute(query, values)
            conn.commit()
        
        conn.close()
    
    def update_visit_stats(self, customer_id, amount_spent):
        """Update customer visit statistics."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            UPDATE customers 
            SET total_visits = total_visits + 1,
                total_spent = total_spent + ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ''', (amount_spent, customer_id))
        
        conn.commit()
        conn.close()
