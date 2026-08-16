<?php
require '../includes/admin_guard.php';

if (isset($_POST["submit"])) {
    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $details = mysqli_real_escape_string($conn, $_POST["details"]);
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
        if (!in_array($imageExtension, $validImageExtension)) {
            echo "<script> alert('Invalid Image Extension'); </script>";
        } elseif ($filesize > 3000000) {
            echo "<script> alert('Image Size Is Too Large'); </script>";
        } else {
            $newImageName = uniqid();
            $newImageName .= '.' . $imageExtension;

            move_uploaded_file($tmpName, '../upload/news/' . $newImageName);
            $query = "INSERT INTO news (title, details, image, user_id) VALUES ('$title', '$details', '$newImageName', '$user_id')";
            $result = mysqli_query($conn, $query);
            if ($result) {
                echo "
                <script> 
                    alert('Successfully Added'); 
                    document.location.href = 'admin-add-news.php';
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
    <title>Add News — rePaw City Admin</title>
    <?php require '../includes/admin_head.php'; ?>
</head>

<body class="font-sans bg-repaw-bg text-repaw-text antialiased">
    <?php require '../includes/admin_navbar.php'; ?>

    <div class="flex min-h-[calc(100vh-4rem)]">
        <?php require '../includes/admin_sidebar.php'; ?>

        <main class="flex-1 p-6 sm:p-10">
            <div class="max-w-3xl mx-auto bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <span class="mui-icon text-3xl text-repaw-dark">newspaper</span>
                    <h1 class="font-serif text-3xl font-bold text-repaw-dark">News Form</h1>
                </div>

                <form action="#" method="POST" enctype="multipart/form-data" class="space-y-5">
                    <div>
                        <label for="title" class="block text-sm font-medium text-repaw-dark mb-1.5">News Title:</label>
                        <input type="text" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="title" name="title" required>
                    </div>
                    <div>
                        <label for="details" class="block text-sm font-medium text-repaw-dark mb-1.5">Details:</label>
                        <textarea class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="details" name="details" rows="4" required></textarea>
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
