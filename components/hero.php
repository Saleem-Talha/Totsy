<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Portfolio</title>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Popper.js and Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

    <?php include 'includes/header-links.php'; ?>
    <!-- Make sure Bootstrap CSS and JS are included in your header-links.php -->
    <style>
        .carousel-item img {
            object-fit: cover;
            height: 100vh; /* Default height for larger screens */
        }
        .overlay {
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
        .carousel-content {
            z-index: 2;
        }
        .logo-btn {
            transition: transform 0.3s ease;
        }
        .logo-btn:hover {
            transform: scale(1.1);
        }
        @media (max-width: 768px) {
            .carousel-item img {
                height: 70vh; /* Adjusted height for mobile screens */
            }
            #home {
                height: 70vh !important; /* Overriding the inline style for mobile */
            }
        }
    </style>
</head>
<body>

<!-- Hero Section with Image Carousel -->
<section id="home" class="vh-100 position-relative overflow-hidden">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner h-100">
            <div class="carousel-item active h-100">
                <img src="hero/hero1.jpg" class="d-block w-100" alt="Image 1">
            </div>
            <div class="carousel-item h-100">
                <img src="hero/hero2.jpg" class="d-block w-100" alt="Image 2">
            </div>
            <div class="carousel-item h-100">
                <img src="hero/hero3.jpeg" class="d-block w-100" alt="Image 3">
            </div>
            <div class="carousel-item h-100">
                <img src="hero/hero4.jpeg" class="d-block w-100" alt="Image 4">
            </div>
            <div class="carousel-item h-100">
                <img src="hero/hero5.jpeg" class="d-block w-100" alt="Image 5">
            </div>
            <div class="carousel-item h-100">
                <img src="hero/hero6.jpeg" class="d-block w-100" alt="Image 6">
            </div>
            <div class="carousel-item h-100">
                <img src="hero/hero7.jpeg" class="d-block w-100" alt="Image 7">
            </div>
            <div class="carousel-item h-100">
                <img src="hero/hero8.jpeg" class="d-block w-100" alt="Image 8">
            </div>
        </div>
    </div>
    <div class="overlay position-absolute top-0 start-0 w-100 h-100"></div>
    <div class="carousel-content position-absolute top-50 start-50 translate-middle text-center text-white w-100 px-4">
        <div class="container">
            <h1 class="display-4 mb-4 main-text animate__animated animate__fadeInDown" style="font-weight: 300;">
                Welcome to 
                <span class="d-inline-block position-relative">
                    <span style="font-weight: 500; background: linear-gradient(90deg, #4ab6f4, #ff69b4); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">TOTSY</span>
                </span>
                <span style="font-weight: 300;">.pk</span>
            </h1>
            <p class="lead mb-5 montserrat-400 animate__animated animate__fadeInUp animate__delay-1s">Dream in color—explore our vibrant bedsheet range</p>
            <div class="d-flex justify-content-center">
                <a href="https://www.daraz.pk/toys-n-toys-1663884939/?q=All-Products&from=wangpu&langFlag=en&pageTypeId=2" class="me-3 logo-btn animate__animated animate__fadeInUp animate__delay-2s" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Visit Totsy.pk on Daraz">
                    <img src="logo/totsy_logo.jpg" alt="Totsy Logo" class="img-fluid rounded-circle" style="width: 60px; height: 60px;">
                </a>
                <a href="https://www.daraz.pk/ac7c3zby/?q=All-Products&from=wangpu&langFlag=en&pageTypeId=2" class="logo-btn animate__animated animate__fadeInUp animate__delay-2s" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Visit Velvet & Vibes on Daraz">
                    <img src="logo/other_logo.jpeg" alt="Other Logo" class="img-fluid rounded-circle" style="width: 60px; height: 60px;">
                </a>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>

</body>
</html>