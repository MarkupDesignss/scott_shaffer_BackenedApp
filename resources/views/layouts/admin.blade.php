<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scott Shaffer Admin - @yield('title')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
        <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons (optional but recommended for icons) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Font Awesome (for the icons used in the design) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #818cf8;
            --primary-dark: #3730a3;
            --secondary: #10b981;
            --accent: #f59e0b;
            --light-bg: #f8fafc;
            --dark-bg: #1e293b;
            --card-bg: #ffffff;
            --sidebar-width: 250px;
            --header-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--light-bg);
            color: #334155;
            overflow-x: hidden;
        }

        /* Header */
        .main-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            height: var(--header-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.2);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo {
            width: 30px;
            height: 30px;
            border-radius: 10px;
            background: linear-gradient(45deg, var(--primary-light), #ffffff);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-dark);
            font-weight: 500;
            font-size: 1.1rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .brand-text {
            font-size: 1.4rem;
            font-weight: 700;
            color: white;
            letter-spacing: -0.5px;
        }

        /* Menu Toggle Button */
        .menu-toggle {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 101;
        }

        .menu-toggle:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.05);
        }

        .menu-toggle span {
            display: block;
            width: 22px;
            height: 2px;
            background: white;
            margin: 3px 0;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .menu-toggle.active span:nth-child(1) {
            transform: translateY(8px) rotate(45deg);
        }

        .menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .menu-toggle.active span:nth-child(3) {
            transform: translateY(-8px) rotate(-45deg);
        }

        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--secondary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .user-role {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.8rem;
        }

        /* Sidebar */
        .sidebar {
            background: var(--card-bg);
            width: var(--sidebar-width);
            position: fixed;
            top: var(--header-height);
            left: 0;
            bottom: 0;
            z-index: 90;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.35s cubic-bezier(.2,.9,.3,1), width 0.35s cubic-bezier(.2,.9,.3,1);
            padding: 1.5rem 0;
            overflow-y: auto;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            padding: 0 1rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.4rem 0.7rem;
            border-radius: 10px;
            color: #64748b;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-item:hover {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.1), transparent);
            color: var(--primary);
            transform: translateX(5px);
        }

        .nav-item.active {
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .nav-icon {
            margin-right: 0.75rem;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .nav-label {
            font-size: 0.95rem;
        }

        .nav-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 1rem 0;
        }

        .nav-section {
            font-size: 0.75rem;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0 1rem;
            margin: 1.5rem 0 0.75rem 0;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
            transition: margin-left 0.35s cubic-bezier(.2,.9,.3,1), width 0.35s cubic-bezier(.2,.9,.3,1);
            width: calc(100% - var(--sidebar-width));
        }

        .content-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            border: 1px solid #f1f5f9;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-title-icon {
            background: linear-gradient(45deg, var(--primary-light), var(--primary));
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #f1f5f9;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-dark);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        .stat-change {
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 0.25rem;
        }

        .stat-change.positive {
            color: var(--secondary);
        }

        .stat-change.negative {
            color: #ef4444;
        }

        /* Buttons */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary), var(--primary-light));
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(79, 70, 229, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(45deg, var(--secondary), #34d399);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        /* Mobile Responsiveness */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }

            .brand-text {
                display: none;
            }
        }

        /* Desktop collapse behaviour: when `.active` on sidebar, hide it and expand main content */
        @media (min-width: 992px) {
            .sidebar.active {
                transform: translateX(-100%);
                width: 0;
            }

            .main-content.collapsed {
                margin-left: 0;
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1.5rem 1rem;
            }

            .content-card {
                padding: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .header-left {
                width: 100%;
                justify-content: space-between;
            }
        }

        /* Desktop: Sidebar hidden by default on toggle */
        @media (min-width: 993px) {
            .sidebar.hidden {
                transform: translateX(-100%);
            }

            .main-content.full-width {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Overlay for mobile sidebar */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 89;
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="header-left">
            <button class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <div class="logo-container">
                <div class="logo">SS</div>
                <div class="brand-text">Scott Shaffer Admin</div>
            </div>
        </div>

        <div class="user-info">
            <div class="user-details">
                <div class="user-name">{{ Auth::guard('admin')->user()->name ?? 'Admin User' }}</div>
                <div class="user-role">Administrator</div>
            </div>
            <div class="user-avatar">
                {{ substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1) }}
            </div>
        </div>
    </header>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-nav">
            <div class="nav-section">Main</div>

            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="nav-icon bi bi-speedometer2"></i>
                <span class="nav-label">Dashboard</span>
            </a>

            <a href="{{ route('admin.user.index') }}" class="nav-item {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-person-circle"></i>
                <span class="nav-label">Users</span>
            </a>

            <a href="{{ route('admin.interest.index') }}"
            class="nav-item {{ request()->routeIs('admin.interest.*') ? 'active' : '' }}">
               <i class="nav-icon bi bi-heart-fill"></i>
                <span class="nav-label">Interest</span>
            </a>
            <a href="{{ route('admin.catalog-categories.index') }}"
            class="nav-item {{ request()->routeIs('admin.catalog-categories.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-folder2-open"></i>
                <span class="nav-label">Catalog Categories</span>
            </a>

            <a href="{{ route('admin.catalog-items.index') }}"
            class="nav-item {{ request()->routeIs('admin.catalog-items.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-box-seam"></i>
                <span class="nav-label">Catalog Items</span>
            </a>





            <div class="nav-divider"></div>
            <form method="POST" action="{{ route('admin.logout') }}" class="nav-item">
                @csrf
                <button type="submit" class="btn btn-link nav-link p-0 m-0">
                    <i class="nav-icon bi bi-box-arrow-right"></i>
                    <span class="nav-label">Logout</span>
                </button>
            </form>

        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <div class="content-card fade-in">



            <!-- Example Content Area -->
            <div class="fade-in" style="animation-delay: 0.5s">
                @yield('content')
            </div>
        </div>
    </main>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        // Toggle sidebar on hamburger click
        document.getElementById('menuToggle').addEventListener('click', function() {
            this.classList.toggle('active');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');

            // Check if we're on mobile or desktop
            if (window.innerWidth < 993) {
                // Mobile behavior
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');

                // Prevent body scroll when sidebar is open
                if (sidebar.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = 'auto';
                }
            } else {
                // Desktop behavior - toggle sidebar visibility
                sidebar.classList.toggle('hidden');
                mainContent.classList.toggle('full-width');
            }
        });

        // Close sidebar when clicking on overlay (mobile only)
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            if (window.innerWidth < 993) {
                document.getElementById('menuToggle').classList.remove('active');
                document.getElementById('sidebar').classList.remove('active');
                this.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });

        // Close sidebar when clicking a nav item on mobile
        // document.querySelectorAll('.nav-item').forEach(item => {
        //     item.addEventListener('click', function(e) {
        //         // Prevent default only for demo (remove in production)
        //         e.preventDefault();

        //         // Update active state
        //         document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
        //         this.classList.add('active');

        //         // Close sidebar on mobile after clicking
        //         if (window.innerWidth < 993) {
        //             document.getElementById('menuToggle').classList.remove('active');
        //             document.getElementById('sidebar').classList.remove('active');
        //             document.getElementById('sidebarOverlay').classList.remove('active');
        //             document.body.style.overflow = 'auto';
        //         }
        //     });
        // });

        // Close sidebar when window is resized to desktop
        window.addEventListener('resize', function() {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');

            if (window.innerWidth >= 993) {
                // Desktop - ensure overlay is hidden
                overlay.classList.remove('active');
                document.body.style.overflow = 'auto';

                // If sidebar was open on mobile, keep it visible on desktop
                if (sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    menuToggle.classList.remove('active');
                }
            } else {
                // Mobile - ensure proper state
                if (sidebar.classList.contains('hidden')) {
                    sidebar.classList.remove('hidden');
                    mainContent.classList.remove('full-width');
                }
            }
        });

        // Add animation to elements on scroll
        const observerOptions = {
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        }, observerOptions);

        // Observe elements you want to animate
        document.querySelectorAll('.stat-card, .content-card').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>
