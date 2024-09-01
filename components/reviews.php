<?php
include_once '../includes/db_connect.php';

$stmt = $db->prepare("SELECT email, description, rating, date FROM reviews ORDER BY date DESC LIMIT 10");
if ($stmt === false) {
    die("Error preparing statement: " . $db->error);
}
$stmt->execute();
$result = $stmt->get_result();
$reviews = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Feedback - Totsy</title>
    <link rel="icon" href="../logo/totsy_logo.jpg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="../css/styles.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4ab6f4;
            --secondary-color: #ff69b4;
        }
        body {
            background-color: #f8f9fa;
        }
        .gradient-text {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .gradient-bg {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }
        .card {
            border-radius: 20px;
            overflow: hidden;
            height: 400px; /* Fixed height for the card */
        }
        .card-body {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
        .carousel-control-prev,
        .carousel-control-next {
            width: 5%;
        }
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: var(--primary-color);
            border-radius: 50%;
        }
        .carousel-indicators [data-bs-target] {
            background-color: var(--secondary-color);
        }
        .star-rating {
            color: gold;
        }
        .review-description {
            font-style: italic;
            margin: auto 0;
            height: 150px; /* Fixed height for description */
            overflow-y: auto;
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .review-meta {
            margin-top: auto;
        }
    </style>
</head>
<body>
    <div class="container mb-5">
        <h1 class="text-center mb-5 gradient-text" data-aos="fade-up">Reviews</h1>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow" data-aos="fade-up">
                    <div class="card-body">
                        <div id="feedbackCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <?php foreach ($reviews as $index => $review): ?>
                                    <button type="button" data-bs-target="#feedbackCarousel" data-bs-slide-to="<?php echo $index; ?>" <?php echo $index === 0 ? 'class="active" aria-current="true"' : ''; ?> aria-label="Slide <?php echo $index + 1; ?>"></button>
                                <?php endforeach; ?>
                            </div>
                            <div class="carousel-inner">
                                <?php foreach ($reviews as $index => $review): ?>
                                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <div class="text-center">
                                        <div class="review-meta" data-aos="fade-up">
                                                <p class="mb-0"><small><?php echo htmlspecialchars($review['email']); ?></small></p>
                                                <p><small class="text-muted"><?php echo date('F j, Y', strtotime($review['date'])); ?></small></p>
                                            </div>
                                           
                                            <div class="review-description" data-aos="fade-up">
                                                <?php echo nl2br(htmlspecialchars(str_replace(['\n', '\r'], ' ', $review['description']))); ?>
                                            </div>
                                            <div class="star-rating mb-2" data-aos="fade-up">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class='bx <?php echo $i <= $review['rating'] ? 'bxs-star' : 'bx-star'; ?>'></i>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#feedbackCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#feedbackCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 1000,
                once: true
            });

            const carousel = document.querySelector('#feedbackCarousel');
            carousel.addEventListener('slide.bs.carousel', function () {
                const activeItem = this.querySelector('.carousel-item.active');
                const fadeElements = activeItem.querySelectorAll('[data-aos]');
                fadeElements.forEach((el) => {
                    el.classList.remove('aos-animate');
                    setTimeout(() => {
                        el.classList.add('aos-animate');
                    }, 50);
                });
            });
        });
    </script>
</body>
</html>