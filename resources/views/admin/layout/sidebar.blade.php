<aside class="app-sidebar">
    <div class="sidebar-content p-3">
        <ul class="sidebar-menu">

            <!-- Dashboard -->
            <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            </li>

            <!-- Category Management -->
            <li class="{{ request()->routeIs('categories.index') ? 'active' : '' }}">
                <a href="{{ route('categories.index') }}">
                    <i class="bi bi-folder me-2"></i>Categories
                </a>
            </li>

            <!-- Brand Management -->
            <li class="{{ request()->routeIs('brands.index') ? 'active' : '' }}">
                <a href="{{ route('brands.index') }}">
                    <i class="bi bi-tags me-2"></i>Brands
                </a>
            </li>

            <!-- Product Management -->
            <li class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
                <a href="{{ route('products.index') }}">
                    <i class="bi bi-box-seam me-2"></i>Products
                </a>
            </li>

            <!-- Feature Management -->
            <li class="{{ request()->routeIs('features.index') ? 'active' : '' }}">
                <a href="{{ route('features.index') }}">
                    <i class="bi bi-stars me-2"></i>Features
                </a>
            </li>

            <!-- Promo Management -->
            <li class="{{ request()->routeIs('promos.index') ? 'active' : '' }}">
                <a href="{{ route('promos.index') }}">
                    <i class="bi bi-megaphone me-2"></i>Promos
                </a>
            </li>

            <!-- Testimonial Management -->
            <li class="{{ request()->routeIs('testimonials.index') ? 'active' : '' }}">
                <a href="{{ route('testimonials.index') }}">
                    <i class="bi bi-chat-left-quote me-2"></i>Testimonials
                </a>
            </li>

            <!-- Blog Post Management -->
            <li class="{{ request()->routeIs('blog-posts.index') ? 'active' : '' }}">
                <a href="{{ route('blog-posts.index') }}">
                    <i class="bi bi-journal-text me-2"></i>Blog Posts
                </a>
            </li>

            <!-- Logout -->
            <li>
                <a href="{{ route('logout') }}">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                </a>
            </li>

        </ul>
    </div>
</aside>
