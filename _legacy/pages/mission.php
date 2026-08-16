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
    <title>Mission — rePaw City</title>
    <meta name="description" content="Our mission, vision, and goals at rePaw City.">
</head>

<body class="font-sans bg-repaw-bg text-repaw-text antialiased">

    <?php include '../includes/navbar.php' ?>

    <main id="top">
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-repaw-bg/90 via-repaw-bg/70 to-repaw-accent/40 pointer-events-none"></div>
            <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-16 lg:py-20 text-center">
                <h1 class="font-serif text-4xl sm:text-5xl font-bold text-repaw-dark">Our Mission</h1>
            </div>
        </section>

        <section class="max-w-3xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
            <div class="bg-white/70 rounded-3xl p-8 sm:p-10 border border-repaw-hover/40 shadow-sm space-y-10">
                <div>
                    <h2 class="font-serif text-2xl font-bold text-repaw-dark mb-3">Mission</h2>
                    <p class="text-repaw-text/90 leading-relaxed">
                        The mission of our pet shelter is to provide a safe, nurturing, and loving environment for
                        animals in need. We are dedicated to rescuing and rehabilitating abandoned, abused, and
                        neglected pets, and finding them forever homes. Our primary focus is on promoting animal
                        welfare, responsible pet ownership, and reducing the number of homeless animals through
                        adoption, education, and community outreach.
                    </p>
                </div>
                <div>
                    <h2 class="font-serif text-2xl font-bold text-repaw-dark mb-3">Vision</h2>
                    <p class="text-repaw-text/90 leading-relaxed">
                        Our vision is to create a world where every animal has a loving and caring home. We strive to be
                        a leading advocate for animal welfare in our community, promoting compassion, empathy, and
                        respect for all living beings. We envision a society where every pet is treated with kindness
                        and provided with the care they deserve, resulting in a decrease in the number of animals
                        suffering from neglect or homelessness.
                    </p>
                </div>
                <div>
                    <h2 class="font-serif text-2xl font-bold text-repaw-dark mb-3">Goals</h2>
                    <ol class="list-decimal list-inside space-y-3 text-repaw-text/90 leading-relaxed">
                        <li><strong>Rescue and Rehabilitation:</strong> Our foremost goal is to rescue animals in need, provide them with necessary medical care, and rehabilitate them both physically and emotionally. We aim to give them a second chance at life and prepare them for successful adoptions.</li>
                        <li><strong>Adoption and Placement:</strong> We aim to find permanent, loving homes for our rescued animals through responsible adoption processes. Our goal is to match each animal with the most suitable family, ensuring a positive and lasting bond.</li>
                        <li><strong>Education and Outreach:</strong> We are committed to educating the community about responsible pet ownership, animal welfare, and the importance of spaying/neutering. Through workshops, seminars, and outreach programs, we strive to raise awareness and promote humane treatment of animals.</li>
                        <li><strong>Advocacy and Legislation:</strong> We strive to be a voice for animals in our community and beyond. We actively advocate for stronger animal protection laws, policies, and regulations to ensure the well-being of all animals. We aim to influence positive change at local and national levels.</li>
                        <li><strong>Volunteer and Staff Development:</strong> We value our dedicated volunteers and staff members and provide them with ongoing training and support. By fostering a positive work environment and encouraging personal growth, we can enhance our organization's effectiveness and ability to serve animals in need.</li>
                    </ol>
                    <p class="mt-4 text-repaw-text/90 leading-relaxed">
                        These goals collectively contribute to our mission and vision, guiding our efforts to make a
                        meaningful difference in the lives of animals and the community we serve.
                    </p>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php' ?>

</body>

</html>
