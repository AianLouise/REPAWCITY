<nav class="sticky top-0 z-50 flex items-center justify-between bg-repaw-dark px-6 h-16 shadow-md">
    <a href="../index.php" class="flex items-center">
        <img src="../image/logo (1).png" alt="rePaw City" class="h-10 w-auto">
    </a>
    <button onclick="repawLogout()" class="inline-flex items-center gap-2 rounded-full bg-repaw-accent px-5 py-2 text-sm font-medium text-repaw-dark hover:bg-repaw-text hover:text-repaw-bg transition-colors">
        <span class="mui-icon">logout</span> Logout
    </button>
</nav>

<script>
    function repawLogout() {
        if (confirm("Are you sure you want to log out?")) {
            window.location.href = "../auth/logout.php";
        }
    }
</script>
