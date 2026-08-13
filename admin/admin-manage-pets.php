<?php
require '../includes/admin_guard.php';

// Check if the form is submitted
if (isset($_POST['update'])) {
    // Retrieve the data from the form
    $id = $_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $type = mysqli_real_escape_string($conn, $_POST["type"]);
    $breed = mysqli_real_escape_string($conn, $_POST["breed"]);
    $sex = mysqli_real_escape_string($conn, $_POST["sex"]);
    $weight = mysqli_real_escape_string($conn, $_POST["weight"]);
    $age = mysqli_real_escape_string($conn, $_POST["age"]);
    $date = mysqli_real_escape_string($conn, $_POST["date"]);
    $about = mysqli_real_escape_string($conn, $_POST["about"]);

    // Check if the image is uploaded
    if (!empty($_FILES['image']['name'])) {
        // Retrieve the image file details
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_error = $_FILES['image']['error'];

        // Check if there is no upload error
        if ($image_error === 0) {
            // Get the file extension
            $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $image_ext = strtolower($image_ext);

            // Check if the file is a valid image
            $allowed_extensions = ['jpg', 'jpeg', 'png'];
            if (in_array($image_ext, $allowed_extensions)) {
                // Generate a unique name for the image file
                $image_new_name = uniqid('image_') . '.' . $image_ext;

                // Upload the image to the server
                $image_destination = '../upload/' . $image_new_name;
                move_uploaded_file($image_tmp, $image_destination);

                // Update the image in the database
                $sql = "UPDATE pets SET name='$name', type='$type', breed='$breed', sex='$sex', weight='$weight', age='$age', date='$date', about='$about', image='$image_new_name' WHERE pets_id='$id'";
            } else {
                echo "Invalid image format. Only JPG, JPEG, and PNG files are allowed.";
                exit;
            }
        } else {
            echo "Error uploading image: " . $image_error;
            exit;
        }
    } else {
        // Update the data in the database without changing the image
        $sql = "UPDATE pets SET name='$name', type='$type', breed='$breed', sex='$sex', weight='$weight', age='$age', date='$date', about='$about' WHERE pets_id='$id'";
    }

    // Perform the database query
    if (mysqli_query($conn, $sql)) {
        echo "
        <script> 
            alert('Data updated successfully'); 
            window.location.href = 'admin-manage-pets.php';
        </script>";
    } else {
        echo "Error updating data: " . mysqli_error($conn);
    }
}

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Pets — rePaw City Admin</title>
    <?php require '../includes/admin_head.php'; ?>

    <style>
        .table-container {
            max-height: 400px;
            overflow-y: scroll;
        }
    </style>
</head>

