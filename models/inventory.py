import sqlite3

class Inventory:
    """Model for inventory management (polishes, supplies, retail products)."""
    
    def __init__(self, db_path='nail_spa.db'):
        self.db_path = db_path
        self.create_table()
    
    def create_table(self):
        """Create the inventory table if it doesn't exist."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS inventory (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                category TEXT NOT NULL,
                quantity INTEGER DEFAULT 0,
                unit TEXT,
                cost_price REAL,
                retail_price REAL,
                reorder_level INTEGER DEFAULT 5,
                supplier TEXT,
                notes TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ''')
        
        conn.commit()
        conn.close()
    
    def add_item(self, name, category, quantity=0, unit='', cost_price=0.0, 
                 retail_price=0.0, reorder_level=5, supplier='', notes=''):
        """Add a new inventory item."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            INSERT INTO inventory (name, category, quantity, unit, cost_price, 
                                  retail_price, reorder_level, supplier, notes)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ''', (name, category, quantity, unit, cost_price, retail_price, 
              reorder_level, supplier, notes))
        
        item_id = cursor.lastrowid
        conn.commit()
        conn.close()
        
        return item_id
    
    def get_item_by_id(self, item_id):
        """Get an inventory item by ID."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('SELECT * FROM inventory WHERE id = ?', (item_id,))
        item = cursor.fetchone()
        
        conn.close()
        return item
    
    def get_all_items(self, category=None):
        """Retrieve all inventory items with optional category filter."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        if category:
            cursor.execute('SELECT * FROM inventory WHERE category = ? ORDER BY name', (category,))
        else:
            cursor.execute('SELECT * FROM inventory ORDER BY category, name')
        
        items = cursor.fetchall()
        conn.close()
        
        return items
    
    def update_quantity(self, item_id, quantity_change):
        """Update item quantity (positive for addition, negative for usage/sale)."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            UPDATE inventory 
            SET quantity = quantity + ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ''', (quantity_change, item_id))
        
        conn.commit()
        conn.close()
    
    def update_item(self, item_id, name=None, category=None, cost_price=None, 
                   retail_price=None, reorder_level=None, supplier=None, notes=None):
        """Update item information."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        updates = []
        values = []
        
        if name is not None:
            updates.append('name = ?')
            values.append(name)
        if category is not None:
            updates.append('category = ?')
            values.append(category)
        if cost_price is not None:
            updates.append('cost_price = ?')
            values.append(cost_price)
        if retail_price is not None:
            updates.append('retail_price = ?')
            values.append(retail_price)
        if reorder_level is not None:
            updates.append('reorder_level = ?')
            values.append(reorder_level)
        if supplier is not None:
            updates.append('supplier = ?')
            values.append(supplier)
        if notes is not None:
            updates.append('notes = ?')
            values.append(notes)
        
        updates.append('updated_at = CURRENT_TIMESTAMP')
        
        if updates:
            values.append(item_id)
            query = f"UPDATE inventory SET {', '.join(updates)} WHERE id = ?"
            cursor.execute(query, values)
            conn.commit()
        
        conn.close()
    
    def get_low_stock_items(self):
        """Get items that are at or below reorder level."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('''
            SELECT * FROM inventory 
            WHERE quantity <= reorder_level 
            ORDER BY quantity ASC
        ''')
        
        items = cursor.fetchall()
        conn.close()
        
        return items
    
    def delete_item(self, item_id):
        """Remove an item from inventory."""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        cursor.execute('DELETE FROM inventory WHERE id = ?', (item_id,))
        conn.commit()
        conn.close()
    
    def initialize_default_inventory(self):
        """Initialize with default nail spa inventory items."""
        default_items = [
            # Nail Polishes
            ('OPI Lincoln Park After Dark', 'Nail Polish - Regular', 12, 'bottles', 8.50, 15.99, 6, 'Beauty Supply Co', 'Classic black'),
            ('Essie Ballet Slippers', 'Nail Polish - Regular', 8, 'bottles', 7.00, 12.99, 6, 'Beauty Supply Co', 'Pale pink'),
            ('Gelish How Great Thistles', 'Nail Polish - Gel', 10, 'bottles', 12.00, 24.99, 5, 'Gel Supply Inc', 'Purple glitter'),
            
            # Supplies
            ('Cotton Pads', 'Supplies', 50, 'packs', 2.50, 5.99, 10, 'Bulk Supplies', 'Round cotton pads'),
            ('Nail Files', 'Supplies', 100, 'pieces', 0.50, 1.99, 20, 'Bulk Supplies', 'Disposable files'),
            ('Orange Wood Sticks', 'Supplies', 200, 'pieces', 0.10, 0.50, 50, 'Bulk Supplies', 'Cuticle pusher'),
            ('Acetone', 'Supplies', 6, 'bottles', 4.00, 9.99, 8, 'Chemical Supply', 'Pure acetone'),
            
            # Retail Products
            ('Cuticle Oil Pen', 'Retail', 15, 'pieces', 3.00, 12.99, 10, 'Beauty Supply Co', 'Vitamin E enriched'),
            ('Hand Cream', 'Retail', 20, 'tubes', 5.00, 18.99, 8, 'Spa Products', 'Lavender scented'),
            ('Foot Scrub', 'Retail', 12, 'jars', 6.00, 22.99, 6, 'Spa Products', 'Peppermint'),
        ]
        
        for item in default_items:
            self.add_item(*item)
