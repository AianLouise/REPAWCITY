<?php require '../includes/config.php' ?>
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/icon.png" type="image/png">
    <title>Contact — rePaw City</title>
    <meta name="description" content="Get in touch with rePaw City.">
</head>

<body class="font-sans bg-repaw-bg text-repaw-text antialiased">

    <?php include '../includes/navbar.php' ?>

    <main id="top">
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-repaw-bg/90 via-repaw-bg/70 to-repaw-accent/40 pointer-events-none"></div>
            <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-16 lg:py-20 text-center">
                <h1 class="font-serif text-4xl sm:text-5xl font-bold text-repaw-dark">Contact Us</h1>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Info card -->
                <div class="bg-white/70 rounded-3xl p-8 sm:p-10 border border-repaw-hover/40 shadow-sm">
                    <p class="text-repaw-text/90 leading-relaxed mb-6">
                        We're thrilled that you're interested in connecting with us. If you have questions or feedback,
                        don't hesitate to reach out. Our team is ready to assist you and provide the information you
                        need. We look forward to connecting with you!
                    </p>
                    <hr class="border-repaw-hover/50 mb-6">
                    <div class="space-y-4">
                        <p class="flex items-center gap-3 text-repaw-text/90">
                            <span class="mui-icon text-repaw-dark text-2xl">call</span>
                            <a href="tel:+639234897632" class="hover:text-repaw-dark transition-colors">+63 923 4897 632</a>
                        </p>
                        <p class="flex items-center gap-3 text-repaw-text/90">
                            <span class="mui-icon text-repaw-dark text-2xl">mail</span>
                            <a href="mailto:repawcity@gmail.com" class="hover:text-repaw-dark transition-colors">repawcity@gmail.com</a>
                        </p>
                        <p class="flex items-start gap-3 text-repaw-text/90">
                            <span class="mui-icon text-repaw-dark text-2xl mt-0.5">place</span>
                            <span>#135 Purok 3, Balsik, Hermosa, Bataan, Philippines 2111</span>
                        </p>
                    </div>
                    <hr class="border-repaw-hover/50 my-6">
                    <div class="flex gap-3">
                        <a href="#" aria-label="Facebook"
                           class="w-11 h-11 rounded-full bg-repaw-dark text-repaw-accent flex items-center justify-center hover:bg-repaw-text hover:scale-110 transition-all duration-300 shadow-md">
                            <i class="bi bi-facebook text-lg"></i>
                        </a>
                        <a href="#" aria-label="Instagram"
                           class="w-11 h-11 rounded-full bg-repaw-dark text-repaw-accent flex items-center justify-center hover:bg-repaw-text hover:scale-110 transition-all duration-300 shadow-md">
                            <i class="bi bi-instagram text-lg"></i>
                        </a>
                        <a href="#" aria-label="TikTok"
                           class="w-11 h-11 rounded-full bg-repaw-dark text-repaw-accent flex items-center justify-center hover:bg-repaw-text hover:scale-110 transition-all duration-300 shadow-md">
                            <i class="bi bi-tiktok text-lg"></i>
                        </a>
                    </div>
                </div>

                <!-- Map -->
                <div class="rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm min-h-[320px]">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d619.8446481048363!2d120.4906345!3d14.862239!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3396f20a82a1a269%3A0x0!2s14%C2%B051&#39;44.1%22N%20120%C2%B029&#39;26.3%22E!5e0!3m2!1sen!2sus!4v1626317845211!5m2!1sen!2sus"
                        width="100%" height="100%" style="border:0; min-height:320px;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php' ?>

</body>

</html>
