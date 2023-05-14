<?php
require './function/config.php';

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Clear the is_featured column for all records
    $clearSql = "UPDATE pets SET is_featured = 0";
    mysqli_query($conn, $clearSql);

    // Retrieve the selected IDs from the number inputs
    $featuredImage1 = $_POST['featured_image_1'];
    $featuredImage2 = $_POST['featured_image_2'];
    $featuredImage3 = $_POST['featured_image_3'];
    $featuredImage4 = $_POST['featured_image_4'];

    // Update the is_featured column for the selected records
    $sql1 = "UPDATE pets SET is_featured = 1 WHERE pets_id = '$featuredImage1'";
    $sql2 = "UPDATE pets SET is_featured = 2 WHERE pets_id = '$featuredImage2'";
    $sql3 = "UPDATE pets SET is_featured = 3 WHERE pets_id = '$featuredImage3'";
    $sql4 = "UPDATE pets SET is_featured = 4 WHERE pets_id = '$featuredImage4'";

    if (mysqli_query($conn, $sql1) && mysqli_query($conn, $sql2) && mysqli_query($conn, $sql3) && mysqli_query($conn, $sql4)) {
        echo "Records updated successfully";
    } else {
        echo "Error updating records: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/admin-featured.css">
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

        .table-container {
            max-height: 400px;
            overflow-y: scroll;
        }

        .form-row {
            margin-bottom: 1rem;
        }

        .form-row label {
            width: 80px;
        }

        .form-row input {
            width: 50px;
        }

        .table-container {
            max-height: 400px;
            overflow-y: scroll;
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
            <a href="adminpage.php" class="menu1"><i class="fa-solid fa-paw"></i> Add Pets</a>
            <a href="admin-manage-pets.php" class="menu2"><i class="fa-solid fa-paw"></i> Manage Pets</a>
            <a href="admin-manage-featured.php" class="menu2"><i class="fa-solid fa-paw"></i> Modify Featured Image</a>
        </div>
        <div class="main">
            <div class="modify-featured">
                <div class="container mt-4 table-container">
                    <h1>Database Table</h1>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Is_Featured</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require './function/config.php';

                            // Query the database table
                            $sql = "SELECT pets_id, name, image , is_featured, sex FROM pets";
                            $result = $conn->query($sql);

                            // Fetch and display the data
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $row["pets_id"]; ?>
                                        </td>
                                        <td><img src="./upload/<?php echo $row['image']; ?>" alt="" height="50"></td>
                                        <td>
                                            <?php echo $row["name"]; ?>
                                        </td>
                                        <td>
                                            <?php echo $row["is_featured"]; ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='6'>No data available</td></tr>";
                            }

                            // Close the connection
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>

                <h2>Select IDs to set as Featured Images</h2>
                <form method="POST">
                    <label for="featured_image_1">Featured Image 1:</label>
                    <input type="number" name="featured_image_1" id="featured_image_1">
                    <br>
                    <label for="featured_image_2">Featured Image 2:</label>
                    <input type="number" name="featured_image_2" id="featured_image_2">
                    <br>
                    <label for="featured_image_3">Featured Image 3:</label>
                    <input type="number" name="featured_image_3" id="featured_image_3">
                    <br>
                    <label for="featured_image_4">Featured Image 4:</label>
                    <input type="number" name="featured_image_4" id="featured_image_4">
                    <br>
                    <input type="submit" name="submit" value="Set as Featured Images">
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            // Handle click event on table rows
            $("table tbody tr").click(function () {
                // Remove the active class from other rows
                $("table tbody tr").removeClass("active");

                // Add the active class to the clicked row
                $(this).addClass("active");

                // Get the selected row's data
                var id = $(this).find("td:nth-child(1)").text().trim();
                // var image = $(this).find("td:nth-child(2) img").attr("src");
                var name = $(this).find("td:nth-child(3)").text().trim();
                var breed = $(this).find("td:nth-child(4)").text().trim();
                var sex = $(this).find("td:nth-child(5)").text().trim();
                var weight = $(this).find("td:nth-child(6)").text().trim();
                var age = $(this).find("td:nth-child(7)").text().trim();

                // Populate the input fields with the selected row data
                $("#pet-id").val(id);
                // $("#pet-image").val(image);
                $("#pet-name").val(name);
                $("#pet-breed").val(breed);
                $("#pet-sex").val(sex);
                $("#pet-weight").val(weight);
                $("#pet-age").val(age);
            });
        });
    </script>
</body>

</html>