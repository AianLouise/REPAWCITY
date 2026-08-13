<?php
require '../includes/admin_guard.php';

if (isset($_POST["submit"])) {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $type = mysqli_real_escape_string($conn, $_POST["type"]);
    $breed = mysqli_real_escape_string($conn, $_POST["breed"]);
    $sex = mysqli_real_escape_string($conn, $_POST["sex"]);
    $weight = mysqli_real_escape_string($conn, $_POST["weight"]);
    $age = mysqli_real_escape_string($conn, $_POST["age"]);
    $date = mysqli_real_escape_string($conn, $_POST["date"]);
    $about = mysqli_real_escape_string($conn, $_POST["about"]);
    $user_id = $_SESSION['auth_user']['id'];

    if ($_FILES["image"]["error"] === 4) {
        echo "<script> alert('Image Does Not Exist'); </script>";
    } else {
        $filename = $_FILES["image"]["name"];
        $filesize = $_FILES["image"]["size"];
        $tmpName = $_FILES["image"]["tmp_name"];

        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = explode('.', $filename);
        $imageExtension = strtolower(end($imageExtension));
        // Verify the actual file content is a valid image
        $imageInfo = @getimagesize($tmpName);
        $isValidImage = $imageInfo !== false
            && in_array($imageInfo[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG]);

        if (!in_array($imageExtension, $validImageExtension)) {
            echo "<script> alert('Invalid Image Extension'); </script>";
        } elseif (!$isValidImage) {
            echo "<script> alert('File is not a valid image'); </script>";
        } elseif ($filesize > 2000000) {
            echo "<script> alert('Image Size Is Too Large'); </script>";
        } else {
            $newImageName = uniqid();
            $newImageName .= '.' . $imageExtension;

            move_uploaded_file($tmpName, '../upload/' . $newImageName);
            $query = "INSERT INTO pets (name,type,breed,sex,weight,age,about,date,image, user_id) VALUES('$name' , '$type', '$breed' , '$sex' , '$weight' , '$age', '$about', '$date', '$newImageName', '$user_id')";
            $result = mysqli_query($conn, $query);
            if ($result) {
                echo "
                <script> 
                    alert('Pets added successfully'); 
                    document.location.href = 'admin-add-pets.php';
                </script>";
            } else {
                echo "Error: " . mysqli_error($conn); // Display the specific error message
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Pets — rePaw City Admin</title>
    <?php require '../includes/admin_head.php'; ?>
</head>

<body class="font-sans bg-repaw-bg text-repaw-text antialiased">
    <!-- Admin top bar -->
    <?php require '../includes/admin_navbar.php'; ?>

    <div class="flex min-h-[calc(100vh-4rem)]">
        <!-- Sidebar -->
        <?php require '../includes/admin_sidebar.php'; ?>

        <!-- Main -->
        <main class="flex-1 p-6 sm:p-10">
            <div class="max-w-3xl mx-auto bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <span class="mui-icon text-3xl text-repaw-dark">pets</span>
                    <h1 class="font-serif text-3xl font-bold text-repaw-dark">Pet Form</h1>
                </div>

                <form action="#" method="POST" enctype="multipart/form-data" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-repaw-dark mb-1.5">Pet Name:</label>
                            <input type="text" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="name" name="name" required>
                        </div>
                        <div>
                            <label for="type" class="block text-sm font-medium text-repaw-dark mb-1.5">Pet Type:</label>
                            <select class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="type" name="type" required>
                                <option value="">Select Type</option>
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
                                <option value="">Select Sex</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="weight" class="block text-sm font-medium text-repaw-dark mb-1.5">Weight:</label>
                            <select class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="weight" name="weight" required>
                                <option value="">Select Weight</option>
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
                                <option value="">Select Age</option>
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
                        <input type="file" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text file:mr-4 file:rounded-lg file:border-0 file:bg-repaw-text file:px-4 file:py-2 file:text-repaw-bg" id="image" name="image" required>
                    </div>

                    <button type="submit" name="submit" class="inline-flex items-center gap-2 w-full sm:w-auto bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300">
                        <span class="mui-icon">add_circle</span> Submit
                    </button>
                </form>
            </div>
        </main>
    </div>
</body>

</html>
