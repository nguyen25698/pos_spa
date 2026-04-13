<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nail Spa POS')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        .navbar { background: #d4a5a5; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { color: white; font-size: 1.5rem; }
        .nav-links { display: flex; gap: 1rem; }
        .nav-links a { color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; transition: background 0.3s; }
        .nav-links a:hover { background: rgba(255,255,255,0.2); }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .card { background: white; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 1rem; transition: opacity 0.3s; }
        .btn-primary { background: #d4a5a5; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn:hover { opacity: 0.9; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: 600; }
        tr:hover { background: #f8f9fa; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #d4a5a5; }
        .alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .stat-card { background: linear-gradient(135deg, #d4a5a5, #c99595); color: white; padding: 1.5rem; border-radius: 8px; text-align: center; }
        .stat-card h3 { font-size: 2rem; margin-bottom: 0.5rem; }
        .stat-card p { opacity: 0.9; }
        .badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem; }
        .badge-success { background: #28a745; color: white; }
        .badge-warning { background: #ffc107; color: #333; }
        .badge-danger { background: #dc3545; color: white; }
        .badge-info { background: #17a2b8; color: white; }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>💅 Nail Spa POS</h1>
        <div class="nav-links">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('pos.index') }}">POS Terminal</a>
            <a href="{{ route('appointments.index') }}">Appointments</a>
            <a href="{{ route('customers.index') }}">Customers</a>
            <a href="{{ route('staff.index') }}">Staff</a>
            <a href="{{ route('services.index') }}">Services</a>
            <a href="{{ route('inventory.index') }}">Inventory</a>
            <a href="{{ route('reports.sales') }}">Reports</a>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
