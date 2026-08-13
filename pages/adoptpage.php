<?php
session_start();
require '../includes/config.php';

$loggedIn = isset($_SESSION['auth_user']);

// Retrieve the selected filter values from the form submission
$type = $_POST['type'] ?? $_GET['type'] ?? '';
$sex = $_POST['sex'] ?? '';
$weight = $_POST['weight'] ?? '';
$age = $_POST['age'] ?? '';

// Build the base query with prepared statement placeholders
$query = "SELECT * FROM pets WHERE 1=1";
$params = [];
$types = "";

// Add filters to the query if they are selected
if (!empty($type)) {
    $query .= " AND type = ?";
    $params[] = $type;
    $types .= "s";
}
if (!empty($sex)) {
    $query .= " AND sex = ?";
    $params[] = $sex;
    $types .= "s";
}
if (!empty($weight)) {
    $query .= " AND weight = ?";
    $params[] = $weight;
    $types .= "s";
}
if (!empty($age)) {
    $query .= " AND age = ?";
    $params[] = $age;
    $types .= "s";
}

// Execute the query using a prepared statement
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$pet_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/icon.png" type="image/png">
    <title>Adopt — rePaw City</title>
    <meta name="description" content="Meet adoptable dogs and cats at rePaw City. Filter by type, sex, weight, and age, then book an appointment to visit.">
</head>

<body class="font-sans bg-repaw-bg text-repaw-text antialiased">

    <?php include '../includes/navbar.php'; ?>

    <main id="top">
        <!-- Hero -->
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-repaw-bg/90 via-repaw-bg/70 to-repaw-accent/40 pointer-events-none"></div>
            <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-16 lg:py-20 text-center">
                <h1 class="font-serif text-4xl sm:text-5xl font-bold text-repaw-dark">Find your new best friend</h1>
                <p class="mt-4 text-lg text-repaw-text/90 max-w-2xl mx-auto">All of our cats and dogs can be seen by appointment only.</p>
                <a href="<?php echo $loggedIn ? '../booking/book-appointment.php' : 'loginpage.php'; ?>"
                   class="btn-repaw btn-repaw-primary mt-8"
                   <?php echo $loggedIn ? 'target="_blank"' : ''; ?>>
                    <span class="mui-icon text-[20px]">event_available</span> Book Appointment
                </a>
            </div>
        </section>

        <!-- Filters + Pet grid -->
        <section class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20" id="pets">
            <div class="text-center mb-10">
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-repaw-dark">Meet Our Pets</h2>
                <p class="mt-3 text-repaw-text/80">Sort and filter to find the perfect match.</p>
            </div>

            <!-- Filter bar -->
            <form action="" method="post"
                  class="bg-white/70 border border-repaw-hover/40 rounded-3xl p-5 sm:p-6 lg:p-8 shadow-sm mb-12">
                <div class="flex items-center gap-2 mb-4 text-repaw-dark">
                    <span class="mui-icon">tune</span>
                    <span class="font-medium">Sort by:</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label for="type" class="block text-sm font-medium text-repaw-dark mb-1.5">Pet Type</label>
                        <select class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="type" name="type" onchange="this.form.submit()">
                            <option value="">Select Type</option>
                            <option value="Dog" <?php if ($type === 'Dog') echo 'selected'; ?>>Dog</option>
                            <option value="Cat" <?php if ($type === 'Cat') echo 'selected'; ?>>Cat</option>
                        </select>
                    </div>
                    <div>
                        <label for="sex" class="block text-sm font-medium text-repaw-dark mb-1.5">Sex</label>
                        <select class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="sex" name="sex" onchange="this.form.submit()">
                            <option value="">Select Sex</option>
                            <option value="Male" <?php if ($sex === 'Male') echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if ($sex === 'Female') echo 'selected'; ?>>Female</option>
                        </select>
                    </div>
                    <div>
                        <label for="weight" class="block text-sm font-medium text-repaw-dark mb-1.5">Weight</label>
                        <select class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="weight" name="weight" onchange="this.form.submit()">
                            <option value="">Select Weight</option>
                            <option value="Less than 5 lbs" <?php if ($weight === 'Less than 5 lbs') echo 'selected'; ?>>Less than 5 lbs</option>
                            <option value="5-10 lbs" <?php if ($weight === '5-10 lbs') echo 'selected'; ?>>5-10 lbs</option>
                            <option value="10-20 lbs" <?php if ($weight === '10-20 lbs') echo 'selected'; ?>>10-20 lbs</option>
                            <option value="20-50 lbs" <?php if ($weight === '20-50 lbs') echo 'selected'; ?>>20-50 lbs</option>
                            <option value="over 50 lbs" <?php if ($weight === 'over 50 lbs') echo 'selected'; ?>>over 50 lbs</option>
                        </select>
                    </div>
                    <div>
                        <label for="age" class="block text-sm font-medium text-repaw-dark mb-1.5">Age</label>
                        <select class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" id="age" name="age" onchange="this.form.submit()">
                            <option value="">Select Age</option>
                            <option value="Less than 6 months" <?php if ($age === 'Less than 6 months') echo 'selected'; ?>>Less than 6 months</option>
                            <option value="6 months to 5 years" <?php if ($age === '6 months to 5 years') echo 'selected'; ?>>6 months to 5 years</option>
                            <option value="5 to 10 years" <?php if ($age === '5 to 10 years') echo 'selected'; ?>>5 to 10 years</option>
                            <option value="over 10 years" <?php if ($age === 'over 10 years') echo 'selected'; ?>>over 10 years</option>
                        </select>
                    </div>
                </div>
            </form>

            <!-- Pet cards -->
            <?php if (!empty($pet_data)): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <?php foreach ($pet_data as $row):
                        $name = $row['name'];
                        $sex = $row['sex'];
                        $age = $row['age'];
                        $image = $row['image'];
                        $petId = $row['pets_id'];
                    ?>
                        <a href="adoptprofile.php?id=<?php echo $petId; ?>"
                           class="group bg-white/70 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 border border-repaw-hover/40">
                            <div class="aspect-square overflow-hidden bg-repaw-bg/60">
                                <img src="../upload/<?php echo htmlspecialchars($image, ENT_HTML5, 'UTF-8'); ?>"
                                     alt="<?php echo htmlspecialchars($name, ENT_HTML5, 'UTF-8'); ?>"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="p-5">
                                <h4 class="font-serif text-xl font-semibold text-repaw-dark"><?php echo htmlspecialchars($name, ENT_HTML5, 'UTF-8'); ?></h4>
                                <div class="mt-2 flex items-center gap-3 text-sm text-repaw-text/80">
                                    <span class="inline-flex items-center gap-1">
                                        <span class="mui-icon text-[18px]"><?php echo $sex === 'Male' ? 'male' : 'female'; ?></span>
                                        <?php echo htmlspecialchars($sex, ENT_HTML5, 'UTF-8'); ?>
                                    </span>
                                    <span class="w-px h-4 bg-repaw-hover"></span>
                                    <span class="inline-flex items-center gap-1">
                                        <span class="mui-icon text-[18px]">cake</span>
                                        <?php echo htmlspecialchars($age, ENT_HTML5, 'UTF-8'); ?>
                                    </span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-repaw-text/80 text-lg py-12">No pets found.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>

</body>

</html>
