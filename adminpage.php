<?php
require './function/config.php';

if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $breed = $_POST["breed"];
    $sex = $_POST["sex"];
    $weight = $_POST["weight"];
    $age = $_POST["age"];
    $about = $_POST["about"];

    if ($_FILES["image"]["error"] === 4) {
        echo "<script> alert('Image Does Not Exist'); </script>";
    } else {
        $filename = $_FILES["image"]["name"];
        $filesize = $_FILES["image"]["size"];
        $tmpName = $_FILES["image"]["tmp_name"];

        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = explode('.', $filename);
        $imageExtension = strtolower(end($imageExtension));
        if (!in_array($imageExtension, $validImageExtension)) {
            echo "<script> alert('Invalid Image Extension'); </script>";
        } elseif ($filesize > 1000000) {
            echo "<script> alert('Image Size Is Too Large'); </script>";
        } else {
            $newImageName = uniqid();
            $newImageName .= '.' . $imageExtension;

            move_uploaded_file($tmpName, 'upload/' . $newImageName);
            $query = "INSERT INTO pets (name,breed,sex,weight,age,about,image) VALUES('$name' , '$breed' , '$sex' , '$weight' , '$age', '$about', '$newImageName')";
            mysqli_query($conn, $query);
            echo "
                <script> 
                    alert('Successfully Added'); 
                    document.location.href = 'adminpage.php';
                </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Acme">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sigmar">
    <script src="https://kit.fontawesome.com/98b545cfa6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom CSS to remove text decoration */
        a,
        .form-control {
            text-decoration: none !important;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a href="index.php" class="logo"><img src="./image/logo (1).png" class="img-logo"></a>
        <a href="home.php" class="list a">Logout</a>
    </nav>
    <div class="setting">
        <div class="sidebar">
            <a href="adminpage.php" class="menu"><i class="fa-solid fa-paw"></i> Add Pets</a>
            <a href="admin-manage-pets.php" class="menu2"><i class="fa-solid fa-paw"></i> Manage Pets</a>
            <a href="admin-manage-featured.php" class="menu2"><i class="fa-solid fa-paw"></i> Modify Featured Image</a>
        </div>
        <div class="main">

            <div class="container mt-1">
                <h1>Pet Form</h1>
                <form action="#" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Pet Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="breed">Breed:</label>
                        <input type="text" class="form-control" id="breed" name="breed" required>
                    </div>
                    <div class="form-group">
                        <label for="sex">Sex:</label>
                        <select class="form-control" id="sex" name="sex" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="weight">Weight:</label>
                        <input type="text" class="form-control" id="weight" name="weight" required>
                    </div>
                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="text" class="form-control" id="age" name="age" required>
                    </div>
                    <div class="form-group">
                        <label for="about">About:</label>
                        <textarea class="form-control" id="about" name="about" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Image:</label>
                        <input type="file" class="form-control-file" id="image" name="image" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>