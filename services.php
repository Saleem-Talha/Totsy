<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Highlights</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        .highlight-card {
            border: none;
            border-radius: 0;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .highlight-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .highlight-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            background: linear-gradient(90deg, #4ab6f4, #ff69b4);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .highlight-description {
            font-size: 0.9rem;
            color: #666;
        }

        .highlight-icon {
            font-size: 2rem;
            color: #4ab6f4;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Product Highlights</h1>
        <div class="row">
            <?php
            $highlights = [
                ['title' => 'Enchanting Designs', 'description' => 'Bed sheets featuring beloved cartoon characters.', 'icon' => 'bx-palette'],
                ['title' => 'Premium Comfort', 'description' => 'Soft, breathable fabric for a cozy night\'s sleep.', 'icon' => 'bx-bed'],
                ['title' => 'Vibrant Colors', 'description' => 'Long-lasting, vivid prints add a pop of color to the room.', 'icon' => 'bx-color'],
                ['title' => 'Easy to Clean', 'description' => 'Low-maintenance sheets for busy parents.', 'icon' => 'bx-water'],
                ['title' => 'Durable', 'description' => 'Built to withstand kids\' daily adventures.', 'icon' => 'bx-shield'],
                ['title' => 'Perfect Fit', 'description' => 'Designed to snugly fit single beds.', 'icon' => 'bx-fullscreen'],
                ['title' => 'Spark Imagination', 'description' => 'Create a magical bedtime experience.', 'icon' => 'bx-bulb'],
                ['title' => 'Ideal Gift', 'description' => 'Perfect for birthdays or special occasions.', 'icon' => 'bx-gift']
            ];

            foreach ($highlights as $highlight):
            ?>
            <div class="col-md-6 col-lg-3">
                <div class="highlight-card p-3">
                    <i class='bx <?php echo $highlight['icon']; ?> highlight-icon'></i>
                    <h3 class="highlight-title"><?php echo $highlight['title']; ?></h3>
                    <p class="highlight-description"><?php echo $highlight['description']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>