<?php
require '../includes/admin_guard.php';
require '../includes/config.php';

$redirect = 'window.location.href = "admin-manage-user.php";';

if (isset($_POST['promote'])) {
    $id = $_POST['id'];
    $userType = $_POST['user_type'];

    if ($userType == 1) {
        echo '<script>alert("Already an Admin");' . $redirect . '</script>';
        exit;
    } elseif ($userType == 2) {
        $sql = "UPDATE user SET user_type = 1 WHERE user_id = '$id'";
        if (mysqli_query($conn, $sql)) {
            echo '<script>alert("Promoted to Admin");' . $redirect . '</script>';
            exit;
        }
        echo "Error promoting user to admin: " . mysqli_error($conn);
        exit;
    }
    echo "Invalid user type";
    exit;
}

if (isset($_POST['demote'])) {
    $id = $_POST['id'];
    $userType = $_POST['user_type'];

    if ($userType == 2) {
        echo '<script>alert("Already a Regular User");' . $redirect . '</script>';
        exit;
    } elseif ($userType == 1) {
        $sql = "UPDATE user SET user_type = 2 WHERE user_id = '$id'";
        if (mysqli_query($conn, $sql)) {
            echo '<script>alert("Demoted to Regular User");' . $redirect . '</script>';
            exit;
        }
        echo "Error demoting user to regular user: " . mysqli_error($conn);
        exit;
    }
    echo "Invalid user type";
    exit;
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $firstName = mysqli_real_escape_string($conn, $_POST['fname']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE user SET fname = ?, lname = ?, email = ?, password = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $firstName, $lastName, $email, $hashed_password, $id);
    } else {
        $sql = "UPDATE user SET fname = ?, lname = ?, email = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $firstName, $lastName, $email, $id);
    }

    if ($stmt->execute()) {
        echo '<script>alert("Data updated successfully");' . $redirect . '</script>';
    } else {
        echo "Error updating data: " . mysqli_error($conn);
    }
    $stmt->close();
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM user WHERE user_id='$id'";

    if (mysqli_query($conn, $sql)) {
        echo '<script>alert("Record deleted successfully");' . $redirect . '</script>';
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Users — rePaw City Admin</title>
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
                    <span class="mui-icon text-3xl text-repaw-dark">group</span>
                    <h1 class="font-serif text-3xl font-bold text-repaw-dark">User List</h1>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-repaw-hover/40 table-container">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-repaw-bg/70 text-repaw-dark sticky top-0">
                            <tr>
                                <th class="px-3 py-3 font-semibold">User ID</th>
                                <th class="px-3 py-3 font-semibold">First Name</th>
                                <th class="px-3 py-3 font-semibold">Last Name</th>
                                <th class="px-3 py-3 font-semibold">Email</th>
                                <th class="px-3 py-3 font-semibold">Password</th>
                                <th class="px-3 py-3 font-semibold">User Type</th>
                                <th class="px-3 py-3 font-semibold">Date Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-repaw-hover/40">
                            <?php
                            $sql = "SELECT user_id, fname ,lname , email, password, user_type, created_at FROM user";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                            ?>
                                    <tr class="table-row hover:bg-repaw-bg/40 cursor-pointer">
                                        <td class="px-3 py-3"><?php echo $row["user_id"]; ?></td>
                                        <td class="px-3 py-3"><?php echo $row["fname"]; ?></td>
                                        <td class="px-3 py-3"><?php echo $row["lname"]; ?></td>
                                        <td class="px-3 py-3"><?php echo $row["email"]; ?></td>
                                        <td class="px-3 py-3">••••••••</td>
                                        <td class="px-3 py-3">
                                            <span class="inline-block rounded-full px-3 py-1 text-xs font-medium <?php echo $row["user_type"] == 1 ? 'bg-repaw-dark text-repaw-bg' : 'bg-repaw-hover/60 text-repaw-dark'; ?>">
                                                <?php echo $row["user_type"] == 1 ? 'Admin' : 'User'; ?>
                                            </span>
                                        </td>
                                        <td class="px-3 py-3"><?php echo $row["created_at"]; ?></td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='7' class='px-3 py-6 text-center text-repaw-text/70'>No data available</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
                <h1 class="font-serif text-2xl font-bold text-repaw-dark mb-6">User Details</h1>
                <form action="#" method="POST" enctype="multipart/form-data" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="id" class="block text-sm font-medium text-repaw-dark mb-1.5">ID:</label>
                            <input type="text" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="id" name="id" readonly>
                        </div>
                        <div>
                            <label for="user_type" class="block text-sm font-medium text-repaw-dark mb-1.5">User Type:</label>
                            <input type="text" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="user_type" name="user_type" required readonly>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="fname" class="block text-sm font-medium text-repaw-dark mb-1.5">First Name:</label>
                            <input type="text" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="fname" name="fname" required>
                        </div>
                        <div>
                            <label for="lname" class="block text-sm font-medium text-repaw-dark mb-1.5">Last Name:</label>
                            <input type="text" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="lname" name="lname" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="email" class="block text-sm font-medium text-repaw-dark mb-1.5">Email:</label>
                            <input type="email" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="email" name="email" required>
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-repaw-dark mb-1.5">New Password (leave blank to keep current):</label>
                            <input type="password" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="password" name="password" placeholder="Leave blank to keep current password">
                        </div>
                    </div>
                    <div>
                        <label for="date_created" class="block text-sm font-medium text-repaw-dark mb-1.5">Date Created:</label>
                        <input type="text" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="date_created" name="date_created" required readonly>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button type="submit" name="update" class="inline-flex items-center gap-2 bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300"><span class="mui-icon">save</span> Update</button>
                        <button type="submit" name="delete" class="inline-flex items-center gap-2 bg-repaw-danger text-white rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:opacity-90 transition-opacity"><span class="mui-icon">delete</span> Delete</button>
                        <button type="submit" name="promote" class="inline-flex items-center gap-2 bg-repaw-accent text-repaw-dark rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark hover:text-repaw-accent transition-colors duration-300"><span class="mui-icon">arrow_upward</span> Promote</button>
                        <button type="submit" name="demote" class="inline-flex items-center gap-2 bg-repaw-hover/70 text-repaw-dark rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-hover transition-colors"><span class="mui-icon">arrow_downward</span> Demote</button>
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
                document.getElementById("fname").value = cell(1);
                document.getElementById("lname").value = cell(2);
                document.getElementById("email").value = cell(3);
                document.getElementById("user_type").value = cell(5);
                document.getElementById("date_created").value = cell(6);
                document.getElementById("password").value = "";
            });
        });
    </script>
</body>

</html>
