<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Totsy</title>
    <link rel="icon" href="logo/totsy_logo.jpg" type="image/x-icon">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Your custom CSS - make sure the path is correct -->
    <link rel="stylesheet" href="/totsy/css/styles.css">
    
   
</head>
<body>
    
<?php include 'includes/navbar.php'; ?>
<?php include 'hero.php'; ?>
<?php include 'products.php'; ?>
<?php include 'services.php'; ?>
<?php include 'reviews.php'; ?>
<?php include 'about.php'; ?>
<?php include 'contact.php'; ?>
<?php include 'includes/footer.php'; ?>
<?php include 'includes/footer_links.php'; ?>

</body>
</html>

<script>
        document.addEventListener('keydown', function(event) {
            // Check if Ctrl, Alt, and 'A' keys are pressed simultaneously
            if (event.ctrlKey && event.altKey && event.key === 'a') {
                // Prevent the default action to avoid potential conflicts
                event.preventDefault();
                // Redirect to admin/admin.php
                window.location.href = 'admin/pass.php';
            }
        });
    </script>

