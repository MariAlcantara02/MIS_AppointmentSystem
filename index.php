<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js" defer></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-white shadow-md py-4">
        <div class="container mx-auto flex justify-between items-center px-4">
            <a href="#" class="flex items-center space-x-3 text-lg font-semibold text-gray-800">
                <img src="sanpablocityseal.png" alt="San Pablo City Seal" class="w-10 h-10">
                <span>San Pablo City Mega Capitol</span>
            </a>
            <div class="space-x-4">
                <a href="announcement.php" class="text-gray-700 hover:text-blue-500">Announcement</a>
                <a href="about.php" class="text-gray-700 hover:text-blue-500">About</a>
                <a href="gallery.php" class="text-gray-700 hover:text-blue-500">Gallery</a>
                <a href="login.php" class="text-gray-700 hover:text-blue-500">Admin</a>
            </div>
        </div>
    </nav>

    <!-- Login & Register Forms -->
    <div class="flex-grow flex items-center justify-center px-4">
        <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-lg">
            <h1 id="form-title" class="text-2xl font-bold text-gray-800 mb-6 text-center">Sign In</h1>

            <!-- Sign In Form -->
            <form id="sign_in" method="post" action="register.php" class="space-y-4">
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3 top-3 text-gray-500"></i>
                    <input type="email" name="email_address" placeholder="Email Address" required 
                        class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-3 text-gray-500"></i>
                    <input type="password" name="password" placeholder="Password" required 
                        class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <p class="text-right text-blue-500 text-sm"><a href="#">Recover Password</a></p>
                <button type="submit" name="sign_in" class="w-full bg-blue-500 text-white p-3 rounded-lg font-semibold hover:bg-blue-600 transition">Sign In</button>
            </form>

            <p class="text-center my-3 text-gray-500">or</p>

            <!-- Social Icons -->
            <div class="flex justify-center space-x-4">
                <i class="fab fa-google text-red-500 text-2xl cursor-pointer"></i>
                <i class="fab fa-facebook text-blue-600 text-2xl cursor-pointer"></i>
            </div>

            <!-- Switch to Sign Up -->
            <p class="text-center mt-4 text-gray-700">Don't Have an Account? <button id="sign_up_btn" class="text-blue-500 font-semibold">Sign Up</button></p>
        </div>

        <!-- Sign Up Form (Initially Hidden) -->
        <div id="sign_up" class="w-full max-w-md bg-white p-6 rounded-lg shadow-lg hidden">
            <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Register</h1>

            <form method="post" action="register.php" class="space-y-4">
                <div class="relative">
                    <i class="fas fa-user absolute left-3 top-3 text-gray-500"></i>
                    <input type="text" name="first_name" placeholder="First Name" required 
                        class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div class="relative">
                    <i class="fas fa-user absolute left-3 top-3 text-gray-500"></i>
                    <input type="text" name="last_name" placeholder="Last Name" required 
                        class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3 top-3 text-gray-500"></i>
                    <input type="email" name="email_address" placeholder="Email Address" required 
                        class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-3 text-gray-500"></i>
                    <input type="password" name="password" placeholder="Password" required 
                        class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <button type="submit" name="sign_up" class="w-full bg-blue-500 text-white p-3 rounded-lg font-semibold hover:bg-blue-600 transition">Sign Up</button>
            </form>

            <p class="text-center my-3 text-gray-500">or</p>

            <!-- Social Icons -->
            <div class="flex justify-center space-x-4">
                <i class="fab fa-google text-red-500 text-2xl cursor-pointer"></i>
                <i class="fab fa-facebook text-blue-600 text-2xl cursor-pointer"></i>
            </div>

            <!-- Switch to Sign In -->
            <p class="text-center mt-4 text-gray-700">Already Have an Account? <button id="sign_in_btn" class="text-blue-500 font-semibold">Sign In</button></p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white text-center py-4 shadow-md mt-5">
        <p class="text-gray-600">&copy; 2023 San Pablo City Mega Capitol. All rights reserved.</p>
    </footer>

    <script>
        document.getElementById('sign_up_btn').addEventListener('click', function() {
            document.getElementById('sign_in').classList.add('hidden');
            document.getElementById('sign_up').classList.remove('hidden');
            document.getElementById('form-title').innerText = "Register";
        });

        document.getElementById('sign_in_btn').addEventListener('click', function() {
            document.getElementById('sign_up').classList.add('hidden');
            document.getElementById('sign_in').classList.remove('hidden');
            document.getElementById('form-title').innerText = "Sign In";
        });
    </script>

</body>
</html>
