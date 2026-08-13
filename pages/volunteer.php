<?php require '../includes/config.php';

session_start();

$loggedIn = isset($_SESSION['auth_user']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/icon.png" type="image/png">
    <title>Volunteer — rePaw City</title>
    <meta name="description" content="Join rePaw City as a volunteer and help animals in need.">
</head>

<body class="font-sans bg-repaw-bg text-repaw-text antialiased">

    <?php include '../includes/navbar.php' ?>

    <main id="top">
        <!-- Hero -->
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-repaw-bg/90 via-repaw-bg/70 to-repaw-accent/40 pointer-events-none"></div>
            <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-16 lg:py-20 text-center">
                <h1 class="font-serif text-4xl sm:text-5xl font-bold text-repaw-dark max-w-3xl mx-auto">Join a passionate community of animal lovers and contribute to a meaningful cause.</h1>
                <a href="<?php echo $loggedIn ? '../booking/book-appointment.php' : 'loginpage.php'; ?>"
                   class="btn-repaw btn-repaw-primary mt-8"
                   <?php echo $loggedIn ? 'target="_blank"' : ''; ?>>
                    <span class="mui-icon text-[20px]">volunteer_activism</span> Become a Volunteer
                </a>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
            <div class="rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm mb-12">
                <img src="../image/volunteer/img1.jpg" alt="Volunteers with pets" class="w-full h-72 object-cover">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-12">
                <div class="rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm">
                    <img src="../image/volunteer/img2.jpg" alt="Volunteer activity" class="w-full h-56 object-cover">
                </div>
                <div class="rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm">
                    <img src="../image/volunteer/img3.jpg" alt="Volunteer activity" class="w-full h-56 object-cover">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
                    <h2 class="font-serif text-2xl font-bold text-repaw-dark mb-4">Volunteer Requirements</h2>
                    <ul class="space-y-3 text-repaw-text/90">
                        <li class="flex gap-2"><span class="mui-icon text-repaw-dark">check_circle</span> Compassion and respect for animals, with a commitment to their well-being.</li>
                        <li class="flex gap-2"><span class="mui-icon text-repaw-dark">check_circle</span> Availability to commit to a regular schedule or specific event dates.</li>
                        <li class="flex gap-2"><span class="mui-icon text-repaw-dark">check_circle</span> No age limit.</li>
                        <li class="flex gap-2"><span class="mui-icon text-repaw-dark">check_circle</span> Want to learn and grow.</li>
                    </ul>
                </div>

                <div class="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
                    <h2 class="font-serif text-2xl font-bold text-repaw-dark mb-4">Volunteer Opportunities</h2>
                    <ul class="space-y-3 text-repaw-text/90">
                        <li class="flex gap-2"><span class="mui-icon text-repaw-dark">pets</span> Support adoption events and assist potential adopters in meeting our animals.</li>
                        <li class="flex gap-2"><span class="mui-icon text-repaw-dark">pets</span> Help with socializing, grooming, and exercising the animals in preparation for adoption.</li>
                        <li class="flex gap-2"><span class="mui-icon text-repaw-dark">pets</span> Assist with feeding, cleaning, and providing enrichment activities for the animals.</li>
                    </ul>
                </div>

                <div class="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
                    <h2 class="font-serif text-2xl font-bold text-repaw-dark mb-4">Benefits</h2>
                    <ul class="space-y-3 text-repaw-text/90">
                        <li class="flex gap-2"><span class="mui-icon text-repaw-dark">favorite</span> Gain valuable experience working with animals and developing essential skills.</li>
                        <li class="flex gap-2"><span class="mui-icon text-repaw-dark">favorite</span> Join a passionate community of animal lovers and contribute to a meaningful cause.</li>
                        <li class="flex gap-2"><span class="mui-icon text-repaw-dark">favorite</span> Personal fulfillment and the joy of seeing animals thrive in their new homes.</li>
                    </ul>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="<?php echo $loggedIn ? '../booking/book-appointment.php' : 'loginpage.php'; ?>"
                   class="btn-repaw btn-repaw-accent"
                   <?php echo $loggedIn ? 'target="_blank"' : ''; ?>>
                    <span class="mui-icon text-[20px]">volunteer_activism</span> Sign Up to Volunteer
                </a>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php' ?>

</body>

</html>
