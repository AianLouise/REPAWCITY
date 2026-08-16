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
    <title>Success Stories — rePaw City</title>
    <meta name="description" content="Heartwarming stories of shelter pets finding forever homes.">
</head>

<body class="font-sans bg-repaw-bg text-repaw-text antialiased">

    <?php include '../includes/navbar.php' ?>

    <main id="top">
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-repaw-bg/90 via-repaw-bg/70 to-repaw-accent/40 pointer-events-none"></div>
            <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-16 lg:py-20 text-center">
                <h1 class="font-serif text-4xl sm:text-5xl font-bold text-repaw-dark">From Strays to Stars</h1>
                <p class="mt-4 text-lg text-repaw-text/90 max-w-2xl mx-auto">Heartwarming stories of shelter pets finding forever homes with the help of rePaw City!</p>
                <p class="mt-2 text-repaw-text/80">Have a success story of your own? Share it here!</p>
            </div>
        </section>

        <section class="max-w-5xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20 space-y-16">
            <!-- Lucky -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div class="rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm">
                    <img src="../image/Success Stories/img1.jpg" alt="Lucky" class="w-full aspect-[4/3] object-cover">
                </div>
                <div class="space-y-6">
                    <div class="bg-white/70 rounded-3xl p-7 border border-repaw-hover/40 shadow-sm">
                        <h2 class="font-serif text-2xl font-bold text-repaw-dark mb-3">"The Lucky Stray"</h2>
                        <p class="text-repaw-text/90 leading-relaxed">In a bustling city, there was a stray dog named Lucky. Lucky was always on the streets, searching for food and shelter. One rainy day, he stumbled upon a pet shelter where the staff took him in, providing him with food, a bath, and a comfortable bed. They took care of him until he was back in good condition. Lucky slowly adjusted to his new surroundings, grateful for the kindness shown by the staff.</p>
                    </div>
                    <div class="bg-white/70 rounded-3xl p-7 border border-repaw-hover/40 shadow-sm">
                        <h2 class="font-serif text-2xl font-bold text-repaw-dark mb-3">Lucky</h2>
                        <p class="text-repaw-text/90 leading-relaxed">He started to play with other dogs out there, making a lot of pawfriends! As time passed, he found a loving family who adopted him, offering a forever home. Now, Lucky spends his days playing with his new family, spreading happiness wherever he goes.</p>
                    </div>
                </div>
            </div>

            <!-- Bella -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                <div class="lg:col-span-1 rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm">
                    <img src="../image/Success Stories/img2.jpg" alt="Bella" class="w-full aspect-square object-cover">
                    <h2 class="font-serif text-xl font-bold text-repaw-dark text-center py-3">Bella</h2>
                </div>
                <div class="lg:col-span-2 bg-white/70 rounded-3xl p-7 border border-repaw-hover/40 shadow-sm">
                    <h2 class="font-serif text-2xl font-bold text-repaw-dark mb-3">"Rebuilding Trust"</h2>
                    <p class="text-repaw-text/90 leading-relaxed">Bella, a once-beloved cat, found herself in a pet shelter after her owner passed away. Confused and heartbroken, Bella became wary of humans. The shelter staff understood her trauma and patiently worked to rebuild her trust. Through gentle interactions, soft-spoken words, and consistent care, Bella began to open up. Slowly, Bella began to trust humans again, purring with delight whenever approached. Then, a kind-hearted woman named Emily visited the shelter and instantly fell in love with Bella's gentle nature. Adopting her, Emily provided Bella with a forever home filled with warmth and affection. Bella now spends her days curled up on Emily's lap, grateful for the second chance at a happy life.</p>
                </div>
            </div>

            <!-- Max -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                <div class="lg:col-span-2 bg-white/70 rounded-3xl p-7 border border-repaw-hover/40 shadow-sm">
                    <h2 class="font-serif text-2xl font-bold text-repaw-dark mb-3">"From Fear to Friendship"</h2>
                    <p class="text-repaw-text/90 leading-relaxed">Meet Max, a timid and frightened dog rescued from an abusive situation. Upon arriving at the pet shelter, Max was terrified of everything and everyone around him. The patient shelter staff worked tirelessly to help him overcome his fears, introducing him to friendly dogs and providing a safe space for healing. Over time, Max's trust in humans grew, his tail wagging in delight upon their approach. One day, a loving couple visited the shelter and instantly connected with Max's gentle eyes.</p>
                    <p class="mt-4 text-repaw-text/90 leading-relaxed">They made the decision to adopt him, promising the love and care he deserved. Today, Max is a happy and confident dog, enjoying long walks and endless belly rubs with his new family.</p>
                </div>
                <div class="lg:col-span-1 rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm">
                    <img src="../image/Success Stories/img3.jpg" alt="Max" class="w-full aspect-square object-cover">
                    <h2 class="font-serif text-xl font-bold text-repaw-dark text-center py-3">Max</h2>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php' ?>

</body>

</html>
