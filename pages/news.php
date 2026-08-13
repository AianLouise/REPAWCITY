<?php require '../includes/config.php' ?>
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/icon.png" type="image/png">
    <title>News — rePaw City</title>
    <meta name="description" content="Latest news, updates, and pet care tips from rePaw City.">
</head>

<body class="font-sans bg-repaw-bg text-repaw-text antialiased">

    <?php include '../includes/navbar.php' ?>

    <main id="top">
        <!-- Hero -->
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-repaw-bg/90 via-repaw-bg/70 to-repaw-accent/40 pointer-events-none"></div>
            <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-16 lg:py-20 text-center">
                <h1 class="font-serif text-4xl sm:text-5xl font-bold text-repaw-dark">Blogs, Latest News & Updates</h1>
                <p class="mt-4 text-lg text-repaw-text/90 max-w-2xl mx-auto">Stay up to date with rePaw City and learn how to care for your new companion.</p>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
            <!-- Pet care tips -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
                <div>
                    <h2 class="font-serif text-3xl font-bold text-repaw-dark mb-4">Pet Care Tips</h2>
                    <p class="text-repaw-text/90 leading-relaxed">
                        How to Ensure a Happy and Healthy Life for Your New Companion: discover essential tips for
                        providing optimal care and well-being to your newly adopted pet, including nutrition,
                        exercise, grooming, and more.
                    </p>
                </div>
                <div class="slideshow-container relative rounded-3xl overflow-hidden aspect-video bg-repaw-bg/60 shadow-sm">
                    <img src="../image/news/pet-tips.jpg" alt="Pet tips 1" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700">
                    <img src="../image/news/pet-tips2.jpg" alt="Pet tips 2" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 opacity-0">
                    <img src="../image/news/pet-tips3.jpg" alt="Pet tips 3" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 opacity-0">
                </div>
            </div>

            <!-- Featured + latest news -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Featured -->
                <div class="lg:col-span-2">
                    <h2 class="font-serif text-2xl font-bold text-repaw-dark mb-5">Featured News</h2>
                    <?php
                    $featuredNews = mysqli_query($conn, "SELECT * FROM news WHERE is_featured = 1");
                    $featuredNews = mysqli_fetch_assoc($featuredNews);

                    if ($featuredNews) {
                        $image = $featuredNews['image'];
                        $title = $featuredNews['title'];
                        $details = $featuredNews['details'];

                        $maxCharacters = 300;
                        if (strlen($title) > 50) {
                            $maxCharacters = 200;
                        }
                        if (strlen($details) > $maxCharacters) {
                            $details = substr($details, 0, $maxCharacters) . '...';
                        }
                    ?>
                        <a href="news-page.php?news_id=<?php echo $featuredNews['news_id']; ?>"
                           class="group block bg-white/70 rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm hover:shadow-xl transition-shadow duration-300">
                            <div class="aspect-video overflow-hidden bg-repaw-bg/60">
                                <img src="../upload/news/<?php echo htmlspecialchars($image, ENT_HTML5, 'UTF-8'); ?>" alt="Featured news" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="p-6">
                                <h3 class="font-serif text-2xl font-semibold text-repaw-dark"><?php echo htmlspecialchars($title, ENT_HTML5, 'UTF-8'); ?></h3>
                                <p class="mt-3 text-repaw-text/80 leading-relaxed"><?php echo htmlspecialchars($details, ENT_HTML5, 'UTF-8'); ?></p>
                            </div>
                        </a>
                    <?php } else { ?>
                        <div class="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40">
                            <h3 class="font-serif text-2xl font-semibold text-repaw-dark">No Featured News Available</h3>
                            <p class="mt-3 text-repaw-text/80">There is no featured news at the moment. Check back later for updates.</p>
                        </div>
                    <?php } ?>
                </div>

                <!-- Latest list -->
                <div>
                    <h2 class="font-serif text-2xl font-bold text-repaw-dark mb-5">Latest News</h2>
                    <div class="space-y-4">
                        <?php
                        $newsItems = mysqli_query($conn, "SELECT * FROM news ORDER BY date_published DESC");
                        $newsItems = mysqli_fetch_all($newsItems, MYSQLI_ASSOC);

                        foreach ($newsItems as $newsItem) {
                            $newsId = $newsItem['news_id'];
                            $image = $newsItem['image'];
                            $title = $newsItem['title'];
                            $details = $newsItem['details'];
                            $date = $newsItem['date_published'];

                            date_default_timezone_set('Asia/Manila');
                            $newsDatetime = $date;
                            $currentDatetime = date('Y-m-d H:i:s');

                            $datetime1 = new DateTime($newsDatetime);
                            $datetime2 = new DateTime($currentDatetime);
                            $interval = date_diff($datetime1, $datetime2);

                            $years = $interval->format('%y');
                            $months = $interval->format('%m');
                            $days = $interval->format('%d');
                            $hours = $interval->format('%h');
                            $minutes = $interval->format('%i');

                            $timeElapsed = '';
                            if ($years > 0) {
                                $timeElapsed = ($years > 1) ? $years . ' years ago' : '1 year ago';
                            } elseif ($months > 0) {
                                $timeElapsed = ($months > 1) ? $months . ' months ago' : '1 month ago';
                            } elseif ($days > 0) {
                                $timeElapsed = ($days > 1) ? $days . ' days ago' : '1 day ago';
                            } elseif ($hours > 0) {
                                $timeElapsed = ($hours > 1) ? $hours . ' hours ago' : '1 hour ago';
                            } elseif ($minutes > 0) {
                                $timeElapsed = ($minutes > 1) ? $minutes . ' minutes ago' : '1 minute ago';
                            } else {
                                $timeElapsed = 'Just now';
                            }

                            $maxCharacters = 100;
                            if (strlen($details) > $maxCharacters) {
                                $details = substr($details, 0, $maxCharacters) . '...';
                            }
                        ?>
                            <a href="news-page.php?news_id=<?php echo $newsId; ?>"
                               class="group flex gap-4 bg-white/70 rounded-2xl p-4 border border-repaw-hover/40 shadow-sm hover:shadow-md transition-shadow duration-300">
                                <div class="w-20 h-20 shrink-0 rounded-xl overflow-hidden bg-repaw-bg/60">
                                    <img src="../upload/news/<?php echo htmlspecialchars($image, ENT_HTML5, 'UTF-8'); ?>" alt="News thumbnail" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-serif text-base font-semibold text-repaw-dark truncate"><?php echo htmlspecialchars($title, ENT_HTML5, 'UTF-8'); ?></h4>
                                    <p class="mt-1 text-sm text-repaw-text/80 leading-snug line-clamp-2"><?php echo htmlspecialchars($details, ENT_HTML5, 'UTF-8'); ?></p>
                                    <p class="mt-1 text-xs text-repaw-text/60"><?php echo $timeElapsed; ?></p>
                                </div>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php' ?>

    <script>
        var slideIndex = 0;
        var slides = document.getElementsByClassName("slideshow-container")[0].getElementsByTagName("img");

        function showSlides() {
            for (var i = 0; i < slides.length; i++) {
                slides[i].style.opacity = 0;
            }

            slideIndex++;
            if (slideIndex > slides.length) {
                slideIndex = 1;
            }

            slides[slideIndex - 1].style.opacity = 1;

            setTimeout(showSlides, 4000);
        }
        showSlides();
    </script>
</body>

</html>
