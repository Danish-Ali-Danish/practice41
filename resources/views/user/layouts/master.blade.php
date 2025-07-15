<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ecommerce')</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />


    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <style>
        #searchResults {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1050;
            background-color: #fff;
            border: 1px solid #ccc;
            max-height: 300px;
            overflow-y: auto;
        }

        #searchResults a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-bottom: 1px solid #eee;
            text-decoration: none;
            color: #000;
        }

        #searchResults a:hover {
            background-color: #f8f9fa;
        }

        #searchResults img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-4 position-relative">
        <a class="navbar-brand fw-bold text-primary" href="{{ url('/home') }}">ShopNow</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                <li class="nav-item me-3 position-relative d-none d-lg-block">
                @if (!Request::is('home'))
                    <x-search-box />
                @endif

                </li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/allproducts') }}">Products</a></li>
                <li class="nav-item position-relative">
                    <a class="nav-link" href="{{ url('/cart') }}">
                        <i class="fas fa-shopping-cart"></i> Cart
                        <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.75rem;">
                            0
                        </span>
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/checkout') }}">Checkout</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/orders') }}">Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/wishlist') }}"><i class="fas fa-heart text-danger me-1"></i>Wishlist</a></li>
                <li class="nav-item">
                    <button class="btn btn-sm btn-outline-secondary theme-toggle" id="toggleTheme" aria-label="Toggle theme">
                        <i class="fas fa-adjust me-1"></i> Toggle
                    </button>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Content Section -->
    <main class="container py-4">
        @yield('content')
    </main>
    <x-image-preview-modal />
    <!-- Footer -->
    <footer class="footer">
        <p class="mb-0">&copy; {{ date('Y') }} ShopNow. All rights reserved.</p>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('toggleTheme');
        const htmlEl = document.documentElement;
        const icon = themeToggle.querySelector('i');

        function setTheme(theme) {
            htmlEl.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            icon.classList.replace('fa-sun', 'fa-moon');
            if (theme === 'dark') {
                icon.classList.replace('fa-moon', 'fa-sun');
            }
        }

        const savedTheme = localStorage.getItem('theme') || 'light';
        setTheme(savedTheme);

        themeToggle.addEventListener('click', function () {
            const currentTheme = htmlEl.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
        });
    });
    </script>
    <script>
    $(document).on('click', '.previewable-image', function () {
        let src = $(this).attr('src');
        $('#previewImage').attr('src', src);
        $('#imagePreviewModal').modal('show');
    });
</script>

    @stack('scripts')

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('navbarSearchInput');
    const suggestionBox = document.getElementById('navbarSearchResults');

    if (!searchInput || !suggestionBox) return;

    let delay;
    searchInput.addEventListener('keyup', function () {
        const query = this.value.trim();

        if (query.length < 2) {
            suggestionBox.style.display = 'none';
            return;
        }

        clearTimeout(delay);
        delay = setTimeout(() => {
            fetch(`/search/suggestions?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    let html = '';

                    data.categories?.forEach(item => {
                        html += `<div class="p-2 border-bottom"><a href="${item.link}"><strong>📁 ${item.name}</strong></a></div>`;
                    });

                    data.brands?.forEach(item => {
                        html += `<div class="p-2 border-bottom"><a href="${item.link}"><strong>🏷️ ${item.name}</strong></a></div>`;
                    });

                    data.products?.forEach(item => {
                        html += `
                            <div class="p-2 border-bottom d-flex align-items-center">
                                <img src="${item.image}" width="40" height="40" class="me-2" style="object-fit:cover;">
                                <div>
                                    <a href="${item.link}" class="text-dark fw-bold">${item.name}</a>
                                    <div class="text-muted small">${item.description ?? ''}</div>
                                    <div class="text-danger fw-bold">Rs ${item.price}${item.discount ? ` <small class="text-muted text-decoration-line-through">Rs ${item.compare_price}</small> <span class="badge bg-success ms-1">${item.discount}% off</span>` : ''}</div>
                                </div>
                            </div>`;
                    });

                    suggestionBox.innerHTML = html || '<div class="p-2">No results found.</div>';
                    suggestionBox.style.display = 'block';
                })
                .catch(() => {
                    suggestionBox.innerHTML = '<div class="p-2 text-danger">Error loading results</div>';
                    suggestionBox.style.display = 'block';
                });
        }, 300);
    });

    document.addEventListener('click', (e) => {
        if (!suggestionBox.contains(e.target) && e.target !== searchInput) {
            suggestionBox.style.display = 'none';
        }
    });
});
</script>

</body>
</html>