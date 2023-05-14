<?php
require './function/config.php';

// Check if the form is submitted
if (isset($_POST['update'])) {
    // Retrieve the data from the form
    $id = $_POST['id'];
    $name = $_POST['name'];
    $breed = $_POST['breed'];
    $sex = $_POST['sex'];
    $weight = $_POST['weight'];
    $age = $_POST['age'];

    // Update the data in the database
    $sql = "UPDATE pets SET name='$name', breed='$breed', sex='$sex', weight='$weight', age='$age' WHERE pets_id='$id'";

    if (mysqli_query($conn, $sql)) {
        echo "
        <script> 
            alert('Data updated successfully'); 
            document.location.href = 'admin-manage-pets.php';
        </script>";
    } else {
        echo "Error updating data: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>

<?php
require './function/config.php';

// Check if the form is submitted
if (isset($_POST['delete'])) {
    // Retrieve the id of the record to delete
    $id = $_POST['id'];

    // Delete the record from the database
    $sql = "DELETE FROM pets WHERE pets_id='$id'";

    if (mysqli_query($conn, $sql)) {
        echo "
        <script> 
            alert('Record deleted successfully'); 
            document.location.href = 'admin-manage-pets.php';
        </script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
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
    <link rel="stylesheet" href="css/admin-pets.css">
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

        .input-group {
            margin-bottom: 10px;
            margin-left: 15rem;
            width: 50rem;
        }

        .input-group-prepend,
        .input-group-append {
            height: 38px;
        }

        .input-group-text {
            width: 80px;
            text-align: center;
        }

        .center-align {
            text-align: left;
        }

        button.move {
            margin-left: 35rem;
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
                                <th>Breed</th>
                                <th>Sex</th>
                                <th>Weight</th>
                                <th>Age</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require './function/config.php';

                            // Query the database table
                            $sql = "SELECT pets_id, name, breed, sex, weight, age, image FROM pets";
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
                                            <?php echo $row["breed"]; ?>
                                        </td>
                                        <td>
                                            <?php echo $row["sex"]; ?>
                                        </td>
                                        <td>
                                            <?php echo $row["weight"]; ?>
                                        </td>
                                        <td>
                                            <?php echo $row["age"]; ?>
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

                <form id="selected-row-form" class="mt-4" method="POST">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Id</span>
                        </div>
                        <input type="text" class="form-control" id="pet-id" name="id" readonly>
                    </div>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Name</span>
                        </div>
                        <input type="text" class="form-control" id="pet-name" name="name">
                    </div>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Breed</span>
                        </div>
                        <input type="text" class="form-control" id="pet-breed" name="breed">

                        <div class="input-group-prepend">
                            <span class="input-group-text">Sex</span>
                        </div>
                        <input type="text" class="form-control" id="pet-sex" name="sex">
                    </div>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Weight</span>
                        </div>
                        <input type="text" class="form-control center-align" id="pet-weight" name="weight">

                        <div class="input-group-prepend">
                            <span class="input-group-text">Age</span>
                        </div>
                        <input type="text" class="form-control center-align" id="pet-age" name="age">
                    </div>

                    <div class="mt-3">
                        <button type="submit " name="update" class="btn btn-primary move"
                            id="edit-button">Update</button>
                        <button type="submit" name="delete" class="btn btn-danger" id="remove-button">Remove</button>
                    </div>
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