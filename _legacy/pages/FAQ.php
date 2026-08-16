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
    <title>FAQ — rePaw City</title>
    <meta name="description" content="Frequently asked questions about rePaw City.">
</head>

<body class="font-sans bg-repaw-bg text-repaw-text antialiased">

    <?php include '../includes/navbar.php' ?>

    <main id="top">
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-repaw-bg/90 via-repaw-bg/70 to-repaw-accent/40 pointer-events-none"></div>
            <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-16 lg:py-20 text-center">
                <h1 class="font-serif text-4xl sm:text-5xl font-bold text-repaw-dark">Frequently Asked Questions</h1>
                <p class="mt-4 text-lg text-repaw-text/90 max-w-2xl mx-auto">Welcome to the FAQ section for our pet shelter! Below are answers to some commonly asked questions.</p>
            </div>
        </section>

        <section class="max-w-3xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
            <div class="space-y-4">
                <div class="faq-item bg-white/70 rounded-2xl border border-repaw-hover/40 shadow-sm overflow-hidden">
                    <h3 class="flex items-center justify-between gap-4 cursor-pointer px-6 py-5 font-serif text-lg font-semibold text-repaw-dark">
                        What is the purpose of rePaw City?
                        <span class="mui-icon text-2xl transition-transform faq-caret">expand_more</span>
                    </h3>
                    <div class="answer px-6 pb-5 text-repaw-text/90 leading-relaxed" style="display:none;">
                        rePaw City is dedicated to providing a safe and caring environment for abandoned, neglected, or
                        surrendered pets. Our main goal is to rehabilitate and rehome these animals, ensuring they find
                        loving and permanent homes.
                    </div>
                </div>

                <div class="faq-item bg-white/70 rounded-2xl border border-repaw-hover/40 shadow-sm overflow-hidden">
                    <h3 class="flex items-center justify-between gap-4 cursor-pointer px-6 py-5 font-serif text-lg font-semibold text-repaw-dark">
                        How can I adopt a pet from your shelter?
                        <span class="mui-icon text-2xl transition-transform faq-caret">expand_more</span>
                    </h3>
                    <div class="answer px-6 pb-5 text-repaw-text/90 leading-relaxed" style="display:none;">
                        To adopt a pet from our shelter, please book an appointment first or check our website for
                        available animals.
                    </div>
                </div>

                <div class="faq-item bg-white/70 rounded-2xl border border-repaw-hover/40 shadow-sm overflow-hidden">
                    <h3 class="flex items-center justify-between gap-4 cursor-pointer px-6 py-5 font-serif text-lg font-semibold text-repaw-dark">
                        Do you have a policy for screening potential adopters?
                        <span class="mui-icon text-2xl transition-transform faq-caret">expand_more</span>
                    </h3>
                    <div class="answer px-6 pb-5 text-repaw-text/90 leading-relaxed" style="display:none;">
                        Yes, we have a screening process to ensure that our animals are placed in suitable and loving
                        homes. The process may involve an application, an interview, reference checks, and sometimes a
                        home visit. We aim to match the needs and personalities of our animals with the lifestyle and
                        capabilities of potential adopters.
                    </div>
                </div>

                <div class="faq-item bg-white/70 rounded-2xl border border-repaw-hover/40 shadow-sm overflow-hidden">
                    <h3 class="flex items-center justify-between gap-4 cursor-pointer px-6 py-5 font-serif text-lg font-semibold text-repaw-dark">
                        Can I surrender my pet to your shelter?
                        <span class="mui-icon text-2xl transition-transform faq-caret">expand_more</span>
                    </h3>
                    <div class="answer px-6 pb-5 text-repaw-text/90 leading-relaxed" style="display:none;">
                        Yes, we accept owner surrenders, but we encourage you to contact us in advance to discuss your
                        situation. Surrendering a pet is a serious decision, and we want to ensure we have the necessary
                        resources to accommodate your pet's needs.
                    </div>
                </div>

                <div class="faq-item bg-white/70 rounded-2xl border border-repaw-hover/40 shadow-sm overflow-hidden">
                    <h3 class="flex items-center justify-between gap-4 cursor-pointer px-6 py-5 font-serif text-lg font-semibold text-repaw-dark">
                        What happens if a pet is not adopted?
                        <span class="mui-icon text-2xl transition-transform faq-caret">expand_more</span>
                    </h3>
                    <div class="answer px-6 pb-5 text-repaw-text/90 leading-relaxed" style="display:none;">
                        If a pet is not adopted within a reasonable timeframe, we continue to provide them with care and
                        enrichment while actively seeking a suitable home. We do not have a time limit for how long an
                        animal can stay with us. Our priority is finding the best match for each pet, even if it takes
                        longer.
                    </div>
                </div>

                <div class="faq-item bg-white/70 rounded-2xl border border-repaw-hover/40 shadow-sm overflow-hidden">
                    <h3 class="flex items-center justify-between gap-4 cursor-pointer px-6 py-5 font-serif text-lg font-semibold text-repaw-dark">
                        Can I volunteer at your shelter?
                        <span class="mui-icon text-2xl transition-transform faq-caret">expand_more</span>
                    </h3>
                    <div class="answer px-6 pb-5 text-repaw-text/90 leading-relaxed" style="display:none;">
                        Yes, we welcome volunteers who are passionate about animal welfare. Volunteers play a crucial
                        role in providing care and socialization to our animals. Please contact our shelter or book an
                        appointment to learn more about our volunteer opportunities and requirements.
                    </div>
                </div>

                <div class="faq-item bg-white/70 rounded-2xl border border-repaw-hover/40 shadow-sm overflow-hidden">
                    <h3 class="flex items-center justify-between gap-4 cursor-pointer px-6 py-5 font-serif text-lg font-semibold text-repaw-dark">
                        How can I support your pet shelter if I cannot adopt or volunteer?
                        <span class="mui-icon text-2xl transition-transform faq-caret">expand_more</span>
                    </h3>
                    <div class="answer px-6 pb-5 text-repaw-text/90 leading-relaxed" style="display:none;">
                        There are several ways to support our shelter. You can consider making a monetary donation,
                        donating pet supplies or food, sponsoring a specific animal, or organizing fundraising events.
                        Sharing our social media posts and spreading awareness about our shelter can also make a
                        significant impact.
                    </div>
                </div>
            </div>

            <p class="mt-8 text-center text-repaw-text/80">We hope these answers have been helpful. If you have any further questions, feel free to ask.</p>
        </section>
    </main>

    <?php include '../includes/footer.php' ?>

    <script>
        window.addEventListener('DOMContentLoaded', function () {
            var faqItems = document.getElementsByClassName('faq-item');
            for (var i = 0; i < faqItems.length; i++) {
                faqItems[i].addEventListener('click', toggleAnswer);
            }

            function toggleAnswer() {
                var answer = this.querySelector('.answer');
                var caret = this.querySelector('.faq-caret');
                var isActive = this.classList.contains('active');

                for (var i = 0; i < faqItems.length; i++) {
                    faqItems[i].classList.remove('active');
                    faqItems[i].querySelector('.answer').style.display = 'none';
                    var c = faqItems[i].querySelector('.faq-caret');
                    if (c) c.style.transform = 'rotate(0deg)';
                }

                if (!isActive) {
                    this.classList.add('active');
                    answer.style.display = 'block';
                    if (caret) caret.style.transform = 'rotate(180deg)';
                }
            }
        });
    </script>
</body>

</html>
