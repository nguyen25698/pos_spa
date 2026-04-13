import sqlite3
from datetime import datetime

class Appointment:
    """Model for appointment management."""
    
    def __init__(self, db_path='nail_spa.db'):
        self.db_path = db_path
        self.create_table()
    
    def create_table(self):
        """Create the appointments table if it doesn't exist."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS appointments (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                customer_id INTEGER NOT NULL,
                staff_id INTEGER,
                service_id INTEGER NOT NULL,
                appointment_date DATE NOT NULL,
                appointment_time TIME NOT NULL,
                status TEXT DEFAULT 'scheduled',
                notes TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (customer_id) REFERENCES customers(id),
                FOREIGN KEY (staff_id) REFERENCES staff(id),
                FOREIGN KEY (service_id) REFERENCES services(id)
            )
        ''')
        
        conn.commit()
        conn.close()
    
    def book_appointment(self, customer_id, service_id, appointment_date, 
                        appointment_time, staff_id=None, notes=''):
        """Book a new appointment."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            INSERT INTO appointments (customer_id, service_id, appointment_date, 
                                     appointment_time, staff_id, notes)
            VALUES (?, ?, ?, ?, ?, ?)
        ''', (customer_id, service_id, appointment_date, appointment_time, staff_id, notes))
        
        appointment_id = cursor.lastrowid
        conn.commit()
        conn.close()
        
        return appointment_id
    
    def get_appointment_by_id(self, appointment_id):
        """Get an appointment by ID with related information."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            SELECT a.*, c.first_name, c.last_name, c.phone,
                   s.name as service_name, s.price, s.duration_minutes,
                   st.first_name as staff_first_name, st.last_name as staff_last_name
            FROM appointments a
            JOIN customers c ON a.customer_id = c.id
            JOIN services s ON a.service_id = s.id
            LEFT JOIN staff st ON a.staff_id = st.id
            WHERE a.id = ?
        ''', (appointment_id,))
        
        appointment = cursor.fetchone()
        conn.close()
        
        return appointment
    
    def get_all_appointments(self, date=None, status=None):
        """Get all appointments with optional filters."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        query = '''
            SELECT a.*, c.first_name, c.last_name, c.phone,
                   s.name as service_name, s.price, s.duration_minutes,
                   st.first_name as staff_first_name, st.last_name as staff_last_name
            FROM appointments a
            JOIN customers c ON a.customer_id = c.id
            JOIN services s ON a.service_id = s.id
            LEFT JOIN staff st ON a.staff_id = st.id
            WHERE 1=1
        '''
        
        params = []
        
        if date:
            query += ' AND a.appointment_date = ?'
            params.append(date)
        
        if status:
            query += ' AND a.status = ?'
            params.append(status)
        
        query += ' ORDER BY a.appointment_date, a.appointment_time'
        
        cursor.execute(query, params)
        appointments = cursor.fetchall()
        
        conn.close()
        return appointments
    
    def update_appointment_status(self, appointment_id, status):
        """Update appointment status (scheduled, confirmed, completed, cancelled, no-show)."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('UPDATE appointments SET status = ? WHERE id = ?', (status, appointment_id))
        conn.commit()
        conn.close()
    
    def cancel_appointment(self, appointment_id):
        """Cancel an appointment."""
        self.update_appointment_status(appointment_id, 'cancelled')
    
    def complete_appointment(self, appointment_id):
        """Mark an appointment as completed."""
        self.update_appointment_status(appointment_id, 'completed')
    
    def get_staff_schedule(self, staff_id, date):
        """Get all appointments for a specific staff member on a date."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            SELECT a.*, c.first_name, c.last_name, c.phone,
                   s.name as service_name, s.duration_minutes
            FROM appointments a
            JOIN customers c ON a.customer_id = c.id
            JOIN services s ON a.service_id = s.id
            WHERE a.staff_id = ? AND a.appointment_date = ?
            ORDER BY a.appointment_time
        ''', (staff_id, date))
        
        appointments = cursor.fetchall()
        conn.close()
        
        return appointments
    
    def check_availability(self, staff_id, date, time, duration_minutes):
        """Check if a staff member is available at a given time slot."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        # This is a simplified availability check
        cursor.execute('''
            SELECT COUNT(*) FROM appointments
            WHERE staff_id = ? AND appointment_date = ? 
            AND status NOT IN ('cancelled', 'no-show')
        ''', (staff_id, date))
        
        count = cursor.fetchone()[0]
        conn.close()
        
        return count == 0
