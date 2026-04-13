import sqlite3
from datetime import datetime

class Transaction:
    """Model for POS transactions and sales records."""
    
    def __init__(self, db_path='nail_spa.db'):
        self.db_path = db_path
        self.create_tables()
    
    def create_tables(self):
        """Create transaction tables if they don't exist."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        # Main transaction table
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS transactions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                customer_id INTEGER,
                staff_id INTEGER,
                appointment_id INTEGER,
                subtotal REAL NOT NULL,
                tax REAL DEFAULT 0.0,
                discount REAL DEFAULT 0.0,
                total REAL NOT NULL,
                payment_method TEXT NOT NULL,
                status TEXT DEFAULT 'completed',
                notes TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (customer_id) REFERENCES customers(id),
                FOREIGN KEY (staff_id) REFERENCES staff(id),
                FOREIGN KEY (appointment_id) REFERENCES appointments(id)
            )
        ''')
        
        # Transaction line items
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS transaction_items (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                transaction_id INTEGER NOT NULL,
                item_type TEXT NOT NULL,
                item_id INTEGER NOT NULL,
                item_name TEXT NOT NULL,
                quantity INTEGER DEFAULT 1,
                unit_price REAL NOT NULL,
                total_price REAL NOT NULL,
                FOREIGN KEY (transaction_id) REFERENCES transactions(id)
            )
        ''')
        
        conn.commit()
        conn.close()
    
    def create_transaction(self, customer_id=None, staff_id=None, appointment_id=None,
                          payment_method='cash', notes=''):
        """Create a new transaction record."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            INSERT INTO transactions (customer_id, staff_id, appointment_id, 
                                     subtotal, tax, discount, total, payment_method, notes)
            VALUES (?, ?, ?, 0, 0, 0, 0, ?, ?)
        ''', (customer_id, staff_id, appointment_id, payment_method, notes))
        
        transaction_id = cursor.lastrowid
        conn.commit()
        conn.close()
        
        return transaction_id
    
    def add_transaction_item(self, transaction_id, item_type, item_id, 
                            item_name, quantity, unit_price):
        """Add an item to a transaction."""
        total_price = quantity * unit_price
        
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            INSERT INTO transaction_items (transaction_id, item_type, item_id, 
                                         item_name, quantity, unit_price, total_price)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ''', (transaction_id, item_type, item_id, item_name, quantity, unit_price, total_price))
        
        # Update transaction totals
        cursor.execute('SELECT subtotal FROM transactions WHERE id = ?', (transaction_id,))
        current_subtotal = cursor.fetchone()[0]
        
        new_subtotal = current_subtotal + total_price
        tax = new_subtotal * 0.08  # 8% tax rate
        total = new_subtotal + tax
        
        cursor.execute('''
            UPDATE transactions 
            SET subtotal = ?, tax = ?, total = ?
            WHERE id = ?
        ''', (new_subtotal, tax, total, transaction_id))
        
        item_id = cursor.lastrowid
        conn.commit()
        conn.close()
        
        return item_id
    
    def get_transaction_by_id(self, transaction_id):
        """Get a transaction with all its items."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('SELECT * FROM transactions WHERE id = ?', (transaction_id,))
        transaction = cursor.fetchone()
        
        cursor.execute('''
            SELECT * FROM transaction_items WHERE transaction_id = ?
        ''', (transaction_id,))
        items = cursor.fetchall()
        
        conn.close()
        
        return {'transaction': transaction, 'items': items}
    
    def get_all_transactions(self, start_date=None, end_date=None):
        """Get all transactions with optional date range."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        query = 'SELECT * FROM transactions WHERE 1=1'
        params = []
        
        if start_date:
            query += ' AND DATE(created_at) >= ?'
            params.append(start_date)
        
        if end_date:
            query += ' AND DATE(created_at) <= ?'
            params.append(end_date)
        
        query += ' ORDER BY created_at DESC'
        
        cursor.execute(query, params)
        transactions = cursor.fetchall()
        
        conn.close()
        return transactions
    
    def complete_transaction(self, transaction_id, customer_id=None):
        """Mark transaction as completed and update customer stats."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('UPDATE transactions SET status = ? WHERE id = ?', 
                      ('completed', transaction_id))
        
        # Update customer visit statistics if customer_id provided
        if customer_id:
            cursor.execute('SELECT total FROM transactions WHERE id = ?', (transaction_id,))
            total = cursor.fetchone()[0]
            
            from models.customer import Customer
            customer = Customer(self.db_path)
            customer.update_visit_stats(customer_id, total)
        
        conn.commit()
        conn.close()
    
    def get_daily_sales(self, date=None):
        """Get total sales for a specific day."""
        from datetime import date as dt
        if date is None:
            date = dt.today().isoformat()
        
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            SELECT COUNT(*), SUM(total), SUM(subtotal), SUM(tax)
            FROM transactions
            WHERE DATE(created_at) = ? AND status = 'completed'
        ''', (date,))
        
        result = cursor.fetchone()
        conn.close()
        
        return {
            'transaction_count': result[0] or 0,
            'total_sales': result[1] or 0.0,
            'subtotal': result[2] or 0.0,
            'tax_collected': result[3] or 0.0
        }