<body class="font-sans bg-repaw-bg text-repaw-text antialiased">
    <?php require '../includes/admin_navbar.php'; ?>

    <div class="flex min-h-[calc(100vh-4rem)]">
        <?php require '../includes/admin_sidebar.php'; ?>

        <main class="flex-1 p-6 sm:p-10 space-y-8">
            <div class="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <span class="mui-icon text-3xl text-repaw-dark">pets</span>
                    <h1 class="font-serif text-3xl font-bold text-repaw-dark">Pets List</h1>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-repaw-hover/40 table-container">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-repaw-bg/70 text-repaw-dark sticky top-0">
                            <tr>
                                <th class="px-3 py-3 font-semibold">ID</th>
                                <th class="px-3 py-3 font-semibold">Image</th>
                                <th class="px-3 py-3 font-semibold">Name</th>
                                <th class="px-3 py-3 font-semibold">Type</th>
                                <th class="px-3 py-3 font-semibold">Breed</th>
                                <th class="px-3 py-3 font-semibold">Sex</th>
                                <th class="px-3 py-3 font-semibold">Weight</th>
                                <th class="px-3 py-3 font-semibold">Age</th>
                                <th class="px-3 py-3 font-semibold">Date of Rescue</th>
                                <th class="px-3 py-3 font-semibold">About</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-repaw-hover/40">
                            <?php
                            $sql = "SELECT pets_id, name ,type , breed, sex, weight, age, date, about, image FROM pets";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                            ?>
                                    <tr class="table-row hover:bg-repaw-bg/40 cursor-pointer">
                                        <td class="px-3 py-3"><?php echo $row["pets_id"]; ?></td>
                                        <td class="px-3 py-3"><img src="../upload/<?php echo $row['image']; ?>" alt="" class="h-12 w-12 rounded-lg object-cover"></td>
                                        <td class="px-3 py-3 font-medium text-repaw-dark"><?php echo $row["name"]; ?></td>
                                        <td class="px-3 py-3"><?php echo $row["type"]; ?></td>
                                        <td class="px-3 py-3"><?php echo $row["breed"]; ?></td>
                                        <td class="px-3 py-3"><?php echo $row["sex"]; ?></td>
                                        <td class="px-3 py-3"><?php echo $row["weight"]; ?></td>
                                        <td class="px-3 py-3"><?php echo $row["age"]; ?></td>
                                        <td class="px-3 py-3"><?php echo $row["date"]; ?></td>
                                        <td class="px-3 py-3 max-w-xs"><?php echo $row["about"]; ?></td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='10' class='px-3 py-6 text-center text-repaw-text/70'>No data available</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
                <h1 class="font-serif text-2xl font-bold text-repaw-dark mb-6">Pet Form</h1>
                <form action="#" method="POST" enctype="multipart/form-data" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="id" class="block text-sm font-medium text-repaw-dark mb-1.5">ID:</label>
                            <input type="text" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="id" name="id" readonly>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-repaw-dark mb-1.5">Pet Name:</label>
                            <input type="text" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="name" name="name" required>
                        </div>
                        <div>
                            <label for="type" class="block text-sm font-medium text-repaw-dark mb-1.5">Pet Type:</label>
                            <select class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="type" name="type" required>
                                <option value="Dog">Dog</option>
                                <option value="Cat">Cat</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="breed" class="block text-sm font-medium text-repaw-dark mb-1.5">Breed:</label>
                            <select name="breed" id="breed" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text">
                                <option value="">Select Breed</option>
                                <optgroup label="Dog Breeds">
                                    <option value="Aspin">Aspin</option>
                                    <option value="Shih Tzu">Shih Tzu</option>
                                    <option value="Pomeranian">Pomeranian</option>
                                    <option value="Labrador Retriever">Labrador Retriever</option>
                                    <option value="German Shepherd">German Shepherd</option>
                                    <option value="Golden Retriever">Golden Retriever</option>
                                    <option value="Rottweiler">Rottweiler</option>
                                    <option value="Chihuahua">Chihuahua</option>
                                    <option value="Bulldog">Bulldog</option>
                                    <option value="Dalmatian">Dalmatian</option>
                                    <option value="Beagle">Beagle</option>
                                    <option value="Boxer">Boxer</option>
                                    <option value="Doberman Pinscher">Doberman Pinscher</option>
                                    <option value="Siberian Husky">Siberian Husky</option>
                                    <option value="Pug">Pug</option>
                                    <option value="Cocker Spaniel">Cocker Spaniel</option>
                                    <option value="Australian Shepherd">Australian Shepherd</option>
                                    <option value="Poodle">Poodle</option>
                                    <option value="Bichon Frise">Bichon Frise</option>
                                </optgroup>
                                <optgroup label="Cat Breeds">
                                    <option value="Persian">Persian</option>
                                    <option value="Siamese">Siamese</option>
                                    <option value="Maine Coon">Maine Coon</option>
                                    <option value="Bengal">Bengal</option>
                                    <option value="Puspin">Puspin</option>
                                    <option value="Scottish Fold">Scottish Fold</option>
                                    <option value="British Shorthair">British Shorthair</option>
                                    <option value="Ragdoll">Ragdoll</option>
                                    <option value="Sphynx">Sphynx</option>
                                    <option value="Norwegian Forest Cat">Norwegian Forest Cat</option>
                                    <option value="Russian Blue">Russian Blue</option>
                                    <option value="Exotic Shorthair">Exotic Shorthair</option>
                                    <option value="Persian Chinchilla">Persian Chinchilla</option>
                                    <option value="Himalayan">Himalayan</option>
                                    <option value="Devon Rex">Devon Rex</option>
                                    <option value="Manx">Manx</option>
                                    <option value="Cornish Rex">Cornish Rex</option>
                                    <option value="Tonkinese">Tonkinese</option>
                                    <option value="Burmese">Burmese</option>
                                    <option value="Abyssinian">Abyssinian</option>
                                </optgroup>
                            </select>
                        </div>
                        <div>
                            <label for="sex" class="block text-sm font-medium text-repaw-dark mb-1.5">Sex:</label>
                            <select class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="sex" name="sex" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="weight" class="block text-sm font-medium text-repaw-dark mb-1.5">Weight:</label>
                            <select class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="weight" name="weight" required>
                                <option value="Less than 5 lbs">Less than 5 lbs</option>
                                <option value="5-10 lbs">5-10 lbs</option>
                                <option value="10-20 lbs">10-20 lbs</option>
                                <option value="20-50 lbs">20-50 lbs</option>
                                <option value="over 50 lbs">over 50 lbs</option>
                            </select>
                        </div>
                        <div>
                            <label for="age" class="block text-sm font-medium text-repaw-dark mb-1.5">Age:</label>
                            <select class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="age" name="age" required>
                                <option value="Less than 6 months">Less than 6 months</option>
                                <option value="6 months to 5 years">6 months to 5 years</option>
                                <option value="5 to 10 years">5 to 10 years</option>
                                <option value="over 10 years">over 10 years</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="date" class="block text-sm font-medium text-repaw-dark mb-1.5">Date of Rescue:</label>
                        <input type="date" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="date" name="date" required>
                    </div>
                    <div>
                        <label for="about" class="block text-sm font-medium text-repaw-dark mb-1.5">About:</label>
                        <textarea class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="about" name="about" rows="4" required></textarea>
                    </div>
                    <div>
                        <label for="image" class="block text-sm font-medium text-repaw-dark mb-1.5">Image:</label>
                        <input type="file" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text file:mr-4 file:rounded-lg file:border-0 file:bg-repaw-text file:px-4 file:py-2 file:text-repaw-bg" id="image" name="image">
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button type="submit" name="update" class="inline-flex items-center gap-2 bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300"><span class="mui-icon">save</span> Update</button>
                        <button type="submit" name="delete" class="inline-flex items-center gap-2 bg-repaw-danger text-white rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:opacity-90 transition-opacity"><span class="mui-icon">delete</span> Delete</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.querySelectorAll(".table-row").forEach(function (row) {
            row.addEventListener("click", function () {
                const cell = i => row.children[i].textContent.trim();
                document.getElementById("id").value = cell(0);
                document.getElementById("name").value = cell(2);
                document.getElementById("type").value = cell(3);
                document.getElementById("breed").value = cell(4);
                document.getElementById("sex").value = cell(5);
                document.getElementById("weight").value = cell(6);
                document.getElementById("age").value = cell(7);
                document.getElementById("date").value = cell(8);
                document.getElementById("about").value = cell(9);
            });
        });
    </script>
</body>

</html>
