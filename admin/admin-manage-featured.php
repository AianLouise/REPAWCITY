<?php
require '../includes/admin_guard.php';

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
        echo "
        <script> 
            alert('Records updated successfully'); 
            window.location.href = 'admin-manage-featured.php';
        </script>";
    } else {
        echo "Error updating records: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Modify Featured — rePaw City Admin</title>
    <?php require '../includes/admin_head.php'; ?>
</head>

<body class="font-sans bg-repaw-bg text-repaw-text antialiased">
    <?php require '../includes/admin_navbar.php'; ?>

    <div class="flex min-h-[calc(100vh-4rem)]">
        <?php require '../includes/admin_sidebar.php'; ?>

        <main class="flex-1 p-6 sm:p-10">
            <div class="max-w-5xl mx-auto bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <span class="mui-icon text-3xl text-repaw-dark">stars</span>
                    <h1 class="font-serif text-3xl font-bold text-repaw-dark">Pets List</h1>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-repaw-hover/40">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-repaw-bg/70 text-repaw-dark">
                            <tr>
                                <th class="px-4 py-3 font-semibold">ID</th>
                                <th class="px-4 py-3 font-semibold">Image</th>
                                <th class="px-4 py-3 font-semibold">Name</th>
                                <th class="px-4 py-3 font-semibold">Is Featured</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-repaw-hover/40">
                            <?php
                            $sql = "SELECT pets_id, name, image , is_featured, sex FROM pets";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                            ?>
                                    <tr class="hover:bg-repaw-bg/40">
                                        <td class="px-4 py-3"><?php echo $row["pets_id"]; ?></td>
                                        <td class="px-4 py-3"><img src="../upload/<?php echo $row['image']; ?>" alt="" class="h-12 w-12 rounded-lg object-cover"></td>
                                        <td class="px-4 py-3 font-medium text-repaw-dark"><?php echo $row["name"]; ?></td>
                                        <td class="px-4 py-3"><?php echo $row["is_featured"]; ?></td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='4' class='px-4 py-6 text-center text-repaw-text/70'>No data available</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <h4 class="text-center font-serif text-xl font-semibold text-repaw-dark mt-10 mb-6">Select IDs to set as Featured Images</h4>
                <form method="POST" class="max-w-md mx-auto grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="featured_image_1" class="block text-sm font-medium text-repaw-dark mb-1.5">Featured Image 1:</label>
                        <input type="number" name="featured_image_1" id="featured_image_1" value="<?php echo isset($featuredImage1) ? $featuredImage1 : ''; ?>" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text">
                    </div>
                    <div>
                        <label for="featured_image_2" class="block text-sm font-medium text-repaw-dark mb-1.5">Featured Image 2:</label>
                        <input type="number" name="featured_image_2" id="featured_image_2" value="<?php echo isset($featuredImage2) ? $featuredImage2 : ''; ?>" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text">
                    </div>
                    <div>
                        <label for="featured_image_3" class="block text-sm font-medium text-repaw-dark mb-1.5">Featured Image 3:</label>
                        <input type="number" name="featured_image_3" id="featured_image_3" value="<?php echo isset($featuredImage3) ? $featuredImage3 : ''; ?>" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text">
                    </div>
                    <div>
                        <label for="featured_image_4" class="block text-sm font-medium text-repaw-dark mb-1.5">Featured Image 4:</label>
                        <input type="number" name="featured_image_4" id="featured_image_4" value="<?php echo isset($featuredImage4) ? $featuredImage4 : ''; ?>" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text">
                    </div>
                    <div class="sm:col-span-2">
                        <button type="submit" name="submit" class="inline-flex items-center gap-2 w-full bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300">
                            <span class="mui-icon">stars</span> Set as Featured Images
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>
