<?php
require '../includes/config.php';
session_start();

$loggedIn = isset($_SESSION['auth_user']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/icon.png" type="image/png">
    <title>Adopt a Pet — rePaw City</title>
    <meta name="description" content="Meet your future companion at rePaw City.">
</head>

<body class="font-sans bg-repaw-bg text-repaw-text antialiased">

    <?php include '../includes/navbar.php' ?>

    <main id="top">
        <!-- Hero -->
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-repaw-bg/90 via-repaw-bg/70 to-repaw-accent/40 pointer-events-none"></div>
            <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-16 lg:py-20 text-center">
                <h1 class="font-serif text-4xl sm:text-5xl font-bold text-repaw-dark">Meet your new best friend</h1>
                <p class="mt-4 text-lg text-repaw-text/90 max-w-2xl mx-auto">All of our cats and dogs can be seen by appointment only.</p>
                <a href="<?php echo $loggedIn ? '../booking/book-appointment.php' : 'loginpage.php'; ?>"
                   class="btn-repaw btn-repaw-primary mt-8"
                   <?php echo $loggedIn ? 'target="_blank"' : ''; ?>>
                    <span class="mui-icon text-[20px]">event_available</span> Book Appointment
                </a>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
            <a href="adoptpage.php" class="inline-flex items-center gap-2 text-repaw-dark font-medium hover:text-repaw-text transition-colors mb-8">
                <span class="mui-icon">arrow_back</span> Back to pets
            </a>

            <?php
            $id = $_GET['id'] ?? 0;
            $stmt = $conn->prepare("SELECT * FROM pets WHERE pets_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $rows = $stmt->get_result();
            $stmt->close();
            ?>

            <?php if ($rows->num_rows > 0): foreach ($rows as $row): ?>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
                    <div class="rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm bg-white/70 aspect-square">
                        <img src="../upload/<?php echo htmlspecialchars($row['image'], ENT_HTML5, 'UTF-8'); ?>"
                             alt="<?php echo htmlspecialchars($row['name'], ENT_HTML5, 'UTF-8'); ?>"
                             class="w-full h-full object-cover">
                    </div>

                    <div class="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
                        <h1 class="font-serif text-3xl font-bold text-repaw-dark"><?php echo htmlspecialchars($row['name'], ENT_HTML5, 'UTF-8'); ?></h1>

                        <dl class="mt-6 grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
                            <div>
                                <dt class="text-repaw-text/60">Type</dt>
                                <dd class="font-medium text-repaw-dark"><?php echo htmlspecialchars($row['type'], ENT_HTML5, 'UTF-8'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-repaw-text/60">Breed</dt>
                                <dd class="font-medium text-repaw-dark"><?php echo htmlspecialchars($row['breed'], ENT_HTML5, 'UTF-8'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-repaw-text/60">Sex</dt>
                                <dd class="font-medium text-repaw-dark"><?php echo htmlspecialchars($row['sex'], ENT_HTML5, 'UTF-8'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-repaw-text/60">Weight</dt>
                                <dd class="font-medium text-repaw-dark"><?php echo htmlspecialchars($row['weight'], ENT_HTML5, 'UTF-8'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-repaw-text/60">Age</dt>
                                <dd class="font-medium text-repaw-dark"><?php echo htmlspecialchars($row['age'], ENT_HTML5, 'UTF-8'); ?></dd>
                            </div>
                            <div>
                                <dt class="text-repaw-text/60">Date of Rescue</dt>
                                <dd class="font-medium text-repaw-dark"><?php echo htmlspecialchars($row['date'], ENT_HTML5, 'UTF-8'); ?></dd>
                            </div>
                        </dl>

                        <h2 class="mt-8 font-serif text-xl font-semibold text-repaw-dark">About <?php echo htmlspecialchars($row['name'], ENT_HTML5, 'UTF-8'); ?>:</h2>
                        <p class="mt-3 text-repaw-text/90 leading-relaxed"><?php echo nl2br(htmlspecialchars($row['about'], ENT_HTML5, 'UTF-8')); ?></p>

                        <a href="<?php echo $loggedIn ? '../booking/book-appointment.php' : 'loginpage.php'; ?>"
                           class="btn-repaw btn-repaw-primary mt-8"
                           <?php echo $loggedIn ? 'target="_blank"' : ''; ?>>
                            <span class="mui-icon text-[20px]">call</span>
                            Contact us to Meet <?php echo htmlspecialchars($row['name'], ENT_HTML5, 'UTF-8'); ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </section>
    </main>

    <?php include '../includes/footer.php' ?>

</body>

</html>
