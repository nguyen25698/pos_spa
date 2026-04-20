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

        /* Calendar Header */
        .calendar-header {
            background: #fff;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
        }

        .today-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            background: #fff;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .date-nav {
            display: flex;
            align-items: center;
            margin-left: 1rem;
            gap: 0.5rem;
        }

        .date-nav-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.25rem;
            color: #666;
        }

        .current-date {
            font-size: 1rem;
            color: #1976d2;
            font-weight: 500;
            cursor: pointer;
        }

        .settings-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.25rem;
            color: #666;
        }

        .view-toggle {
            margin-left: auto;
            display: flex;
            gap: 0.5rem;
        }

        .view-btn {
            padding: 0.5rem 1.25rem;
            border: 1px solid #ddd;
            background: #fff;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .view-btn:first-child {
            border-radius: 6px 0 0 6px;
        }

        .view-btn:last-child {
            border-radius: 0 6px 6px 0;
        }

        .view-btn.active {
            background: #1976d2;
            color: #fff;
            border-color: #1976d2;
        }

        /* Alert styles */
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem;
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
