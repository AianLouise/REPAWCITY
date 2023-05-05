<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rePaw City</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Acme">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sigmar">
    <script src="https://kit.fontawesome.com/98b545cfa6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>

<body>
    <div class="login">
        <div class="login-image">
            <img src="./image/loginbg.jpg" alt="">
        </div>
        <a href="home.php"><img src="./image/logo (1).png" class="logo"></a>
        <div class="content">
            <h1>WELCOME!</h1>
            <p>Let's get started</p>
        </div>

        <form class="login-form" name="login" action="" method="post" onsubmit="return loginValidation()">
            <div class="input-container">
                <i class="fa-regular fa-user icon"></i>
                <input class="input-field" type="text" placeholder="Username" name="username" id="username">
            </div>
            <div class="input-container">
                <i class="fa-solid fa-lock icon"></i>
                <input class="input-field" type="password" placeholder="Password" name="password" id="password">
                <i class="fa-solid fa-eye" id="show-password"></i>
            </div>
            <a href="" class="forgot-pass"><p>Forgot Password?</p></a>
            <input type="submit" name="submit" value="Login" class="loginbtn">
            <a href="signuppage.php" class="account"><p>Don't have an Account?<br>Sign Up</p></a>
        </form>

    </div>
    <script src="./script/script.js"></script>
</body>

</html>