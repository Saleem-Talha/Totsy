<!-- Navbar -->
<head>
    <?php include 'header-links.php'; ?>
    <style>
        

/*navbar*/

.form-control:focus {
    color: var(--super-color);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}
.navbar-nav .nav-item .nav-link:hover {
    background: var(--super-color);
    -webkit-background-clip: text;
    background-clip: text; 
    -webkit-text-fill-color: transparent;
}
.nav-icon {
    opacity: 0;
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.nav-link:hover .nav-icon {
    opacity: 1;
    transform: translateX(5px);
}
.nav-link:hover .nav-text {
    margin-right: 0.25rem;
}
/* Styles for transparent and scrollable navbar */
.navbar {
    transition: background-color 0.3s ease;
}
.navbar-scrolled, .navbar-expanded {
    background-color: #fff !important;
    box-shadow: 0 2px 4px rgba(0,0,0,.1);
}
.navbar-brand, .navbar-nav .nav-link {
    color: #fff;
    transition: color 0.3s ease;
}
.navbar-scrolled .navbar-brand, .navbar-scrolled .navbar-nav .nav-link,
.navbar-expanded .navbar-brand, .navbar-expanded .navbar-nav .nav-link {
    color: #000;
}
.navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}
.navbar-scrolled .navbar-toggler-icon, .navbar-expanded .navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%280, 0, 0, 0.55%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
}
/* Enhanced search bar styles */
.search-wrapper {
    position: relative;
    width: 100%;
}
.search-input {
    width: 100%;
    padding: 12px 50px 12px 20px;
    border-radius: 30px;
    border: 0.5px solid rgba(255, 255, 255, 0.5);
    background-color: rgba(255, 255, 255, 0.2);
    color: #fff;
    transition: all 0.3s ease;
}
.search-input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}
.navbar-scrolled .search-input, .navbar-expanded .search-input {
    border: 0.5px solid transparent;
    background-image: linear-gradient(#fff, #fff), linear-gradient(90deg, #4ab6f4, #ff69b4);
    background-origin: border-box;
    background-clip: padding-box, border-box;
    color: #000;
}
.navbar-scrolled .search-input::placeholder, .navbar-expanded .search-input::placeholder {
    color: rgba(0, 0, 0, 0.5);
}
.search-input:focus {
    box-shadow: 0 0 15px rgba(74, 182, 244, 0.3);
    background-color: rgba(255, 255, 255, 0.9);
    color: #000;
}
.search-btn {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    background: linear-gradient(90deg, #4ab6f4, #ff69b4);
    color: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}
.search-btn:hover {
    opacity: 0.8;
}
/* Styles for nav items */
.nav-item .nav-link {
    display: flex;
    align-items: center;
}
.nav-item .nav-link i {
    margin-right: 8px;
    color: #fff; /* Set initial color to white */
    transition: color 0.3s ease;
}
.navbar-scrolled .nav-item .nav-link i,
.navbar-expanded .nav-item .nav-link i {
    color: #000; /* Change color to black when scrolled or expanded */
}
@media (max-width: 991px) {
            .navbar-brand {
                margin: auto;
            }

            .navbar-nav {
                flex-wrap: wrap;
                justify-content: center;
            }

            .navbar-nav .nav-item {
                flex: 1 0 50%; /* 2 items per row */
                margin-top: 0.5rem;
                margin-bottom: 0.5rem;
                text-align: center;
            }

            .navbar-nav .nav-item:nth-child(3) {
                flex: 1 0 33.33%; /* 3 items in the next row */
            }

            .search-wrapper {
                margin-top: 1rem;
                margin-bottom: 1rem;
                width: 100%;
            }

            .search-input {
                width: 100%;
            }

            .navbar-toggler {
                display: none;
            }

            #navbarNav {
                display: flex !important;
            }
        }
/* Login/Signup button styles */
.btn-login-signup {
    background: linear-gradient(90deg, #4ab6f4, #ff69b4);
    color: white;
    border: none;
    border-radius: 20px;
    padding: 8px 15px;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
}
.btn-login-signup:hover {
    opacity: 0.8;
    color: white;
}
.btn-login-signup i {
    margin-right: 5px;
}
    </style>

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center ms-3" href="index.php">
            <img src="logo/totsy_logo.jpg" alt="Logo" width="70" height="70" class="d-inline-block align-text-top me-3 rounded-circle">
            <span style="font-weight: 500; background: linear-gradient(90deg, #4ab6f4, #ff69b4); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">TOTSY</span>
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
        <form class="d-flex me-auto w-50" action="/totsy/pages/search_product.php" method="GET">
    <div class="search-wrapper">
        <input class="search-input" type="search" name="query" placeholder="Search products..." aria-label="Search">
        <button class="search-btn" type="submit">
            <i class='bx bx-search'></i>
        </button>
    </div>
</form>

            <ul class="navbar-nav mb-2 mb-lg-0 ms-3 me-s">
                <li class="nav-item"><a class="nav-link" href="index.php" title="Home"><i class='bx bx-home-alt'></i><span>Home</span></a></li>
                <li class="nav-item"><a class="nav-link" href="/totsy/pages/product_page.php" title="Products"><i class='bx bx-store'></i><span>Products</span></a></li>
               <li class="nav-item"><a class="nav-link" href="/totsy/pages/reviews_page.php" title="Reviews"><i class='bx bx-message-square-dots'></i><span>Reviews</span></a></li>
                <li class="nav-item"><a class="nav-link" href="/totsy/pages/about_page.php" title="About"><i class='bx bx-info-circle'></i><span>About</span></a></li>
                <li class="nav-item"><a class="nav-link" href="/totsy/pages/cart_page.php" title="Cart"><i class='bx bx-cart'></i><span>Cart</span></a></li>
                <li class="nav-item"><a class="btn btn-sm btn-login-signup rounded-pill" href="/totsy/pages/login.php"><i class='bx bx-user' style="color: white;"></i><span style="color: white;">Login</span></a></li>
            </ul>
        </div>
    </div>
</nav>

<script>
    window.addEventListener('scroll', function() {
        var navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });
</script>
</body>
