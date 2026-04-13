import sqlite3
from datetime import datetime

class Service:
    """Model for spa services like manicure, pedicure, nail art, etc."""
    
    def __init__(self, db_path='nail_spa.db'):
        self.db_path = db_path
        self.create_table()
    
    def create_table(self):
        """Create the services table if it doesn't exist."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS services (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                description TEXT,
                price REAL NOT NULL,
                duration_minutes INTEGER NOT NULL,
                category TEXT,
                is_active BOOLEAN DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ''')
        
        conn.commit()
        conn.close()
    
    def add_service(self, name, price, duration_minutes, description='', category='General'):
        """Add a new service to the database."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            INSERT INTO services (name, description, price, duration_minutes, category)
            VALUES (?, ?, ?, ?, ?)
        ''', (name, description, price, duration_minutes, category))
        
        service_id = cursor.lastrowid
        conn.commit()
        conn.close()
        
        return service_id
    
    def get_all_services(self, active_only=True):
        """Retrieve all services from the database."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        if active_only:
            cursor.execute('SELECT * FROM services WHERE is_active = 1 ORDER BY category, name')
        else:
            cursor.execute('SELECT * FROM services ORDER BY category, name')
        
        services = cursor.fetchall()
        conn.close()
        
        return services
    
    def get_service_by_id(self, service_id):
        """Get a specific service by ID."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('SELECT * FROM services WHERE id = ?', (service_id,))
        service = cursor.fetchone()
        
        conn.close()
        return service
    
    def update_service(self, service_id, name=None, price=None, duration_minutes=None, 
                      description=None, category=None):
        """Update an existing service."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        updates = []
        values = []
        
        if name is not None:
            updates.append('name = ?')
            values.append(name)
        if price is not None:
            updates.append('price = ?')
            values.append(price)
        if duration_minutes is not None:
            updates.append('duration_minutes = ?')
            values.append(duration_minutes)
        if description is not None:
            updates.append('description = ?')
            values.append(description)
        if category is not None:
            updates.append('category = ?')
            values.append(category)
        
        if updates:
            values.append(service_id)
            query = f"UPDATE services SET {', '.join(updates)} WHERE id = ?"
            cursor.execute(query, values)
            conn.commit()
        
        conn.close()
    
    def deactivate_service(self, service_id):
        """Deactivate a service (soft delete)."""
        self.update_service(service_id, is_active=0)
    
    def initialize_default_services(self):
        """Initialize with default nail spa services."""
        default_services = [
            ('Classic Manicure', 25.00, 30, 'Basic manicure with nail shaping, cuticle care, and polish', 'Manicure'),
            ('Gel Manicure', 40.00, 45, 'Long-lasting gel polish manicure', 'Manicure'),
            ('Deluxe Manicure', 35.00, 45, 'Manicure with exfoliation and massage', 'Manicure'),
            ('Classic Pedicure', 35.00, 45, 'Basic pedicure with foot soak and polish', 'Pedicure'),
            ('Spa Pedicure', 50.00, 60, 'Luxurious pedicure with scrub, mask, and massage', 'Pedicure'),
            ('Deluxe Pedicure', 45.00, 60, 'Complete pedicure with callus removal', 'Pedicure'),
            ('Acrylic Full Set', 55.00, 90, 'Full set of acrylic nails', 'Nail Extensions'),
            ('Acrylic Fill', 35.00, 60, 'Fill-in for acrylic nails', 'Nail Extensions'),
            ('Gel Full Set', 60.00, 90, 'Full set of gel nail extensions', 'Nail Extensions'),
            ('Gel Removal', 15.00, 20, 'Safe removal of gel polish or extensions', 'Removal'),
            ('Acrylic Removal', 20.00, 30, 'Safe removal of acrylic nails', 'Removal'),
            ('Nail Art (per nail)', 5.00, 10, 'Custom nail art design per nail', 'Nail Art'),
            ('French Tips', 10.00, 15, 'Classic French tip design', 'Nail Art'),
            ('Paraffin Wax Treatment', 15.00, 15, 'Moisturizing paraffin wax for hands or feet', 'Add-ons'),
            ('Callus Removal', 10.00, 15, 'Professional callus treatment', 'Add-ons'),
        ]
        
        for service in default_services:
            self.add_service(*service)
