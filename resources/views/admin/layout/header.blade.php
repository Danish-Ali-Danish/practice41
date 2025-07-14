<header class="app-header d-flex align-items-center px-3">
        {{-- The sidebar-toggle button is now visible on medium and smaller screens, hidden on large --}}
        <button class="btn btn-link text-white sidebar-toggle d-lg-none me-3">
            <i class="bi bi-list" style="font-size: 1.5rem;"></i>
        </button>
        <h4 class="mb-0">Admin Dashboard</h4>
        <div class="ms-auto d-flex align-items-center">
            <div class="dropdown">
                <button class="btn btn-link text-white dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-2"></i>Admin User
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('logout')}}"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </header>
