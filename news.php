<?php require './function/config.php' ?>
<?php
session_start(); // Add this line to start the session
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rePaw City</title>
    <link rel="stylesheet" href="css/news.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Acme">
    <script src="https://kit.fontawesome.com/98b545cfa6.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php include './function/navbar.php' ?>

    <section class="home">
        <div class="top">
            <div class="title">
                <h2>BLOGS, LATEST NEWS, AND UPDATES</h2> <br>
            </div>
            <div class="tips">
                <h1>PET CARE TIPS</h1>
                <p>How to Ensure a Happy and Healthy Life for Your New Companion: <br>
                    Discover essential tips for providing optimal care and well- being to your newly adopted pet,
                    including nutrition, exercise, grooming, and more
                </p>
                <br>
                <div class="slideshow-container">
                    <img src=".\image\news\pet-tips.jpg" alt="Image 1">
                    <img src=".\image\news\pet-tips2.jpg" alt="Image 2">
                    <img src=".\image\news\pet-tips3.jpg" alt="Image 3">
                </div>
            </div>
            <div class="news">
                <div class="headline">
                    <div class="img">
                        <img src=".\image\news\headline.jpg" alt="Image 1">
                    </div>
                    <div class="details">
                        <h1>New Adoption Center Opening Soon!</h1>
                        <p>We are excited to announce the upcoming opening of our new adoption center, which will
                            provide a spacious and comfortable environment for animals in search of their forever homes.
                            Stay tuned for the official launch date!"
                        </p>
                    </div>

                </div>
                <div class="card-container latest-news">
                    <div class="latest card">
                        <div class="img">
                            <img src=".\image\news\latest.jpg" alt="Image 1">
                        </div>
                        <div class="details">
                            <h1>Coco finally meets his new family!</h1>
                            <br>
                            <p>Meet coco, the dog who found her happily ever after! Backthen, Coco was stray found
                                wandering the streets, and she got rescued one.. <span>See more.</span>
                            </p>
                        </div>
                        <p class="date"><span>Just now</span></p>
                    </div>
                    <div class="latest2 card">
                        <div class="img">
                            <img src=".\image\news\latest2.jpg" alt="Image 1">
                        </div>
                        <div class="details">
                            <h1>Community Comes Together to Save Abandoned Kittens</h1>
                            <br>
                            <p>Twenty cats, rescued from a recent hoarding situation, will be available for adoption
                                starting today in the City of Mabalacat. <span>See more.</span>
                            </p>
                        </div>
                        <p class="date"><span>5 hrs 20 mins</span></p>
                    </div>
                    <div class="latest3 card">
                        <div class="img">
                            <img src=".\image\news\latest3.jpg" alt="Image 1">
                        </div>
                        <div class="details">
                            <h1>Experience of being a volunteer</h1>
                            <br>
                            <p>Sarah is one of our exceptional volunteers,
                                shares her experience of fostering animals
                                and the joy she feels in seeing them
                                flourish <span>See more.</span>
                            </p>
                        </div>
                        <p class="date"><span>12 hrs ago</span></p>
                    </div>
                </div>

            </div>


        </div>
    </section>

    <?php include './function/footer.php' ?>


    <script>
        var slideIndex = 0;
        var slides = document.getElementsByClassName("slideshow-container")[0].getElementsByTagName("img");

        function showSlides() {
            for (var i = 0; i < slides.length; i++) {
                slides[i].style.opacity = 0;
            }

            slideIndex++;
            if (slideIndex > slides.length) {
                slideIndex = 1;
            }

            slides[slideIndex - 1].style.opacity = 1;

            setTimeout(showSlides, 4000); // Delay between slides (2 seconds)
        }

        showSlides();
    </script>
</body>

</html>