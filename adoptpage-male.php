<?php
session_start(); // Add this line to start the session
require './function/config.php';
include './function/navbar.php';

// Retrieve the pet data from the database
// Assuming you have a database connection and the necessary query here
$pet_data = []; // Replace this with the actual fetched pet data

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rePaw City</title>
    <link rel="stylesheet" href="css/adoptpage.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Acme">
    <script src="https://kit.fontawesome.com/98b545cfa6.js" crossorigin="anonymous"></script>
</head>

<body>
    <section class="home">
        <div class="top">
            <img src="./image/doggo.png" class="paw-bg">
            <img src="./image/catto.png" class="paw-bg2">
            <h1 class="title">Adopt</h1>
            <p class="content">All of our cats and dogs can be seen by appointment only. We are open Tuesday, Friday and
                Saturday 12pm-3pm.</p>
            <a href="" class="book-app btn">Book Appointment</a>
        </div>
        <div class="pets">
            <h1 class="adopt-title">MEET OUR DOGS</h1>
            <p class="sort">Sort by:</p>
            <div class="menu">
                <a href="adoptpage-male.php#adopt-page" class="male">Male</a>
                <a href="adoptpage-female.php#adopt-page" class="female">Female</a>
                <a href="#" class="short">Shortest Stay</a>
                <a href="#" class="long">Longest Stay</a>
            </div>
        </div>
    </section>
    <section class="hero" id="#adopt-page">
        <div class="adoption">
            <div class="card-container">
                <?php
                // Fetch the pet data from the database and loop through the results
                // Modify the query based on your database schema
                $query = "SELECT * FROM pets WHERE sex = 'Male'";

                $result = mysqli_query($conn, $query);

                // Check if any pets are found in the database
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Extract the pet details from the row
                        $name = $row['name'];
                        $sex = $row['sex'];
                        $age = $row['age'];
                        $image = $row['image'];

                        // Generate the HTML for the pet card
                        echo '<div class="card feature">';
                        echo '<img src="./upload/' . $image . '" alt="">';
                        echo '<h4><b>' . $name . '</b></h4>';
                        echo '<p class="gender">' . $sex . '</p>';
                        echo '<div class="vl2"></div>';
                        echo '<p class="age">' . $age . '</p>';
                        echo '</div>';
                    }
                } else {
                    // Display a message if no pets are found in the database
                    echo 'No pets found.';
                }

                // Free the result variable
                mysqli_free_result($result);
                ?>
            </div>
            <div id="additionalPetsContainer"></div> <!-- Container to hold additional pet cards -->

            <!-- <a href="" class="load-more btn">Load More</a> -->
        </div>
    </section>
    <?php include './function/footer.php' ?>
</body>

</html>