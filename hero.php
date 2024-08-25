<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Portfolio</title>
    <?php include 'includes/header-links.php'; ?>
    <!-- Make sure Bootstrap CSS and JS are included in your header-links.php -->
    <style>
        .carousel-item img {
            object-fit: cover;
        }
        .overlay {
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
        .carousel-content {
            z-index: 2;
        }
        

    </style>
</head>
<body>

<!-- Hero Section with Image Carousel -->
<section id="home" class="vh-100 position-relative overflow-hidden">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner h-100">
            <div class="carousel-item active h-100">
                <img src="hero/hero1.jpg" class="d-block w-100 h-100" alt="Image 1">
            </div>
            <div class="carousel-item h-100">
                <img src="hero/hero2.jpg" class="d-block w-100 h-100" alt="Image 2">
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
            <a href="https://www.daraz.pk/shop/toys-n-toys-1663884939?path=profile.htm&pageTypeId=1" target="_blank" class="btn btn-sm btn-lg animate__animated animate__fadeInDown animate__delay-2s" style="background: linear-gradient(90deg, #4ab6f4, #ff69b4); color: white; border: none; transition: all 0.3s ease;">
                <i class='bx bx-store-alt me-2 '></i>Visit Store

            </a>
            <style>
                .btn:hover {
                    background: linear-gradient(90deg, #ff69b4, #4ab6f4) !important;
                    color: white !important;
                }
            </style>
        </div>
    </div>
</section>



</body>
</html>