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

<footer class="relative mt-12 bg-repaw-accent rounded-t-[6rem] font-sans overflow-hidden">
    <!-- Decorative paw print watermark -->
    <div class="absolute -top-10 -right-10 opacity-10 pointer-events-none select-none" aria-hidden="true">
        <i class="fa-solid fa-paw text-[20rem] text-repaw-dark"></i>
    </div>
    <div class="absolute bottom-0 -left-16 opacity-10 pointer-events-none select-none" aria-hidden="true">
        <i class="fa-solid fa-paw text-[16rem] text-repaw-dark"></i>
    </div>

    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 pb-10 relative z-10">
        <!-- Top section: Logo + Quick Links + About + Address -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">
            <!-- Logo -->
            <div class="flex flex-col items-start">
                <a href="/index.php" class="group" aria-label="rePaw City home">
                    <img src="/image/logo (1).png" alt="rePaw City" class="h-20 w-auto transition-transform duration-300 group-hover:scale-105">
                </a>
                <p class="mt-4 text-repaw-text/80 text-sm leading-relaxed max-w-[220px]">
                    Helping pets find their forever homes across the Philippines.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="font-serif text-xl font-semibold text-repaw-dark mb-5 relative inline-block">
                    Quick Links
                    <span class="absolute -bottom-1 left-0 w-10 h-[3px] bg-repaw-dark rounded-full"></span>
                </h3>
                <ul class="space-y-2.5">
                    <li>
                        <a href="/pages/adoptpage.php?type=Dog" class="text-repaw-text hover:text-repaw-dark transition-colors duration-200 text-[15px] font-medium inline-flex items-center gap-2 group">
                            <span class="w-1.5 h-1.5 bg-repaw-dark rounded-full opacity-40 group-hover:opacity-100 transition-opacity"></span>
                            Adopt a Dog
                        </a>
                    </li>
                    <li>
                        <a href="/pages/adoptpage.php?type=Cat" class="text-repaw-text hover:text-repaw-dark transition-colors duration-200 text-[15px] font-medium inline-flex items-center gap-2 group">
                            <span class="w-1.5 h-1.5 bg-repaw-dark rounded-full opacity-40 group-hover:opacity-100 transition-opacity"></span>
                            Adopt a Cat
                        </a>
                    </li>
                    <li>
                        <a href="/pages/donatepage.php" class="text-repaw-text hover:text-repaw-dark transition-colors duration-200 text-[15px] font-medium inline-flex items-center gap-2 group">
                            <span class="w-1.5 h-1.5 bg-repaw-dark rounded-full opacity-40 group-hover:opacity-100 transition-opacity"></span>
                            Donate
                        </a>
                    </li>
                    <li>
                        <a href="/pages/success-stories.php" class="text-repaw-text hover:text-repaw-dark transition-colors duration-200 text-[15px] font-medium inline-flex items-center gap-2 group">
                            <span class="w-1.5 h-1.5 bg-repaw-dark rounded-full opacity-40 group-hover:opacity-100 transition-opacity"></span>
                            Success Stories
                        </a>
                    </li>
                    <li>
                        <a href="/pages/volunteer.php" class="text-repaw-text hover:text-repaw-dark transition-colors duration-200 text-[15px] font-medium inline-flex items-center gap-2 group">
                            <span class="w-1.5 h-1.5 bg-repaw-dark rounded-full opacity-40 group-hover:opacity-100 transition-opacity"></span>
                            Volunteer
                        </a>
                    </li>
                    <li>
                        <a href="/pages/news.php" class="text-repaw-text hover:text-repaw-dark transition-colors duration-200 text-[15px] font-medium inline-flex items-center gap-2 group">
                            <span class="w-1.5 h-1.5 bg-repaw-dark rounded-full opacity-40 group-hover:opacity-100 transition-opacity"></span>
                            News
                        </a>
                    </li>
                </ul>
            </div>

            <!-- About Us -->
            <div class="lg:col-span-1">
                <h3 class="font-serif text-xl font-semibold text-repaw-dark mb-5 relative inline-block">
                    About Us
                    <span class="absolute -bottom-1 left-0 w-10 h-[3px] bg-repaw-dark rounded-full"></span>
                </h3>
                <p class="text-repaw-text/90 text-sm leading-relaxed text-justify">
                    Welcome to RePaw City, your go-to pet adoption website based in the Philippines! We are a team of passionate animal lovers who are committed to helping pets find their forever homes.
                </p>
                <p class="text-repaw-text/90 text-sm leading-relaxed text-justify mt-3">
                    At RePaw City, we believe that every pet deserves a loving home and a chance to live a happy life. We work tirelessly to connect adoptable pets with loving families who can provide them with the care and attention they need.
                </p>
            </div>

            <!-- Address -->
            <div>
                <h3 class="font-serif text-xl font-semibold text-repaw-dark mb-5 relative inline-block">
                    Address
                    <span class="absolute -bottom-1 left-0 w-10 h-[3px] bg-repaw-dark rounded-full"></span>
                </h3>
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-location-dot text-repaw-dark mt-1 text-lg"></i>
                    <p class="text-repaw-text/90 text-sm leading-relaxed">
                        #135 Purok 3, Balsik,<br>
                        Hermosa, Bataan,<br>
                        Philippines 2111
                    </p>
                </div>
                <a href="https://goo.gl/maps/CxUucZAbZ6mvNXRG7" target="_blank" rel="noopener noreferrer"
                   class="mt-4 inline-flex items-center gap-2 text-repaw-dark font-semibold text-sm hover:underline underline-offset-4 transition-colors duration-200">
                    <i class="fa-solid fa-map-location-dot"></i>
                    View Google Maps
                </a>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-repaw-dark/20 my-8"></div>

        <!-- Bottom section: Contact + Information + Social -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 items-start">
            <!-- Contact -->
            <div>
                <h3 class="font-serif text-lg font-semibold text-repaw-dark mb-4">Contact</h3>
                <div class="space-y-2.5">
                    <p class="flex items-center gap-3 text-repaw-text/90 text-sm">
                        <i class="fa-solid fa-phone text-repaw-dark"></i>
                        +63 923 4897 632
                    </p>
                    <p class="flex items-center gap-3 text-repaw-text/90 text-sm">
                        <i class="fa-solid fa-envelope text-repaw-dark"></i>
                        repawcity@gmail.com
                    </p>
                </div>
            </div>

            <!-- Information -->
            <div>
                <h3 class="font-serif text-lg font-semibold text-repaw-dark mb-4">Information</h3>
                <ul class="space-y-2.5">
                    <li>
                        <a href="/pages/mission.php" class="text-repaw-text/90 hover:text-repaw-dark transition-colors duration-200 text-sm inline-flex items-center gap-2 group">
                            <i class="fa-solid fa-chevron-right text-xs text-repaw-dark/50 group-hover:text-repaw-dark transition-colors"></i>
                            Mission
                        </a>
                    </li>
                    <li>
                        <a href="/pages/FAQ.php" class="text-repaw-text/90 hover:text-repaw-dark transition-colors duration-200 text-sm inline-flex items-center gap-2 group">
                            <i class="fa-solid fa-chevron-right text-xs text-repaw-dark/50 group-hover:text-repaw-dark transition-colors"></i>
                            Assistance
                        </a>
                    </li>
                    <li>
                        <a href="/pages/privacy-policy.php" class="text-repaw-text/90 hover:text-repaw-dark transition-colors duration-200 text-sm inline-flex items-center gap-2 group">
                            <i class="fa-solid fa-chevron-right text-xs text-repaw-dark/50 group-hover:text-repaw-dark transition-colors"></i>
                            Privacy Policy
                        </a>
                    </li>
                    <li>
                        <a href="/pages/terms-of-use.php" class="text-repaw-text/90 hover:text-repaw-dark transition-colors duration-200 text-sm inline-flex items-center gap-2 group">
                            <i class="fa-solid fa-chevron-right text-xs text-repaw-dark/50 group-hover:text-repaw-dark transition-colors"></i>
                            Terms of Use
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Social -->
            <div>
                <h3 class="font-serif text-lg font-semibold text-repaw-dark mb-4">Social</h3>
                <div class="flex gap-3">
                    <a href="#" aria-label="Facebook"
                       class="w-11 h-11 rounded-full bg-repaw-dark text-repaw-accent flex items-center justify-center hover:bg-repaw-text hover:scale-110 transition-all duration-300 shadow-md">
                        <i class="fa-brands fa-facebook-f text-lg"></i>
                    </a>
                    <a href="#" aria-label="Instagram"
                       class="w-11 h-11 rounded-full bg-repaw-dark text-repaw-accent flex items-center justify-center hover:bg-repaw-text hover:scale-110 transition-all duration-300 shadow-md">
                        <i class="fa-brands fa-instagram text-lg"></i>
                    </a>
                    <a href="#" aria-label="TikTok"
                       class="w-11 h-11 rounded-full bg-repaw-dark text-repaw-accent flex items-center justify-center hover:bg-repaw-text hover:scale-110 transition-all duration-300 shadow-md">
                        <i class="fa-brands fa-tiktok text-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom bar -->
    <?php
    $siteName = 'RePaw City';
    $year = date('Y');
    $legalLinks = [
        ['label' => 'Privacy Policy', 'href' => '/pages/privacy-policy.php'],
        ['label' => 'Terms of Use',   'href' => '/pages/terms-of-use.php'],
    ];
    ?>
    <div class="bg-repaw-dark text-white/80">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-5 flex flex-col sm:flex-row items-center justify-center text-center gap-3">
            <p class="text-sm">
                &copy; <?php echo htmlspecialchars($year, ENT_HTML5, 'UTF-8'); ?> <?php echo htmlspecialchars($siteName, ENT_HTML5, 'UTF-8'); ?>. All rights reserved.
            </p>

            <?php if (!empty($legalLinks)): ?>
                <nav class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-sm" aria-label="Legal">
                    <?php foreach ($legalLinks as $link): ?>
                        <a href="<?php echo htmlspecialchars($link['href'], ENT_HTML5, 'UTF-8'); ?>"
                           class="text-white/80 hover:text-white transition-colors duration-200">
                            <?php echo htmlspecialchars($link['label'], ENT_HTML5, 'UTF-8'); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</footer>

<!-- Scroll to top button -->
<a href="#top" id="scroll-to-top"
   class="fixed bottom-6 right-6 w-12 h-12 rounded-full bg-repaw-dark text-white flex items-center justify-center shadow-lg hover:bg-repaw-text hover:scale-110 transition-all duration-300 z-50 opacity-0 pointer-events-none"
   aria-label="Scroll to top">
    <i class="fa-solid fa-arrow-up text-lg"></i>
</a>

<script>
    // Scroll to top button visibility
    document.addEventListener('DOMContentLoaded', function () {
        const scrollBtn = document.getElementById('scroll-to-top');
        if (!scrollBtn) return;

        window.addEventListener('scroll', function () {
            if (window.scrollY > 300) {
                scrollBtn.classList.remove('opacity-0', 'pointer-events-none');
                scrollBtn.classList.add('opacity-100', 'pointer-events-auto');
            } else {
                scrollBtn.classList.add('opacity-0', 'pointer-events-none');
                scrollBtn.classList.remove('opacity-100', 'pointer-events-auto');
            }
        });

        scrollBtn.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>