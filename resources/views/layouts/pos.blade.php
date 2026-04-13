<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nail Spa POS')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif; 
            background: #f5f7fa;
        }

        /* Top Header */
        .top-header {
            background: #fff;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1976d2;
        }

        .header-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.25rem;
            color: #666;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #1976d2;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        /* Layout */
        .layout {
            display: flex;
            height: calc(100vh - 60px);
        }

        /* Sidebar */
        .sidebar {
            width: 80px;
            background: #fff;
            border-right: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            padding: 1rem 0;
        }

        .sidebar-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.75rem 0;
            cursor: pointer;
            color: #666;
            text-decoration: none;
            transition: all 0.2s;
        }

        .sidebar-item:hover {
            background: #f5f7fa;
        }

        .sidebar-item.active {
            background: #e3f2fd;
            color: #1976d2;
        }

        .sidebar-item svg {
            width: 24px;
            height: 24px;
            margin-bottom: 0.25rem;
        }

        .sidebar-item span {
            font-size: 0.7rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            overflow: auto;
        }

        /* POS Container */
        .pos-container {
            display: grid;
            grid-template-columns: 1fr 320px;
            height: calc(100vh - 60px);
        }

        .main-area {
            padding: 1rem;
            overflow-y: auto;
        }

        /* Staff Buttons */
        .staff-section {
            margin-bottom: 1rem;
        }

        .staff-grid {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .staff-btn {
            padding: 1rem 1.5rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            background: #fff;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            min-width: 120px;
            text-align: center;
            transition: all 0.2s;
        }

        .staff-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .staff-btn.active {
            border-color: transparent;
        }

        .staff-btn:nth-child(5n+1) { background: #fff; }
        .staff-btn:nth-child(5n+2) { background: #1976d2; color: #fff; }
        .staff-btn:nth-child(5n+3) { background: #f44336; color: #fff; }
        .staff-btn:nth-child(5n+4) { background: #4caf50; color: #fff; }
        .staff-btn:nth-child(5n+5) { background: #fff; }

        .staff-btn:nth-child(5n+2).active,
        .staff-btn:nth-child(5n+3).active,
        .staff-btn:nth-child(5n+4).active {
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        /* Services Area */
        .services-area {
            background: #fff;
            border-radius: 12px;
            padding: 1rem;
            min-height: 300px;
        }

        /* Right Panel */
        .right-panel {
            background: #fff;
            border-left: 1px solid #ddd;
            display: flex;
            flex-direction: column;
        }

        .panel-tabs {
            display: flex;
            border-bottom: 2px solid #eee;
        }

        .panel-tab {
            flex: 1;
            padding: 0.75rem;
            text-align: center;
            cursor: pointer;
            font-size: 0.85rem;
            border: none;
            background: none;
            color: #666;
        }

        .panel-tab.active {
            color: #1976d2;
            border-bottom: 2px solid #1976d2;
            font-weight: 600;
        }

        .panel-content {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
        }

        /* Bottom Bar */
        .bottom-bar {
            background: linear-gradient(135deg, #fce4ec, #f8bbd0);
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .bottom-bar::before {
            content: ": "✿";
            position: absolute;
            right: 2rem;
            bottom: -10px;
            font-size: 8rem;
            color: rgba(255,255,255,0.3);
            pointer-events: none;
        }

        .bottom-left {
            display: flex;
            gap: 1rem;
        }

        .bottom-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid #1976d2;
            background: #fff;
            color: #1976d2;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .payment-btn {
            padding: 0.75rem 3rem;
            background: #4caf50;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            z-index: 1;
        }

        .payment-btn:hover {
            background: #43a047;
        }

        /* Form Styles for POS */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }

        .form-group select,
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        /* Service Selection Grid */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .service-card {
            background: #f8f9fa;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
            cursor: pointer;
            text-align: center;
            transition: all 0.2s;
        }

        .service-card:hover {
            border-color: #1976d2;
            background: #e3f2fd;
        }

        .service-card.selected {
            border-color: #1976d2;
            background: #1976d2;
            color: #fff;
        }

        .service-name {
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .service-price {
            font-weight: bold;
            color: #1976d2;
        }

        .service-card.selected .service-price {
            color: #fff;
        }

        /* Alert styles */
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Order Summary */
        .order-summary {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-top: 2px solid #ddd;
            margin-top: 0.5rem;
            font-size: 1.25rem;
            font-weight: bold;
        }

        /* Cart Items */
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: #fff;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            border: 1px solid #eee;
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-name {
            font-weight: 500;
        }

        .cart-item-price {
            font-size: 0.85rem;
            color: #666;
        }

        .cart-item-qty {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .qty-btn {
            width: 28px;
            height: 28px;
            border: 1px solid #ddd;
            background: #fff;
            border-radius: 4px;
            cursor: pointer;
        }

        .remove-btn {
            background: #f44336;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 0.25rem 0.5rem;
            cursor: pointer;
            margin-left: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="top-header">
        <div class="logo">SimplePOS</div>
        <div class="header-right">
            <span style="font-size: 0.9rem; color: #666;">{{ Auth::user()->name ?? 'User' }}</span>
            <button class="notification-btn">🔔</button>
            <div class="user-avatar" style="position: relative;">
                👤
            </div>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; cursor: pointer; color: #666; font-size: 0.85rem;">Logout</button>
            </form>
        </div>
    </div>

    <div class="layout">
        <nav class="sidebar">
            <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <span>Home</span>
            </a>
            <a href="{{ route('appointments.index') }}" class="sidebar-item {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span>Calendar</span>
            </a>
            <a href="{{ route('customers.index') }}" class="sidebar-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="8" r="5"></circle>
                    <path d="M20 21a8 8 0 1 0-16 0"></path>
                </svg>
                <span>Customer</span>
            </a>
            <a href="{{ route('pos.index') }}" class="sidebar-item {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                    <line x1="2" y1="10" x2="22" y2="10"></line>
                </svg>
                <span>Charge</span>
            </a>
            <a href="{{ route('reports.sales') }}" class="sidebar-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M12 1v6M12 17v6M4.22 4.22l4.24 4.24M15.54 15.54l4.24 4.24M1 12h6M17 12h6M4.22 19.78l4.24-4.24M15.54 8.46l4.24-4.24"></path>
                </svg>
                <span>Reports</span>
            </a>
            <a href="#" class="sidebar-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
                <span>Settings</span>
            </a>
        </nav>

        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
