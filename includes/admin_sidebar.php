<?php
$adminNav = [
    'Overview' => [
        ['file' => 'admin-dashboard.php', 'label' => 'Dashboard', 'icon' => 'dashboard'],
    ],
    'Pets' => [
        ['file' => 'admin-add-pets.php', 'label' => 'Add Pets', 'icon' => 'pets'],
        ['file' => 'admin-manage-pets.php', 'label' => 'Manage Pets', 'icon' => 'pets'],
        ['file' => 'admin-manage-featured.php', 'label' => 'Modify Featured Image', 'icon' => 'stars'],
    ],
    'News' => [
        ['file' => 'admin-add-news.php', 'label' => 'Add News', 'icon' => 'newspaper'],
        ['file' => 'admin-manage-news.php', 'label' => 'Manage News', 'icon' => 'newspaper'],
    ],
    'Accounts' => [
        ['file' => 'admin-manage-user.php', 'label' => 'Manage Users', 'icon' => 'group'],
    ],
];
$currentPage = $currentPage ?? basename($_SERVER['PHP_SELF']);
?>
<aside class="w-60 shrink-0 bg-white/60 border-r border-repaw-hover/40 p-4 hidden md:block">
    <nav class="flex flex-col gap-5">
        <?php foreach ($adminNav as $group => $links): ?>
            <div>
                <p class="px-4 mb-1 text-xs font-semibold uppercase tracking-wider text-repaw-text/50"><?php echo $group; ?></p>
                <?php foreach ($links as $link): ?>
                    <a href="<?php echo $link['file']; ?>"
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium <?php echo $currentPage === $link['file'] ? 'bg-repaw-text text-repaw-bg' : 'text-repaw-text hover:bg-repaw-hover/60 transition-colors'; ?>">
                        <span class="mui-icon text-[20px]"><?php echo $link['icon']; ?></span>
                        <?php echo $link['label']; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </nav>
</aside>
