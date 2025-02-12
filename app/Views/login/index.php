<?= $this->extend('login/template'); ?>
<?= $this->section('content'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login To Kasiran</title>
    <!-- Update the favicon link to use the correct path -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/YPE-LOGO-BLUE-GOLD-SMALL.png'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .bg-slider {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }	

        .bg-slider img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .bg-slider img.active {
            opacity: 1;
        }

        .login-container {
            height: 100%;
            display: flex;
            justify-content: center; /* Center the form horizontally */
            align-items: center;
            padding: 30px;
            position: relative;
        }

        .card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            background-color: rgba(255, 255, 255, 0.85); /* Slight transparency for an elegant effect */
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .logo {
            display: block;
            margin: 0 auto 15px auto;
            width: 95px; /* Adjust the width as needed */
        }
    </style>
</head>

<body>
    <!-- Background Image Slider -->
    <div class="bg-slider">
        <img src="https://wildnordics.com/wp-content/uploads/2019/01/Dolomites_Italy.jpg" alt="Image 1" class="active">
        <img src="https://cdn.prod.website-files.com/5f58a077d654db1a689fd95b/62136cd01720ee70886ca662_picfair-014732403-lake-over-the-sea-fu-PREVIEW-ONLY.jpg" alt="Image 2">
        <img src="https://i.natgeofe.com/n/fdf294e2-8cd5-4fe5-884e-836bff2092f9/europe-hikes-alpe-adria.jpg" alt="Image 3">
        <img src="https://media.cnn.com/api/v1/images/stellar/prod/190905140911-15-europe-beautiful-places.jpg?q=w_2056,h_1157,x_0,y_0,c_fill" alt="Image 4">
        <img src="https://www.telegraph.co.uk/content/dam/Travel/2019/November/tuscany-iStock-978368192.jpg" alt="Image 5">
    </div>

    <!-- Login Form -->
    <div class="login-container">
        <div class="card">
		<img loading="lazy" class="logo" src="<?= base_url('assets/images/YPE-LOGO-BLUE-GOLD-SMALL.png'); ?>" alt="logo" style="width: 95px;">
            <h3 class="text-center mb-5">Login to Kasiran</h3>
            <form class="auth-form login-form" method="post" action="<?= base_url('login/process'); ?>">
                <?= csrf_field(); ?>
                <div class="mb-3">
                    <label for="username" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="email" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Log In</button>
            </form>
        </div>
    </div>

    <script>
        let currentIndex = 1;
        const images = document.querySelectorAll('.bg-slider img');

        function showNextImage() {
            images[currentIndex].classList.remove('active');
            currentIndex = (currentIndex + 1) % images.length;
            images[currentIndex].classList.add('active');
        }

        setInterval(showNextImage, 4000);
    </script>
</body>

</html>

<?= $this->endSection(); ?>
