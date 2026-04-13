import sqlite3

class Staff:
    """Model for staff/technician management."""
    
    def __init__(self, db_path='nail_spa.db'):
        self.db_path = db_path
        self.create_table()
    
    def create_table(self):
        """Create the staff table if it doesn't exist."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS staff (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                first_name TEXT NOT NULL,
                last_name TEXT NOT NULL,
                phone TEXT,
                email TEXT,
                role TEXT DEFAULT 'Technician',
                commission_rate REAL DEFAULT 0.0,
                is_active BOOLEAN DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ''')
        
        conn.commit()
        conn.close()
    
    def add_staff(self, first_name, last_name, phone='', email='', 
                  role='Technician', commission_rate=0.0):
        """Add a new staff member."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            INSERT INTO staff (first_name, last_name, phone, email, role, commission_rate)
            VALUES (?, ?, ?, ?, ?, ?)
        ''', (first_name, last_name, phone, email, role, commission_rate))
        
        staff_id = cursor.lastrowid
        conn.commit()
        conn.close()
        
        return staff_id
    
    def get_staff_by_id(self, staff_id):
        """Get a staff member by ID."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('SELECT * FROM staff WHERE id = ?', (staff_id,))
        staff = cursor.fetchone()
        
        conn.close()
        return staff
    
    def get_all_staff(self, active_only=True):
        """Retrieve all staff members."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        if active_only:
            cursor.execute('SELECT * FROM staff WHERE is_active = 1 ORDER BY last_name, first_name')
        else:
            cursor.execute('SELECT * FROM staff ORDER BY last_name, first_name')
        
        staff = cursor.fetchall()
        conn.close()
        
        return staff
    
    def update_staff(self, staff_id, first_name=None, last_name=None, 
                    phone=None, email=None, role=None, commission_rate=None):
        """Update staff information."""
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
        if role is not None:
            updates.append('role = ?')
            values.append(role)
        if commission_rate is not None:
            updates.append('commission_rate = ?')
            values.append(commission_rate)
        
        if updates:
            values.append(staff_id)
            query = f"UPDATE staff SET {', '.join(updates)} WHERE id = ?"
            cursor.execute(query, values)
            conn.commit()
        
        conn.close()
    
    def deactivate_staff(self, staff_id):
        """Deactivate a staff member."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('UPDATE staff SET is_active = 0 WHERE id = ?', (staff_id,))
        conn.commit()
        conn.close()
    
    def initialize_default_staff(self):
        """Initialize with default staff members."""
        default_staff = [
            ('Maria', 'Garcia', '555-0101', 'maria@nailspa.com', 'Senior Technician', 0.15),
            ('Jennifer', 'Lee', '555-0102', 'jennifer@nailspa.com', 'Technician', 0.12),
            ('Sarah', 'Johnson', '555-0103', 'sarah@nailspa.com', 'Technician', 0.12),
            ('Emily', 'Chen', '555-0104', 'emily@nailspa.com', 'Manager', 0.0),
        ]
        
        for member in default_staff:
            self.add_staff(*member)
