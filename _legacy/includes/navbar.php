<?php
require __DIR__ . '/config.php';

$currentPage = basename($_SERVER['PHP_SELF']);

function navIsActive(string $file, string $current): bool
{
    return $file === $current;
}

$primaryLinks = [
    ['label' => 'Home',      'href' => '/index.php',          'file' => 'index.php'],
    ['label' => 'Adopt',     'href' => '/pages/adoptpage.php', 'file' => 'adoptpage.php'],
    ['label' => 'Donate',    'href' => '/pages/donatepage.php','file' => 'donatepage.php'],
    ['label' => 'News',      'href' => '/pages/news.php',      'file' => 'news.php'],
    ['label' => 'Volunteer', 'href' => '/pages/volunteer.php', 'file' => 'volunteer.php'],
];

$aboutLinks = [
    ['label' => 'Success Stories', 'href' => '/pages/success-stories.php', 'file' => 'success-stories.php'],
    ['label' => 'FAQ',             'href' => '/pages/FAQ.php',             'file' => 'FAQ.php'],
    ['label' => 'Contact',         'href' => '/pages/contact.php',         'file' => 'contact.php'],
    ['label' => 'Team',            'href' => '/pages/team.php',            'file' => 'team.php'],
    ['label' => 'References',      'href' => '/pages/reference.php',       'file' => 'reference.php'],
];

$aboutActive = in_array($currentPage, array_column($aboutLinks, 'file'), true);

$appointments = [];
if (!empty($_SESSION['auth']) && !empty($_SESSION['auth_user']['id'])) {
    $userId = (int) $_SESSION['auth_user']['id'];
    $stmt = $conn->prepare("SELECT appointment_type, appointment_id FROM appointment WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $appointments[] = $row;
    }
    $stmt->close();
}
?>

<!-- Self-contained styling so the navbar renders correctly on every page that includes it -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght@24,400..700&display=swap">
<style>
    .mui-icon {
        font-family: 'Material Symbols Rounded';
        font-weight: normal;
        font-style: normal;
        line-height: 1;
        letter-spacing: normal;
        text-transform: none;
        display: inline-block;
        white-space: nowrap;
        word-wrap: normal;
        direction: ltr;
        -webkit-font-feature-settings: 'liga';
        -webkit-font-smoothing: antialiased;
    }
</style>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    repaw: {
                        bg: '#f5e6d3',
                        text: '#6c4421',
                        hover: '#d6bca8',
                        dark: '#4a2c17',
                        danger: '#c62828',
                    }
                },
                fontFamily: {
                    sans: ['Roboto', 'sans-serif'],
                    serif: ['Montserrat', 'sans-serif'],
                }
            }
        }
    }
