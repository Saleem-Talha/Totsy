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
        /* Styles for navbar */
        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: #000;
        }
        .navbar-toggler-icon {
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
            border: 0.5px solid rgba(0, 0, 0, 0.1);
            background-color: #f8f9fa;
            color: #000;
            transition: all 0.3s ease;
        }
        .search-input::placeholder {
            color: rgba(0, 0, 0, 0.5);
        }
        .search-input:focus {
            box-shadow: 0 0 15px rgba(74, 182, 244, 0.3);
            background-color: #fff;
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
            color: #000;
        }
        @media (min-width: 992px) {
            .nav-item .nav-link span {
                display: none;
            }
            .nav-item .nav-link {
                padding: 0.5rem;
            }
        }
        /* Add more vertical margin for smaller screens */
        @media (max-width: 991px) {
            .navbar-nav .nav-item {
                margin-top: 0.5rem;
                margin-bottom: 0.5rem;
            }
            .search-wrapper{
                margin-top: 1rem;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center ms-3" href="../index.php">
            <img src="../logo/totsy_logo.jpg" alt="Logo" width="70" height="70" class="d-inline-block align-text-top me-3 rounded-circle">
            <span style="font-weight: 500; background: linear-gradient(90deg, #4ab6f4, #ff69b4); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">TOTSY</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <form class="d-flex me-auto w-50">
                <div class="search-wrapper">
                    <input class="search-input" type="search" placeholder="Search products..." aria-label="Search">
                    <button class="search-btn" type="submit">
                        <i class='bx bx-search'></i>
                    </button>
                </div>
            </form>

            <ul class="navbar-nav mb-2 mb-lg-0 ms-3 me-s">
                <li class="nav-item"><a class="nav-link" href="../index.php" title="Home"><i class='bx bx-home-alt'></i><span>Home</span></a></li>
                <li class="nav-item"><a class="nav-link" href="../pages/reviews_page.php" title="Feedbacks"><i class='bx bx-message-square-dots'></i><span>Give Feedbacks</span></a></li>
                <li class="nav-item"><a class="nav-link" href="../pages/about_page.php" title="About"><i class='bx bx-info-circle'></i><span>About</span></a></li>
             <li class="nav-item"><a class="nav-link" href="../pages/cart_page.php" title="Cart"><i class='bx bx-cart'></i><span>Cart</span></a></li>
            </ul>
        </div>
    </div>
</nav>
</body>
