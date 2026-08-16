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
    <title>Our Team — rePaw City</title>
    <meta name="description" content="Meet the rePaw City team.">
</head>

<body class="font-sans bg-repaw-bg text-repaw-text antialiased">

    <?php include '../includes/navbar.php' ?>

    <main id="top">
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-repaw-bg/90 via-repaw-bg/70 to-repaw-accent/40 pointer-events-none"></div>
            <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-16 lg:py-20 text-center">
                <h1 class="font-serif text-4xl sm:text-5xl font-bold text-repaw-dark">Our Team</h1>
            </div>
        </section>

        <section class="max-w-6xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm text-center">
                    <img src="../image/team/ALFARO.png" alt="Aian Louise A. Alfaro" class="w-32 h-32 rounded-full object-cover mx-auto mb-5 border-4 border-repaw-accent">
                    <p class="font-serif text-lg font-semibold text-repaw-dark">AIAN LOUISE A. ALFARO</p>
                    <p class="text-repaw-text/80 mt-1">Administrative</p>
                    <p class="text-sm text-repaw-text/60 mt-1">aianlouisealfaro@gmail.com</p>
                </div>
                <div class="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm text-center">
                    <img src="../image/team/GAMBOA.png" alt="Edgar Gamboa Jr." class="w-32 h-32 rounded-full object-cover mx-auto mb-5 border-4 border-repaw-accent">
                    <p class="font-serif text-lg font-semibold text-repaw-dark">EDGAR GAMBOA JR.</p>
                    <p class="text-repaw-text/80 mt-1">Appointments/Inquiries</p>
                    <p class="text-sm text-repaw-text/60 mt-1">edgargamboa@gmail.com</p>
                </div>
                <div class="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm text-center">
                    <img src="../image/team/IBAY.png" alt="Armyn Jace Ibay" class="w-32 h-32 rounded-full object-cover mx-auto mb-5 border-4 border-repaw-accent">
                    <p class="font-serif text-lg font-semibold text-repaw-dark">ARMYN JACE IBAY</p>
                    <p class="text-repaw-text/80 mt-1">Donations and Volunteers</p>
                    <p class="text-sm text-repaw-text/60 mt-1">armynjace@gmail.com</p>
                </div>
                <div class="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm text-center">
                    <img src="../image/team/LAXAMANA.png" alt="Alfred Laxamana" class="w-32 h-32 rounded-full object-cover mx-auto mb-5 border-4 border-repaw-accent">
                    <p class="font-serif text-lg font-semibold text-repaw-dark">ALFRED LAXAMANA</p>
                    <p class="text-repaw-text/80 mt-1">Adoption</p>
                    <p class="text-sm text-repaw-text/60 mt-1">alfredlaxamana@gmail.com</p>
                </div>
                <div class="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm text-center">
                    <img src="../image/team/LUZANO.png" alt="Nicole Luzano" class="w-32 h-32 rounded-full object-cover mx-auto mb-5 border-4 border-repaw-accent">
                    <p class="font-serif text-lg font-semibold text-repaw-dark">NICOLE LUZANO</p>
                    <p class="text-repaw-text/80 mt-1">General Information</p>
                    <p class="text-sm text-repaw-text/60 mt-1">nicoleluzano@gmail.com</p>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php' ?>

</body>

</html>