</script>
<style type="text/tailwindcss">
    @layer components {
        .nav-link-custom {
            @apply relative text-repaw-text font-medium text-[15px] uppercase tracking-wide px-4 h-20 flex items-center transition-colors duration-300 focus:outline-none;
        }
        .nav-link-custom::after {
            content: '';
            @apply absolute left-4 right-4 bottom-5 h-[2px] bg-repaw-dark scale-x-0 transition-transform duration-300 origin-center;
        }
        .nav-link-custom:hover {
            @apply text-repaw-dark;
        }
        .nav-link-custom:hover::after,
        .nav-link-custom.nav-link-active::after {
            @apply scale-x-100;
        }
        .nav-link-custom.nav-link-active {
            @apply text-repaw-dark;
        }
        .dropdown-menu {
            @apply hidden absolute top-full left-0 bg-[#f9f5f0] rounded-xl shadow-xl min-w-[210px] py-2 z-50 border border-repaw-hover/40;
        }
        .dropdown-menu::before {
            content: '';
            @apply absolute inset-x-0 -top-2 h-2;
        }
        .dropdown-menu a {
            @apply block text-repaw-text text-[15px] py-2.5 px-5 transition-colors duration-200 hover:bg-repaw-hover hover:text-repaw-dark;
        }
    }
</style>

<nav class="sticky top-0 bg-repaw-bg/95 backdrop-blur shadow-sm h-20 z-50 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <a href="/index.php" class="flex items-center group" aria-label="rePaw City home">
                <img src="/image/logo (1).png" alt="rePaw City" class="h-14 w-auto transition-transform duration-300 group-hover:scale-105">
            </a>

            <div class="hidden lg:flex items-center">
                <?php foreach ($primaryLinks as $link): ?>
                    <a href="<?php echo $link['href']; ?>"
                       class="nav-link-custom <?php echo navIsActive($link['file'], $currentPage) ? 'nav-link-active' : ''; ?>">
                        <?php echo $link['label']; ?>
                    </a>
                <?php endforeach; ?>

                <div class="relative group">
                    <button class="nav-link-custom items-center gap-1 focus:outline-none <?php echo $aboutActive ? 'nav-link-active' : ''; ?>"
                            aria-haspopup="true" aria-expanded="false">
                        About Us
                        <span class="mui-icon text-[20px] transition-transform duration-300 group-hover:rotate-180">keyboard_arrow_down</span>
                    </button>
                    <div class="dropdown-menu group-hover:block group-focus-within:block">
                        <?php foreach ($aboutLinks as $link): ?>
                            <a href="<?php echo $link['href']; ?>"
                               class="<?php echo navIsActive($link['file'], $currentPage) ? 'bg-repaw-hover text-repaw-dark' : ''; ?>">
                                <?php echo $link['label']; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if (!empty($_SESSION['auth'])): ?>
                    <div class="relative group">
                        <button class="nav-link-custom items-center gap-1 focus:outline-none" aria-haspopup="true" aria-expanded="false">
                            Profile
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="dropdown-menu group-hover:block group-focus-within:block">
                            <a href="/user/edit-profile.php">Edit Profile</a>
                            <a href="/user/change-password.php">Change Password</a>
                            <?php foreach ($appointments as $appt): ?>
                                <a href="/user/notification.php?appointmentId=<?php echo $appt['appointment_id']; ?>">
                                    <?php echo htmlspecialchars($appt['appointment_type']); ?> Appointment
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <a href="javascript:void(0);" class="nav-link-custom text-repaw-danger hover:!text-red-700" onclick="repawLogout()">Logout</a>
                <?php else: ?>
                    <a href="/auth/loginpage.php" class="nav-link-custom">Log In</a>
                    <a href="/auth/signuppage.php"
                       class="ml-2 bg-repaw-text text-repaw-bg rounded-full px-6 py-2.5 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300">
                        Sign Up
                    </a>
                <?php endif; ?>
            </div>

            <div class="lg:hidden flex items-center">
                <button id="mobile-menu-btn" class="text-repaw-text hover:text-repaw-dark p-2 focus:outline-none" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-menu">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="hidden lg:hidden bg-repaw-bg border-t border-repaw-hover/40">
        <div class="px-4 pt-3 pb-6 space-y-1 max-h-[80vh] overflow-y-auto">
            <?php foreach ($primaryLinks as $link): ?>
                <a href="<?php echo $link['href']; ?>"
                   class="block py-3 px-3 rounded-lg text-repaw-text text-lg uppercase tracking-wide <?php echo navIsActive($link['file'], $currentPage) ? 'bg-repaw-hover text-repaw-dark' : 'hover:bg-repaw-hover'; ?>">
                    <?php echo $link['label']; ?>
                </a>
            <?php endforeach; ?>

            <div class="py-1">
                <button id="about-toggle" class="w-full flex justify-between items-center py-3 px-3 rounded-lg text-repaw-text text-lg uppercase tracking-wide hover:bg-repaw-hover focus:outline-none" aria-expanded="false" aria-controls="about-menu">
                    About Us
                    <svg id="about-chevron" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="about-menu" class="hidden pl-4 space-y-1 mt-1">
                    <?php foreach ($aboutLinks as $link): ?>
                        <a href="<?php echo $link['href']; ?>"
                           class="block py-2 px-3 rounded-lg text-repaw-text hover:bg-repaw-hover hover:text-repaw-dark <?php echo navIsActive($link['file'], $currentPage) ? 'bg-repaw-hover text-repaw-dark' : ''; ?>">
                            <?php echo $link['label']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if (!empty($_SESSION['auth'])): ?>
                <div class="py-1">
                    <button id="profile-toggle" class="w-full flex justify-between items-center py-3 px-3 rounded-lg text-repaw-text text-lg uppercase tracking-wide hover:bg-repaw-hover focus:outline-none" aria-expanded="false" aria-controls="profile-menu">
                        Profile
                        <svg id="profile-chevron" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="profile-menu" class="hidden pl-4 space-y-1 mt-1">
                        <a href="/user/edit-profile.php" class="block py-2 px-3 rounded-lg text-repaw-text hover:bg-repaw-hover hover:text-repaw-dark">Edit Profile</a>
                        <a href="/user/change-password.php" class="block py-2 px-3 rounded-lg text-repaw-text hover:bg-repaw-hover hover:text-repaw-dark">Change Password</a>
                        <?php foreach ($appointments as $appt): ?>
                            <a href="/user/notification.php?appointmentId=<?php echo $appt['appointment_id']; ?>" class="block py-2 px-3 rounded-lg text-repaw-text hover:bg-repaw-hover hover:text-repaw-dark">
                                <?php echo htmlspecialchars($appt['appointment_type']); ?> Appointment
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <a href="javascript:void(0);" onclick="repawLogout()" class="block py-3 px-3 mt-1 rounded-lg text-repaw-danger text-lg uppercase tracking-wide hover:bg-red-50">Logout</a>
            <?php else: ?>
                <a href="/auth/loginpage.php" class="block py-3 px-3 rounded-lg text-repaw-text text-lg uppercase tracking-wide hover:bg-repaw-hover">Log In</a>
                <a href="/auth/signuppage.php" class="block py-3 mt-2 text-center bg-repaw-text text-repaw-bg text-lg uppercase tracking-wide rounded-full hover:bg-repaw-dark">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        function closeMobileMenu() {
            mobileMenu.classList.add('hidden');
            mobileMenuBtn.setAttribute('aria-expanded', 'false');
        }

        mobileMenuBtn.addEventListener('click', function () {
            const isHidden = mobileMenu.classList.toggle('hidden');
            mobileMenuBtn.setAttribute('aria-expanded', String(!isHidden));
        });

        function bindToggle(toggleId, menuId, chevronId) {
            const toggle = document.getElementById(toggleId);
            const menu = document.getElementById(menuId);
            const chevron = document.getElementById(chevronId);
            if (!toggle) return;
            toggle.addEventListener('click', function () {
                const isHidden = menu.classList.toggle('hidden');
                toggle.setAttribute('aria-expanded', String(!isHidden));
                if (chevron) chevron.classList.toggle('rotate-180', !isHidden);
            });
        }

        bindToggle('about-toggle', 'about-menu', 'about-chevron');
        bindToggle('profile-toggle', 'profile-menu', 'profile-chevron');

        document.addEventListener('click', function (e) {
            if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                closeMobileMenu();
            }
        });
    });

    function repawLogout() {
        if (confirm("Are you sure you want to log out?")) {
            window.location.href = "/auth/logout.php";
        }
    }
</script>
