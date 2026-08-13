<?php require '../includes/config.php' ?>
<?php
session_start();

// Retrieve the news content based on the news ID using a prepared statement
$newsId = $_GET['news_id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM news WHERE news_id = ?");
$stmt->bind_param("i", $newsId);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $newsItem = $result->fetch_assoc();
    $title = $newsItem['title'];
    $date = $newsItem['date_published'];
    $content = $newsItem['details'];
    $image = $newsItem['image'];
    $stmt->close();
} else {
    $title = "Article Not Found";
    $date = "";
    $content = "The requested article could not be found.";
    $image = "";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/icon.png" type="image/png">
    <title><?php echo htmlspecialchars($title, ENT_HTML5, 'UTF-8'); ?> — rePaw City</title>
    <meta name="description" content="<?php echo htmlspecialchars(substr($content, 0, 160), ENT_HTML5, 'UTF-8'); ?>">
</head>

<body class="font-sans bg-repaw-bg text-repaw-text antialiased">

    <?php include '../includes/navbar.php' ?>

    <main id="top">
        <section class="max-w-3xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
            <a href="news.php" class="inline-flex items-center gap-2 text-repaw-dark font-medium hover:text-repaw-text transition-colors mb-8">
                <span class="mui-icon">arrow_back</span> Back to News
            </a>

            <article class="bg-white/70 rounded-3xl p-8 sm:p-10 border border-repaw-hover/40 shadow-sm">
                <h1 class="font-serif text-3xl sm:text-4xl font-bold text-repaw-dark"><?php echo htmlspecialchars($title, ENT_HTML5, 'UTF-8'); ?></h1>
                <?php if (!empty($date)): ?>
                    <p class="mt-2 text-sm text-repaw-text/60">Published: <?php echo date('F d, Y', strtotime($date)); ?></p>
                <?php endif; ?>

                <div class="flex gap-3 mt-4">
                    <a href="#" aria-label="Facebook" class="w-10 h-10 rounded-full bg-repaw-dark text-repaw-accent flex items-center justify-center hover:bg-repaw-text hover:scale-110 transition-all duration-300 shadow-md"><i class="bi bi-facebook"></i></a>
                    <a href="#" aria-label="Instagram" class="w-10 h-10 rounded-full bg-repaw-dark text-repaw-accent flex items-center justify-center hover:bg-repaw-text hover:scale-110 transition-all duration-300 shadow-md"><i class="bi bi-instagram"></i></a>
                    <a href="#" aria-label="TikTok" class="w-10 h-10 rounded-full bg-repaw-dark text-repaw-accent flex items-center justify-center hover:bg-repaw-text hover:scale-110 transition-all duration-300 shadow-md"><i class="bi bi-tiktok"></i></a>
                </div>

                <?php if (!empty($image)): ?>
                    <div class="mt-8 rounded-2xl overflow-hidden border border-repaw-hover/40">
                        <img src="../upload/news/<?php echo htmlspecialchars($image, ENT_HTML5, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($title, ENT_HTML5, 'UTF-8'); ?>" class="w-full max-h-[420px] object-cover">
                    </div>
                <?php endif; ?>

                <div class="mt-8 text-repaw-text/90 leading-relaxed whitespace-pre-line"><?php echo htmlspecialchars($content, ENT_HTML5, 'UTF-8'); ?></div>
            </article>
        </section>
    </main>

    <?php include '../includes/footer.php' ?>

</body>

</html>
