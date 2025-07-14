<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Dark Dashboard</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+pfYnFfW3l/ux+WfM1Qx0sC2v5Jc/V/0z0t/0j0A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    

    <style>
        :root {
            --dark-bg: #1a1a2e;
            --darker-bg: #16213e;
            --primary-color: #0f3460;
            --accent-color: #e94560;
            --text-color: #f1f1f1;
            --sidebar-width: 250px;
            --sidebar-transition-duration: 0.3s;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            overflow-x: hidden; /* Prevent horizontal scroll during transitions */
        }

        /* Header Styles */
        .app-header {
            background-color: var(--darker-bg);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            height: 60px;
            position: fixed;
            top: 0;
            left: 0;
            /* width: 100%; */ /* This will be adjusted by JavaScript on small screens */
            right: 0;
            z-index: 1000;
            /* Initial width adjustment for larger screens */
            width: calc(100% - var(--sidebar-width));
            margin-left: var(--sidebar-width);
            transition: margin-left var(--sidebar-transition-duration) ease, width var(--sidebar-transition-duration) ease;
        }

        /* Sidebar Styles */
        .app-sidebar {
            background-color: var(--darker-bg);
            width: var(--sidebar-width);
            position: fixed;
            top: 60px;
            bottom: 0;
            left: 0;
            transition: transform var(--sidebar-transition-duration) ease;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1010; /* Ensure sidebar is above header on small screens when open */
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
        }

        .sidebar-menu li {
            padding: 10px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-menu li a {
            color: var(--text-color);
            text-decoration: none;
            display: block;
            transition: all 0.3s;
        }

        .sidebar-menu li a:hover {
            color: var(--accent-color);
            padding-left: 5px;
        }

        .sidebar-menu li.active {
            background-color: var(--primary-color);
            border-left: 3px solid var(--accent-color);
        }

        /* Main Content Styles */
        .app-main {
            margin-left: var(--sidebar-width);
            margin-top: 60px;
            padding: 20px;
            min-height: calc(100vh - 120px); /* Adjust for header and footer height */
            transition: margin-left var(--sidebar-transition-duration) ease;
        }

        /* Footer Styles */
        .app-footer {
            background-color: var(--darker-bg);
            height: 60px;
            margin-left: var(--sidebar-width);
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            transition: margin-left var(--sidebar-transition-duration) ease;
        }

        /* Card Styles */
        .dashboard-card {
            background-color: var(--darker-bg);
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            margin-bottom: 20px;
            padding: 20px; /* Add padding for internal content spacing */
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .card-icon {
            font-size: 2rem;
            color: var(--accent-color);
        }

        /* Alerts */
        .alert {
            border-radius: 8px;
            border-left: 4px solid;
            margin-top: 20px; /* Add margin for spacing from other elements */
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: rgba(25, 135, 84, 0.1);
            border-color: #198754;
            color: #198754;
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            border-color: #dc3545;
            color: #dc3545;
        }

        .alert-warning { /* Added for consistency with showAlert function */
            background-color: rgba(253, 126, 20, 0.1);
            border-color: #fd7e14;
            color: #fd7e14;
        }

        .alert ul {
            padding-left: 20px;
            margin-bottom: 0;
        }

        .alert ul li {
            margin-bottom: 5px;
        }

        /* Modal Styles */
        .modal-content {
            background-color: var(--darker-bg);
            color: var(--text-color);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-top-left-radius: 9px; /* Slightly less than content for inner curve */
            border-top-right-radius: 9px;
            border-bottom: none;
        }

        .modal-body {
            color: var(--text-color); /* Ensure text in body is readable */
        }

        .modal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-footer button {
            /* width: 100px; */ /* Removed fixed width, let Bootstrap handle button sizing */
        }

        .form-label {
            color: var(--text-color);
        }

        .form-control {
            background-color: var(--dark-bg);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-color);
        }

        .form-control:focus {
            background-color: var(--dark-bg);
            color: var(--text-color);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(233, 69, 96, 0.25); /* Accent color for focus */
        }

        .table {
            color: var(--text-color);
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05); /* Lighter stripe */
        }

        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.1); /* Hover effect */
        }

        .table-dark {
            --bs-table-bg: var(--primary-color);
            --bs-table-striped-bg: var(--primary-color);
            --bs-table-striped-color: var(--text-color);
            --bs-table-active-bg: var(--primary-color);
            --bs-table-active-color: var(--text-color);
            --bs-table-hover-bg: var(--primary-color);
            --bs-table-hover-color: var(--text-color);
            color: var(--text-color);
            border-color: var(--primary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-info {
            background-color: #0dcaf0;
            border-color: #0dcaf0;
        }
        .btn-info:hover {
            background-color: #0aa5cb;
            border-color: #0aa5cb;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #bb2d3b;
            border-color: #bb2d3b;
        }

        /* Layout Container Adjustments */
        /* This container is used for the main content within the app-main area */
        .layout-container {
            /* max-width is now handled by bootstrap's container class, and margin auto for centering */
            /* padding is handled by app-main */
            padding: 0 15px; /* Add horizontal padding for smaller screens */
            max-width: 1200px; /* Max width for content, adjust as needed */
            margin-left: auto;
            margin-right: auto;
        }

        /* --- Responsive Adjustments --- */

        /* Styles for when the sidebar is closed */
        body.sidebar-closed .app-sidebar {
            transform: translateX(-100%); /* Slide out to the left */
        }

        body.sidebar-closed .app-main,
        body.sidebar-closed .app-footer {
            margin-left: 0; /* Take full width */
        }

        body.sidebar-closed .app-header {
            margin-left: 0;
            width: 100%;
        }


        /* Small devices (portrait tablets and large phones, 576px and up) */
        @media (max-width: 991.98px) { /* Bootstrap 'lg' breakpoint (col-lg-* affects from 992px) */
            /* Hide sidebar by default on smaller screens */
            .app-sidebar {
                transform: translateX(-100%);
            }

            /* Main content and footer take full width */
            .app-main, .app-footer {
                margin-left: 0;
            }

            /* Header also takes full width */
            .app-header {
                width: 100%;
                margin-left: 0;
            }

            /* Show toggle button */
            .sidebar-toggle {
                display: block !important;
            }

            /* When sidebar is open on small screens */
            body.sidebar-open .app-sidebar {
                transform: translateX(0);
            }

            body.sidebar-open .app-main,
            body.sidebar-open .app-footer {
                /* No margin left needed, sidebar floats over content */
            }

             /* Overlay for when sidebar is open on small screens */
            body.sidebar-open::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1005; /* Between sidebar and content */
                cursor: pointer;
            }
        }

        /* Large devices (desktops, 992px and up) */
        @media (min-width: 992px) {
            /* Ensure sidebar is visible and toggle button is hidden on large screens */
            .app-sidebar {
                transform: translateX(0);
            }

            .app-main, .app-footer {
                margin-left: var(--sidebar-width);
            }

            .app-header {
                width: calc(100% - var(--sidebar-width));
                margin-left: var(--sidebar-width);
            }

            .sidebar-toggle {
                display: none !important;
            }
        }

        /* Further adjustments for extra small devices, if needed */
        @media (max-width: 575.98px) { /* Bootstrap 'sm' breakpoint */
            .table-responsive {
                border: 1px solid rgba(255, 255, 255, 0.1); /* Add a border for better visual separation */
                border-radius: 8px;
            }
            .dashboard-card {
                padding: 15px; /* Reduce padding on very small screens */
            }
            .modal-dialog {
                margin: 1rem; /* Add some margin to modals on small screens */
            }
            .modal-content {
                width: auto;
            }
        }
    </style>
</head>
<body>
    @include('admin.layout.header')

    <div class="d-flex">
        @include('admin.layout.sidebar')
        <main class="app-main flex-grow-1">
            <div class="layout-container">
                @yield('content')
            </div>
        </main>
    </div>


    @include('admin.layout.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const body = document.body;
            const appMain = document.querySelector('.app-main');
            const appFooter = document.querySelector('.app-footer');
            const appHeader = document.querySelector('.app-header');

            // Function to toggle sidebar state
            function toggleSidebar() {
                // Toggle the 'sidebar-open' class on the body.
                // On larger screens (>= 992px), 'sidebar-closed' will hide it
                // On smaller screens (< 992px), 'sidebar-open' will show it
                if (window.innerWidth < 992) {
                    body.classList.toggle('sidebar-open');
                    // Add/remove overlay for smaller screens
                    if (body.classList.contains('sidebar-open')) {
                        const overlay = document.createElement('div');
                        overlay.id = 'sidebar-overlay';
                        overlay.style.cssText = `
                            position: fixed;
                            top: 0;
                            left: 0;
                            right: 0;
                            bottom: 0;
                            background-color: rgba(0, 0, 0, 0.5);
                            z-index: 1005;
                            cursor: pointer;
                        `;
                        document.body.appendChild(overlay);
                        overlay.addEventListener('click', toggleSidebar); // Close sidebar when overlay clicked
                    } else {
                        const overlay = document.getElementById('sidebar-overlay');
                        if (overlay) overlay.remove();
                    }
                } else {
                    body.classList.toggle('sidebar-closed');
                }
            }

            // Event listener for sidebar toggle button
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }

            // Close sidebar when clicking outside on small screens (if overlay is not used)
            // Or handle clicks on the overlay if it exists
            // Since we're adding an overlay, this specific event listener might not be needed
            // if the overlay handles closing.
            // document.addEventListener('click', function(event) {
            //     const sidebar = document.querySelector('.app-sidebar');
            //     if (window.innerWidth < 992 && body.classList.contains('sidebar-open') &&
            //         !sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
            //         toggleSidebar(); // Close sidebar if click outside sidebar and toggle button
            //     }
            // });


            // Handle window resize to adjust sidebar state
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    const overlay = document.getElementById('sidebar-overlay');
                    if (window.innerWidth >= 992) {
                        // On large screens, ensure sidebar is open and no overlay
                        body.classList.remove('sidebar-closed');
                        body.classList.remove('sidebar-open');
                        if (overlay) overlay.remove();
                    } else {
                        // On small screens, ensure sidebar is initially closed unless toggled open
                        // And remove overlay if window resized to smaller and sidebar isn't explicitly open
                        if (!body.classList.contains('sidebar-open')) {
                             body.classList.add('sidebar-closed'); // Ensure it's closed by default
                             if (overlay) overlay.remove(); // Remove overlay if it exists when not sidebar-open
                        }
                    }
                }, 100); // Debounce resize event
            });

            // Initial setup based on screen size
            if (window.innerWidth < 992) {
                body.classList.add('sidebar-closed'); // Hide sidebar by default on small screens
            } else {
                body.classList.remove('sidebar-closed'); // Show sidebar by default on large screens
            }

            // Add active class to clicked menu item (existing logic)
            document.querySelectorAll('.sidebar-menu li').forEach(item => {
                item.addEventListener('click', function() {
                    document.querySelectorAll('.sidebar-menu li').forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
    // Handle AJAX navigation
    $(document).on('click', '.ajax-link', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        
        // Show loading indicator if needed
        $('#main-content-container').html('<div class="text-center py-5">Loading...</div>');
        
        // Make AJAX request
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                // Update main content
                $('#main-content-container').html(response);
                
                // Update URL in browser without reload
                history.pushState(null, null, url);
                
                // Update active class in sidebar
                $('.sidebar-menu li').removeClass('active');
                $(e.target).closest('li').addClass('active');
            },
            error: function(xhr) {
                $('#main-content-container').html('<div class="alert alert-danger">Error loading page</div>');
            }
        });
    });
    
    // Handle browser back/forward buttons
    $(window).on('popstate', function() {
        $.ajax({
            url: location.pathname,
            type: 'GET',
            success: function(response) {
                $('#main-content-container').html(response);
                updateActiveNavItem();
            }
        });
    });
    
    // Function to update active nav item based on current URL
    function updateActiveNavItem() {
        $('.sidebar-menu li').removeClass('active');
        $('.sidebar-menu a[href="' + location.pathname + '"]').closest('li').addClass('active');
    }
});
    </script>
    @yield('scripts') {{-- This will be for page-specific scripts --}}
</body>
</html>
