<?php require '../includes/no_cache.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/icon.png" type="image/png">
    <title>Sign Up — rePaw City</title>
    <meta name="description" content="Create a rePaw City account.">

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
                            accent: '#fad046',
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
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
        <!-- Slideshow side -->
        <div class="relative hidden lg:block overflow-hidden bg-repaw-dark">
            <div class="slideshow-container absolute inset-0">
                <img src="../image/LoginSignup/bg1.jpg" alt="Slide 1" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700">
                <img src="../image/LoginSignup/bg2.jpg" alt="Slide 2" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 opacity-0">
                <img src="../image/LoginSignup/bg3.jpg" alt="Slide 3" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 opacity-0">
            </div>
            <div class="absolute inset-0 bg-repaw-dark/40"></div>
            <div class="relative h-full flex flex-col justify-between p-10 text-repaw-bg">
                <a href="../index.php" class="inline-block w-fit">
                    <img src="../image/logo (1).png" alt="rePaw City" class="h-16 w-auto">
                </a>
                <div>
                    <h2 class="font-serif text-3xl font-bold leading-tight">Join our community of animal lovers.</h2>
                    <p class="mt-3 text-repaw-bg/80 max-w-sm">Create an account to adopt, donate, and volunteer with rePaw City.</p>
                </div>
            </div>
        </div>

        <!-- Form side -->
        <div class="flex items-center justify-center bg-repaw-bg px-6 py-12">
            <div class="w-full max-w-md">
                <div class="lg:hidden mb-8 flex justify-center">
                    <a href="../index.php"><img src="../image/logo (1).png" alt="rePaw City" class="h-16 w-auto"></a>
                </div>

                <form class="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm" name="signup" action="../includes/authcode.php" method="post" autocomplete="off">
                    <div class="text-center mb-8">
                        <h1 class="font-serif text-3xl font-bold text-repaw-dark">Create an Account</h1>
                        <p class="text-repaw-text/80 mt-1">Let's get started!</p>
                    </div>

                    <div class="relative mb-4">
                        <span class="mui-icon text-repaw-dark absolute left-4 top-1/2 -translate-y-1/2 text-[20px]">person</span>
                        <input type="text" name="fname" id="fname" required placeholder="First Name"
                               class="w-full rounded-xl border border-repaw-hover bg-repaw-bg pl-12 pr-4 py-3 text-repaw-text placeholder-repaw-text/50 focus:outline-none focus:ring-2 focus:ring-repaw-text">
                    </div>

                    <div class="relative mb-4">
                        <span class="mui-icon text-repaw-dark absolute left-4 top-1/2 -translate-y-1/2 text-[20px]">person</span>
                        <input type="text" name="lname" id="lname" required placeholder="Last Name"
                               class="w-full rounded-xl border border-repaw-hover bg-repaw-bg pl-12 pr-4 py-3 text-repaw-text placeholder-repaw-text/50 focus:outline-none focus:ring-2 focus:ring-repaw-text">
                    </div>

                    <div class="relative mb-4">
                        <span class="mui-icon text-repaw-dark absolute left-4 top-1/2 -translate-y-1/2 text-[20px]">mail</span>
                        <input type="email" name="email" id="email" required placeholder="Email"
                               class="w-full rounded-xl border border-repaw-hover bg-repaw-bg pl-12 pr-4 py-3 text-repaw-text placeholder-repaw-text/50 focus:outline-none focus:ring-2 focus:ring-repaw-text">
                    </div>

                    <div class="relative mb-4">
                        <span class="mui-icon text-repaw-dark absolute left-4 top-1/2 -translate-y-1/2 text-[20px]">lock</span>
                        <input type="password" name="password" id="password" required placeholder="Password"
                               class="w-full rounded-xl border border-repaw-hover bg-repaw-bg pl-12 pr-12 py-3 text-repaw-text placeholder-repaw-text/50 focus:outline-none focus:ring-2 focus:ring-repaw-text">
                        <span class="mui-icon text-repaw-text/60 absolute right-4 top-1/2 -translate-y-1/2 text-[20px] cursor-pointer" data-toggle="password">visibility</span>
                    </div>

                    <div class="relative mb-4">
                        <span class="mui-icon text-repaw-dark absolute left-4 top-1/2 -translate-y-1/2 text-[20px]">lock</span>
                        <input type="password" name="cpassword" id="cpassword" required placeholder="Confirm Password"
                               class="w-full rounded-xl border border-repaw-hover bg-repaw-bg pl-12 pr-12 py-3 text-repaw-text placeholder-repaw-text/50 focus:outline-none focus:ring-2 focus:ring-repaw-text">
                        <span class="mui-icon text-repaw-text/60 absolute right-4 top-1/2 -translate-y-1/2 text-[20px] cursor-pointer" data-toggle="cpassword">visibility</span>
                    </div>

                    <button type="submit" name="register" value="Sign Up"
                            class="w-full mt-2 bg-repaw-text text-repaw-bg rounded-full px-6 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300">
                        Sign Up
                    </button>

                    <p class="text-center text-repaw-text/80 mt-6">Already have an Account?<br>
                        <a href="loginpage.php" class="text-repaw-dark font-medium underline underline-offset-2 hover:text-repaw-text">Log in</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script src="../script/script.js"></script>
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

        document.querySelectorAll('[data-toggle]').forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                var input = document.getElementById(toggle.getAttribute('data-toggle'));
                if (!input) return;
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                toggle.textContent = show ? 'visibility_off' : 'visibility';
            });
        });
    </script>
</body>

</html>
