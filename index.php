<?php
require __DIR__ . '/includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="image/icon.png" type="image/png">
    <title>rePaw City — Find Your Furever Friend</title>
    <meta name="description" content="rePaw City helps pets across the Philippines find their forever homes through adoption, donation, and volunteering.">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap">
    <script src="https://kit.fontawesome.com/98b545cfa6.js" crossorigin="anonymous"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        repaw: {
                            bg: '#f5e6d3',
                            text: '#6c4421',
                            hover: '#d6bca8',
                            dark: '#4a2c17',
                            accent: '#fad046',
                            danger: '#c62828',
                        }
                    },
                    fontFamily: {
                        sans: ['Roboto', 'sans-serif'],
                        serif: ['Montserrat', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer components {
            .btn-repaw {
                @apply inline-flex items-center justify-center gap-2 rounded-full px-7 py-3 text-[15px] font-medium uppercase tracking-wide transition-colors duration-300;
            }
            .btn-repaw-primary {
                @apply bg-repaw-text text-repaw-bg hover:bg-repaw-dark;
            }
            .btn-repaw-accent {
                @apply bg-repaw-accent text-repaw-dark hover:bg-repaw-dark hover:text-repaw-accent;
            }
        }
    </style>
</head>

<body class="font-sans bg-repaw-bg text-repaw-text antialiased">

    <?php require __DIR__ . '/includes/navbar.php'; ?>

    <main id="top">
        <!-- Hero -->
        <section class="relative overflow-hidden">
            <img src="image/landingbg.png" alt="" class="absolute inset-0 w-full h-full object-cover opacity-30 pointer-events-none">
            <div class="absolute inset-0 bg-gradient-to-br from-repaw-bg/90 via-repaw-bg/70 to-repaw-accent/40 pointer-events-none"></div>

            <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-20 lg:py-28">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-repaw-accent/80 px-4 py-1.5 text-sm font-medium text-repaw-dark mb-6">
                            <i class="fa-solid fa-paw"></i> Adopt · Donate · Volunteer
                        </span>
                        <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold text-repaw-dark leading-tight">
                            Every pet deserves a<br><span class="text-repaw-text">forever home.</span>
                        </h1>
                        <p class="mt-6 text-lg text-repaw-text/90 leading-relaxed max-w-xl">
                            rePaw City connects rescuable dogs and cats across the Philippines with loving families. Browse adoptable pets, support our mission, or lend a hand as a volunteer.
                        </p>
                        <div class="mt-9 flex flex-wrap gap-4">
                            <a href="pages/adoptpage.php" class="btn-repaw btn-repaw-primary">
                                <i class="fa-solid fa-dog"></i> Adopt a Pet
                            </a>
                            <a href="pages/donatepage.php" class="btn-repaw btn-repaw-accent">
                                <i class="fa-solid fa-heart"></i> Donate
                            </a>
                        </div>

                        <dl class="mt-12 grid grid-cols-3 gap-6 max-w-md">
                            <div>
                                <dt class="font-serif text-3xl font-bold text-repaw-dark">500+</dt>
                                <dd class="text-sm text-repaw-text/80 mt-1">Pets Rehomed</dd>
                            </div>
                            <div>
                                <dt class="font-serif text-3xl font-bold text-repaw-dark">120</dt>
                                <dd class="text-sm text-repaw-text/80 mt-1">Active Volunteers</dd>
                            </div>
                            <div>
                                <dt class="font-serif text-3xl font-bold text-repaw-dark">15</dt>
                                <dd class="text-sm text-repaw-text/80 mt-1">Partner Shelters</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="relative">
                        <img src="image/pets.png" alt="Happy adoptable pets" class="relative mx-auto w-full max-w-md drop-shadow-2xl rounded-3xl">
                        <img src="image/paw.png" alt="" class="absolute -top-6 -right-4 w-20 opacity-70 animate-bounce">
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Categories -->
        <section class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-16">
            <div class="text-center mb-12">
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-repaw-dark">How You Can Help</h2>
                <p class="mt-3 text-repaw-text/80 max-w-2xl mx-auto">Three simple ways to make a life-changing difference for an animal in need.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <a href="pages/adoptpage.php" class="group bg-white/70 rounded-3xl p-8 shadow-sm hover:shadow-xl transition-shadow duration-300 border border-repaw-hover/40">
                    <div class="w-14 h-14 rounded-2xl bg-repaw-accent text-repaw-dark flex items-center justify-center text-2xl mb-5 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-house-dog"></i>
                    </div>
                    <h3 class="font-serif text-xl font-semibold text-repaw-dark mb-2">Adopt</h3>
                    <p class="text-repaw-text/80 text-sm leading-relaxed">Give a rescue dog or cat the loving home they've been waiting for.</p>
                </a>

                <a href="pages/donatepage.php" class="group bg-white/70 rounded-3xl p-8 shadow-sm hover:shadow-xl transition-shadow duration-300 border border-repaw-hover/40">
                    <div class="w-14 h-14 rounded-2xl bg-repaw-accent text-repaw-dark flex items-center justify-center text-2xl mb-5 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-hand-holding-heart"></i>
                    </div>
                    <h3 class="font-serif text-xl font-semibold text-repaw-dark mb-2">Donate</h3>
                    <p class="text-repaw-text/80 text-sm leading-relaxed">Fund food, medical care, and shelter for pets on their road to recovery.</p>
                </a>

                <a href="pages/volunteer.php" class="group bg-white/70 rounded-3xl p-8 shadow-sm hover:shadow-xl transition-shadow duration-300 border border-repaw-hover/40">
                    <div class="w-14 h-14 rounded-2xl bg-repaw-accent text-repaw-dark flex items-center justify-center text-2xl mb-5 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-people-group"></i>
                    </div>
                    <h3 class="font-serif text-xl font-semibold text-repaw-dark mb-2">Volunteer</h3>
                    <p class="text-repaw-text/80 text-sm leading-relaxed">Lend your time and skills to walk, feed, and care for our furry friends.</p>
                </a>
            </div>
        </section>

        <!-- Call to action -->
        <section class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pb-20">
            <div class="relative overflow-hidden rounded-[3rem] bg-repaw-dark text-repaw-bg px-8 py-14 lg:px-16 text-center">
                <img src="image/paw.png" alt="" class="absolute -bottom-10 -left-10 w-40 opacity-10">
                <img src="image/paw.png" alt="" class="absolute -top-10 -right-10 w-40 opacity-10 rotate-45">
                <h2 class="font-serif text-3xl sm:text-4xl font-bold relative z-10">Ready to meet your new best friend?</h2>
                <p class="mt-4 text-repaw-bg/80 max-w-2xl mx-auto relative z-10">Browse our adoptable pets today and start your journey toward a fuller, happier home.</p>
                <a href="pages/adoptpage.php" class="btn-repaw btn-repaw-accent mt-8 relative z-10">
                    <i class="fa-solid fa-paw"></i> Start Adopting
                </a>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>

</body>

</html>
