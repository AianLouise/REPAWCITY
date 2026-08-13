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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght@24,400..700&display=swap">
    <style>
        .mui-icon {
            font-family: 'Material Symbols Rounded';
            font-weight: normal;
            font-style: normal;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }
    </style>
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
            <div class="absolute inset-0 bg-gradient-to-br from-repaw-bg/90 via-repaw-bg/70 to-repaw-accent/40 pointer-events-none"></div>

            <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-20 lg:py-28">
                <div class="grid grid-cols-1 gap-12 items-center">
                    <div class="max-w-3xl">
                        <span class="inline-flex items-center gap-2 rounded-full bg-repaw-accent/80 px-4 py-1.5 text-sm font-medium text-repaw-dark mb-6">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.5 11a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Zm3.5 3.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Zm8.5-3.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Zm3.5 3.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5ZM12 13c-2.71 0-5.05.94-6.56 2.47C4.65 16.6 4.2 17.9 4.2 19.2 4.2 20.66 5.34 22 6.8 22h10.4c1.46 0 2.6-1.34 2.6-2.8 0-1.3-.45-2.6-1.24-3.73C17.05 13.94 14.71 13 12 13Z"/></svg> Adopt · Donate · Volunteer
                        </span>
                        <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold text-repaw-dark leading-tight">
                            Every pet deserves a<br><span class="text-repaw-text">forever home.</span>
                        </h1>
                        <p class="mt-6 text-lg text-repaw-text/90 leading-relaxed max-w-xl">
                            rePaw City connects rescuable dogs and cats across the Philippines with loving families. Browse adoptable pets, support our mission, or lend a hand as a volunteer.
                        </p>
                        <div class="mt-9 flex flex-wrap gap-4">
                            <a href="pages/adoptpage.php" class="btn-repaw btn-repaw-primary">
                                <span class="mui-icon text-[20px]">pets</span> Adopt a Pet
                            </a>
                            <a href="pages/donatepage.php" class="btn-repaw btn-repaw-accent">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 21.35-1.45-1.32C5.4 15.36 2 12.27 2 8.5 2 5.41 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.08C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.41 22 8.5c0 3.77-3.4 6.86-8.55 11.53L12 21.35Z"/></svg> Donate
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
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    </div>
                    <h3 class="font-serif text-xl font-semibold text-repaw-dark mb-2">Adopt</h3>
                    <p class="text-repaw-text/80 text-sm leading-relaxed">Give a rescue dog or cat the loving home they've been waiting for.</p>
                </a>

                <a href="pages/donatepage.php" class="group bg-white/70 rounded-3xl p-8 shadow-sm hover:shadow-xl transition-shadow duration-300 border border-repaw-hover/40">
                    <div class="w-14 h-14 rounded-2xl bg-repaw-accent text-repaw-dark flex items-center justify-center text-2xl mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20 12.06 11.74 4 9.8 5.94l7.26 7.26L9.8 20.46 11.74 22.4 20 14.16 20 12.06M21.4 11.34 12.07 2 2 11.34l1.3 1.3L12.07 4.6l8.77 8.04 1.56-1.3Zm-9.86 1.5c-.6 0-1.09.49-1.09 1.09 0 .6.49 1.09 1.09 1.09.6 0 1.09-.49 1.09-1.09-.01-.6-.49-1.09-1.09-1.09m0 2.5c-.78 0-1.41-.63-1.41-1.41 0-.78.63-1.41 1.41-1.41.78 0 1.41.63 1.41 1.41 0 .78-.63 1.41-1.41 1.41"/></svg>
                    </div>
                    <h3 class="font-serif text-xl font-semibold text-repaw-dark mb-2">Donate</h3>
                    <p class="text-repaw-text/80 text-sm leading-relaxed">Fund food, medical care, and shelter for pets on their road to recovery.</p>
                </a>

                <a href="pages/volunteer.php" class="group bg-white/70 rounded-3xl p-8 shadow-sm hover:shadow-xl transition-shadow duration-300 border border-repaw-hover/40">
                    <div class="w-14 h-14 rounded-2xl bg-repaw-accent text-repaw-dark flex items-center justify-center text-2xl mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3Zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5Zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5Z"/></svg>
                    </div>
                    <h3 class="font-serif text-xl font-semibold text-repaw-dark mb-2">Volunteer</h3>
                    <p class="text-repaw-text/80 text-sm leading-relaxed">Lend your time and skills to walk, feed, and care for our furry friends.</p>
                </a>
            </div>
        </section>

        <!-- Call to action -->
        <section class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pb-20">
            <div class="relative overflow-hidden rounded-[3rem] bg-repaw-dark text-repaw-bg px-8 py-14 lg:px-16 text-center">
                <h2 class="font-serif text-3xl sm:text-4xl font-bold relative z-10">Ready to meet your new best friend?</h2>
                <p class="mt-4 text-repaw-bg/80 max-w-2xl mx-auto relative z-10">Browse our adoptable pets today and start your journey toward a fuller, happier home.</p>
                <a href="pages/adoptpage.php" class="btn-repaw btn-repaw-accent mt-8 relative z-10">
                    <span class="mui-icon text-[20px]">pets</span> Start Adopting
                </a>
            </div>
        </section>
    </main>

    <?php require __DIR__ . '/includes/footer.php'; ?>

</body>

</html>
