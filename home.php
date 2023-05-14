<?php require './function/config.php' ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rePaw City</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Acme">
    <script src="https://kit.fontawesome.com/98b545cfa6.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php include './function/navbar.php' ?>

    <section class="home">
        <div class="top">
            <img src="./image/paw.png" class="paw-bg">
            <img src="./image/paw.png" class="paw-bg2">
            <img src="./image/pets.png" class="paw-bg3">
            <h1 class="title">They are waiting for<br>YOU!</h1>
        </div>
        <div class="pets">
            <div class="menu">
                <a href="home.php" class="find-dog"><i class="fa-solid fa-dog fa-lg"></i> Find a Dog</a>
                <a href="home.php" class="find-cat"><i class="fa-solid fa-cat fa-lg"></i> Find a Cat</a>
                <a href="home.php" class="donate"><i class="fa-sharp fa-solid fa-hand-holding-heart fa-lg"></i>
                    Donate</a>
                <a href="home.php" class="volunteer"><i class="fa-solid fa-handshake-angle fa-lg"></i> Volunteer</a>
            </div>
            <h2 class="adopt-title">Pets Available for Adoption</h2>
            <a href="">
                <p class="see-more">See More <i class="fa-sharp fa-solid fa-arrow-right"></i></p>
            </a>
        </div>
    </section>
    <section class="hero">
        <div class="adoption">
            <!-- Feature 1 -->
            <?php
            $i = 1;
            $rows = mysqli_query($conn, "SELECT * FROM pets WHERE is_featured = 1");
            foreach ($rows as $row):
                ?>
                <a href="adoptprofile.php?id=<?php echo $row['pets_id']; ?>">
                    <div class="feature card1">
                        <img src="./upload/<?php echo $row['image']; ?>" alt="">
                        <h4><b>
                                <?php echo $row["name"]; ?>
                            </b></h4>
                    </div>
                </a>
            <?php endforeach; ?>
            <!-- Feature 2 -->
            <?php
            $i = 1;
            $rows = mysqli_query($conn, "SELECT * FROM pets WHERE is_featured = 2");
            foreach ($rows as $row):
                ?>
                <a href="adoptprofile.php?id=<?php echo $row['pets_id']; ?>">
                    <div class="feature card2">
                        <img src="./upload/<?php echo $row['image']; ?>" alt="">
                        <h4><b>
                                <?php echo $row["name"]; ?>
                            </b></h4>
                    </div>
                </a>
            <?php endforeach; ?>
            <!-- Feature 3 -->
            <?php
            $i = 1;
            $rows = mysqli_query($conn, "SELECT * FROM pets WHERE is_featured = 3");
            foreach ($rows as $row):
                ?>
                <a href="adoptprofile.php?id=<?php echo $row['pets_id']; ?>">
                    <div class="feature card3">
                        <img src="./upload/<?php echo $row['image']; ?>" alt="">
                        <h4><b>
                                <?php echo $row["name"]; ?>
                            </b></h4>
                    </div>
                </a>
            <?php endforeach; ?>
            <!-- Feature 4 -->
            <?php
            $i = 1;
            $rows = mysqli_query($conn, "SELECT * FROM pets WHERE is_featured = 4");
            foreach ($rows as $row):
                ?>
                <a href="adoptprofile.php?id=<?php echo $row['pets_id']; ?>">
                    <div class="feature card4">
                        <img src="./upload/<?php echo $row['image']; ?>" alt="">
                        <h4><b>
                                <?php echo $row["name"]; ?>
                            </b></h4>
                    </div>
                </a>
            <?php endforeach; ?>

        </div>
    </section>
    <section class="donate-container">
        <img class="donate-bg" src="./image/donatebg.png" alt="">
        <h1 class="donate-title">Planning to Donate?</h1>
        <p class="donate-info info1"><i class="fa-solid fa-heart" style="color: #ffffff;"></i> Your donation will help
            us provide food, shelter, and medical care for animals in need.</p>
        <p class="donate-info info2"><i class="fa-solid fa-heart" style="color: #ffffff;"></i> With your support, we can
            rescue more animals and give them a second chance at life.</p>
        <p class="donate-info info3"><i class="fa-solid fa-heart" style="color: #ffffff;"></i> Your contribution will
            help us cover the costs of vaccinations, spaying/neutering, and other essential medical treatments for
            animals in our care.</p>
        <p class="donate-info info4"><i class="fa-solid fa-heart" style="color: #ffffff;"></i> By making a donation, you
            can help us find loving homes for more animals and reduce the number of homeless pets in our community.</p>
        <p class="donate-info info5"><i class="fa-solid fa-heart" style="color: #ffffff;"></i> Your generosity will help
            us support our foster program, which provides temporary homes for animals until they find their forever
            families.</p>
        <p class="donate-info info6"><i class="fa-solid fa-heart" style="color: #ffffff;"></i> Donations are vital to
            our mission of promoting responsible pet ownership and educating the public about the importance of animal
            welfare.</p>
        <a href="#" class="donate-now">
            <p><i class="fa-sharp fa-solid fa-hand-holding-heart fa-lg"></i> Donate Now</p>
        </a>
    </section>

    <?php include './function/footer.php' ?>
</body>

</html>