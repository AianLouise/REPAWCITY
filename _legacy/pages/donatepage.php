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
    <title>Donate — rePaw City</title>
    <meta name="description" content="Support pets in need at rePaw City through bank transfer, GCash, or cash donations.">
</head>

<body class="font-sans bg-repaw-bg text-repaw-text antialiased">

    <?php include '../includes/navbar.php' ?>

    <main id="top">
        <!-- Hero -->
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-repaw-bg/90 via-repaw-bg/70 to-repaw-accent/40 pointer-events-none"></div>
            <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-16 lg:py-20 text-center">
                <h1 class="font-serif text-4xl sm:text-5xl font-bold text-repaw-dark">Give a little, help a lot.</h1>
                <p class="mt-4 text-lg text-repaw-text/90 max-w-2xl mx-auto">Donate to support pets in need and help us give every animal a happy, healthy life.</p>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
            <!-- Intro + images -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
                <div class="flex gap-4 justify-center lg:justify-start">
                    <img src="../image/donDog.jpeg" alt="Dog in need" class="w-1/2 rounded-3xl object-cover aspect-[4/5] shadow-sm">
                    <img src="../image/donCat.jpeg" alt="Cat in need" class="w-1/2 rounded-3xl object-cover aspect-[4/5] shadow-sm">
                </div>
                <div>
                    <h2 class="font-serif text-3xl font-bold text-repaw-dark mb-4">Donate</h2>
                    <p class="text-repaw-text/90 leading-relaxed">
                        Welcome to our pet donation page, where you have the opportunity to make a positive impact
                        on the lives of pets in need. At our organization, we are passionate about ensuring that
                        every pet has access to the care and support they need to live a happy, healthy life.
                        Unfortunately, many pets find themselves in difficult situations, whether they are homeless,
                        sick, or in need of medical care that their owners cannot afford. That's where your donation
                        can make a real difference.
                    </p>
                    <p class="mt-4 text-repaw-text/90 leading-relaxed">
                        Through your generosity, we are able to provide essential resources to pets in need,
                        including food, shelter, medical care, and other vital services. Every donation, no matter
                        the size, makes a difference in the lives of pets and their families. Thank you for
                        considering a donation to support our mission.
                    </p>
                </div>
            </div>

            <!-- Donation methods -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm text-center">
                    <span class="mui-icon text-4xl text-repaw-dark mb-4 block">account_balance</span>
                    <h3 class="font-serif text-xl font-semibold text-repaw-dark mb-3">Bank Transfer</h3>
                    <hr class="border-repaw-hover/50 mb-5">
                    <img src="../image/qrcode_bank.png" alt="Bank Transfer QR Code" class="mx-auto w-40 h-40 object-contain mb-5">
                    <p class="text-sm text-repaw-text/80"><strong>Account Number:</strong></p>
                    <p class="text-repaw-dark font-medium">0036-4007-0350</p>
                    <p class="mt-3 text-sm text-repaw-text/80"><strong>Account Name:</strong></p>
                    <p class="text-repaw-dark font-medium">Repaw City</p>
                </div>

                <div class="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm text-center">
                    <span class="mui-icon text-4xl text-repaw-dark mb-4 block">smartphone</span>
                    <h3 class="font-serif text-xl font-semibold text-repaw-dark mb-3">GCash Transfer</h3>
                    <hr class="border-repaw-hover/50 mb-5">
                    <img src="../image/qrcode_gcash.png" alt="GCash Transfer QR Code" class="mx-auto w-40 h-40 object-contain mb-5">
                    <p class="text-sm text-repaw-text/80"><strong>Account Number:</strong></p>
                    <p class="text-repaw-dark font-medium">0912-345-6789</p>
                    <p class="mt-3 text-sm text-repaw-text/80"><strong>Account Name:</strong></p>
                    <p class="text-repaw-dark font-medium">Repaw City</p>
                </div>

                <div class="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm text-center flex flex-col">
                    <span class="mui-icon text-4xl text-repaw-dark mb-4 block">payments</span>
                    <h3 class="font-serif text-xl font-semibold text-repaw-dark mb-3">Cash</h3>
                    <hr class="border-repaw-hover/50 mb-5">
                    <p class="text-sm text-repaw-text/80 leading-relaxed">
                        Please <a href="contact.php" class="text-repaw-dark font-medium underline underline-offset-2 hover:text-repaw-text">let us know</a>
                        when would be a good time for you to drop by the shelter. We'll be very pleased to meet you
                        and show some of our pets that we're helping!
                    </p>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php' ?>

</body>

</html>
