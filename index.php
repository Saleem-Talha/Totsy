<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Totsy</title>
   
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
