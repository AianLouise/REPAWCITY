<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rePaw City</title>
    <link rel="stylesheet" href="css/signuppage.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Acme">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sigmar">
    <script src="https://kit.fontawesome.com/98b545cfa6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>

<body>
    <div class="signup">
        <div class="signup-image">
            <img src="./image/loginbg.jpg" alt="">
        </div>
        <a href="index.php"><img src="./image/logo (1).png" class="logo"></a>
        <div class="content">
            <h1>Create an Account</h1>
            <p>Let's get started!</p>
        </div>

        <form class="signup-form" name="signup" action="" method="post" onsubmit="return loginValidation()" autocomplete="off">
            <div class="input-container">
                <i class="fa-regular fa-user icon"></i>
                <input class="input-field" type="text" placeholder="First Name" name="fname" id="fname">
            </div>
            <div class="input-container">
            <i class="fa-regular fa-user icon"></i>
                <input class="input-field" type="text" placeholder="Last Name" name="lname" id="lname">
            </div>
            <div class="input-container">
                <i class="fa-regular fa-user icon"></i>
                <input class="input-field" type="text" placeholder="Username" name="username" id="username">
            </div>
            <div class="input-container">
            <i class="fa-solid fa-envelope icon"></i>
                <input class="input-field" type="email" placeholder="Email" name="email" id="email">
            </div>
            <div class="input-container">
                <i class="fa-solid fa-lock icon"></i>
                <input class="input-field" type="text" placeholder="Password" name="password2" id="password">
            </div>
            <div class="input-container">
                <i class="fa-solid fa-lock icon"></i>
                <input class="input-field" type="text" placeholder="Confirm Password" name="password3" id="password">
            </div>
            <input type="submit" name="submit" value="Sign Up" class="signupbtn">
            <a href="loginpage.php" class="account2"><p>Already have an Account?<br>Log in</p></a>
        </form>

    </div>
    <script src="./script/script.js"></script>
</body>

</html>