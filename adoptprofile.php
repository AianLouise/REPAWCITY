<?php require './function/config.php' ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rePaw City</title>
    <link rel="stylesheet" href="css/adoptprofile.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Acme">
    <script src="https://kit.fontawesome.com/98b545cfa6.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php include './function/navbar.php' ?>

    <section class="home">
        <div class="top">
            <img src="./image/doggo.png" class="paw-bg">
            <img src="./image/catto.png" class="paw-bg2">
            <h1 class="title">Adopt</h1>
            <p class="content">All of our cats and dogs can be seen by appointment only. We are open Tuesday, Friday and
                Saturday 12pm-3pm.</p>
            <a href="" class="book-app btn"><i class="fa-regular fa-calendar-check icon"
                    style="color: #ffffff;"></i>Book Appointment</a>
        </div>
        <div class="pets">
            <h1 class="adopt-title">MEET OUR DOGS</h1>
            <a href="adoptpage.php">
                <p class="back"><i class="fa-sharp fa-solid fa-arrow-left"></i> Back </p>
            </a>
        </div>
        <div class="pet">
            
            <?php
            $i = 1;
            $id = $_GET['id'];
            $rows = mysqli_query($conn, "Select * from pets where pets_id = $id")
                ?>
            <?php foreach ($rows as $row): ?>
                <div class="photo">
                    <img src="./upload/<?php echo $row["image"]; ?>" alt="">
                </div>
                <div class="pet-info">

                    <h1 class="petname">
                        <?php echo $row["name"]; ?>
                    </h1>
                    <p class="a">Breed :
                        <?php echo $row["breed"]; ?>
                    </p>
                    <p class="b">Sex :
                        <?php echo $row["sex"]; ?>
                    </p>
                    <p class="c">Weight :
                        <?php echo $row["weight"]; ?>
                    </p>
                    <p class="d">Age :
                        <?php echo $row["age"]; ?>
                    </p>
                    <h1 class="about-title">About
                        <?php echo $row["name"]; ?>:
                    </h1>
                    <p class="about">
                        <?php echo $row["about"]; ?>
                    </p>
                    <a href="" class="contact-btn btn"><i class="fa-solid fa-phone icon" style="color: #ffffff;"></i>
                        Contact us to Meet
                        <?php echo $row["name"]; ?>
                    </a>

                </div>
            <?php endforeach; ?>
        </div>


    </section>

    <?php include './function/footer.php' ?>
</body>

</html>