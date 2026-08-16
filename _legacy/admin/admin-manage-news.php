<?php
require '../includes/admin_guard.php';

// Promote Button
if (isset($_POST['promote'])) {
    // Retrieve the data from the form
    $id = $_POST['id'];
    $is_featured = $_POST['is_featured'];

    if ($is_featured == 1) {
        echo '<script language="javascript">';
        echo 'alert("Already a Headline");';
        echo 'window.location.href = "admin-manage-news.php";';
        echo '</script>';
        exit;
    } elseif ($is_featured == 0) {
        // Set all other news as not featured (is_featured = 0)
        $updateAllSql = "UPDATE news SET is_featured = 0 WHERE news_id <> '$id'";
        if (mysqli_query($conn, $updateAllSql)) {
            // Set the selected news as featured (is_featured = 1)
            $updateSql = "UPDATE news SET is_featured = 1 WHERE news_id = '$id'";
            if (mysqli_query($conn, $updateSql)) {
                echo '<script language="javascript">';
                echo 'alert("Set as Headlined");';
                echo 'window.location.href = "admin-manage-news.php";';
                echo '</script>';
                exit;
            } else {
                echo "Error setting as Headlined: " . mysqli_error($conn);
                exit;
            }
        } else {
            echo "Error updating news: " . mysqli_error($conn);
            exit;
        }
    } else {
        echo "Invalid user type";
        exit;
    }
}

//Update Button
if (isset($_POST['update'])) {
    // Retrieve the data from the form
    $id = $_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $details = mysqli_real_escape_string($conn, $_POST['details']);

    $sql = "UPDATE news SET title = '$title', details = '$details' WHERE news_id = '$id'";

    // Perform the database query
    if (mysqli_query($conn, $sql)) {
        echo "
            <script> 
                alert('Data updated successfully'); 
                window.location.href = 'admin-manage-news.php';
            </script>";
    } else {
        echo "Error updating data: " . mysqli_error($conn);
    }
}

// Delete Button
if (isset($_POST['delete'])) {
    // Retrieve the id of the record to delete
    $id = $_POST['id'];

    // Delete the record from the database
    $sql = "DELETE FROM news WHERE news_id='$id'";

    if (mysqli_query($conn, $sql)) {
        echo "
        <script> 
            alert('Record deleted successfully'); 
            document.location.href = 'admin-manage-news.php';
        </script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage News — rePaw City Admin</title>
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
                    <span class="mui-icon text-3xl text-repaw-dark">newspaper</span>
                    <h1 class="font-serif text-3xl font-bold text-repaw-dark">News List</h1>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-repaw-hover/40 table-container">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-repaw-bg/70 text-repaw-dark sticky top-0">
                            <tr>
                                <th class="px-3 py-3 font-semibold">News ID</th>
                                <th class="px-3 py-3 font-semibold">Image</th>
                                <th class="px-3 py-3 font-semibold">Title</th>
                                <th class="px-3 py-3 font-semibold">Details</th>
                                <th class="px-3 py-3 font-semibold">Date Published</th>
                                <th class="px-3 py-3 font-semibold">Is Featured</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-repaw-hover/40">
                            <?php
                            $sql = "SELECT news_id, image ,title , details, date_published, is_featured FROM news";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                            ?>
                                    <tr class="table-row hover:bg-repaw-bg/40 cursor-pointer">
                                        <td class="px-3 py-3"><?php echo $row["news_id"]; ?></td>
                                        <td class="px-3 py-3"><img src="../upload/news/<?php echo $row['image']; ?>" alt="" class="h-12 w-12 rounded-lg object-cover"></td>
                                        <td class="px-3 py-3 font-medium text-repaw-dark"><?php echo $row["title"]; ?></td>
                                        <td class="px-3 py-3 max-w-xs"><?php echo $row["details"]; ?></td>
                                        <td class="px-3 py-3"><?php echo $row["date_published"]; ?></td>
                                        <td class="px-3 py-3"><?php echo $row["is_featured"]; ?></td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='6' class='px-3 py-6 text-center text-repaw-text/70'>No data available</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
                <h1 class="font-serif text-2xl font-bold text-repaw-dark mb-6">News Details</h1>
                <form action="#" method="POST" enctype="multipart/form-data" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="id" class="block text-sm font-medium text-repaw-dark mb-1.5">ID:</label>
                            <input type="text" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="id" name="id" readonly>
                        </div>
                        <div>
                            <label for="is_featured" class="block text-sm font-medium text-repaw-dark mb-1.5">Is Featured:</label>
                            <input type="text" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="is_featured" name="is_featured" required readonly>
                        </div>
                    </div>
                    <div>
                        <label for="title" class="block text-sm font-medium text-repaw-dark mb-1.5">Title:</label>
                        <input type="text" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="title" name="title" required>
                    </div>
                    <div>
                        <label for="details" class="block text-sm font-medium text-repaw-dark mb-1.5">Details:</label>
                        <textarea class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="details" name="details" rows="4" required></textarea>
                    </div>
                    <div>
                        <label for="date_published" class="block text-sm font-medium text-repaw-dark mb-1.5">Date Published:</label>
                        <input type="text" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="date_published" name="date_published" required readonly>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button type="submit" name="update" class="inline-flex items-center gap-2 bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300"><span class="mui-icon">save</span> Update</button>
                        <button type="submit" name="delete" class="inline-flex items-center gap-2 bg-repaw-danger text-white rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:opacity-90 transition-opacity"><span class="mui-icon">delete</span> Delete</button>
                        <button type="submit" name="promote" class="inline-flex items-center gap-2 bg-repaw-accent text-repaw-dark rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark hover:text-repaw-accent transition-colors duration-300"><span class="mui-icon">push_pin</span> Set as Headline</button>
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
                document.getElementById("is_featured").value = cell(5);
                document.getElementById("title").value = cell(2);
                document.getElementById("details").value = cell(3);
                document.getElementById("date_published").value = cell(4);
            });
        });
    </script>
</body>

</html>
