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
    <?php include 'navigationbar.php'?>
    
    <section class="home">
        <div class="top">
        <img src="./image/paw.png" class="paw-bg">
            <img src="./image/paw.png" class="paw-bg2">
            <img src="./image/pets.png" class="paw-bg3">
            <h1 class="title">They are waiting for<br>YOU!</h1>
        </div>
        <div class="pets">
        <h1 class="adopt-title">MEET OUR DOGS</h1>
            <p class="sort">Sort by:</p>
            <div class="menu">
                <a href="home.php" class="male">Male</a>
                <a href="home.php" class="female">Female</a>
                <a href="home.php" class="short">Shortest Stay</a>
                <a href="home.php" class="long">Longest Stay</a>
            </div>
        </div>
    </section>
    
    <?php include 'footer.php'?>
</body>
</html>