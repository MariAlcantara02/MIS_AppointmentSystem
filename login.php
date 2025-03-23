<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Logins</title>
   <script src="https://cdn.tailwindcss.com"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js" defer></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

   <!-- Navbar -->
   <nav class="bg-blue-800 shadow-md py-4">
       <div class="container mx-auto flex justify-between items-center px-4">
           <a href="#" class="flex items-center space-x-3 text-lg font-semibold text-gray-800">
               <img src="sanpablocityseal.png" alt="San Pablo City Seal" class="w-10 h-10">
               <span>San Pablo City Mega Capitol</span>
           </a>
           <div class="space-x-4">
               <a href="announcement.php" class="text-gray-700 hover:text-blue-500">Announcement</a>
               <a href="about.php" class="text-gray-700 hover:text-blue-500">About</a>
               <a href="gallery.php" class="text-gray-700 hover:text-blue-500">Gallery</a>
               <a href="index.php" class="text-gray-700 hover:text-blue-500">User</a>
           </div>
       </div>
   </nav>

   <!-- Login Forms Container -->
   <div class="flex-grow flex items-center justify-center px-4">
       <!-- Super Admin Login (Initially Hidden) -->
       <div id="super_admin" class="w-full max-w-md bg-white p-6 rounded-lg shadow-lg hidden">
           <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Super Admin</h1>
           <form method="post" action="login_process.php" class="space-y-4">
               <input type="hidden" name="role" value="super_admin"> <!-- ✅ Identifies role -->
               <div class="relative">
                   <i class="fas fa-user absolute left-3 top-3 text-gray-500"></i>
                   <input type="text" name="username" placeholder="Username" required 
                       class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
               </div>
               <div class="relative">
                   <i class="fas fa-lock absolute left-3 top-3 text-gray-500"></i>
                   <input type="password" name="password" placeholder="Password" required 
                       class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
               </div>
               <p class="text-right text-blue-500 text-sm"><a href="#">Recover Password</a></p>
               <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-lg font-semibold hover:bg-blue-600 transition">Log In</button>
           </form>

           <p class="text-center my-3 text-gray-500">or</p>

           <!-- Switch to Admin Login -->
           <div class="text-center">
               <button id="login_admin_btn" class="text-blue-500 font-semibold">Log In as Admin</button>
           </div>
       </div>

       <!-- Admin Login -->
       <div id="admin" class="w-full max-w-md bg-white p-6 rounded-lg shadow-lg">
           <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Admin</h1>
           <form method="post" action="login_process.php" class="space-y-4">
               <input type="hidden" name="role" value="admin"> <!-- ✅ Identifies role -->
               <div class="relative">
                   <i class="fas fa-user absolute left-3 top-3 text-gray-500"></i>
                   <input type="text" name="username" placeholder="Username" required 
                       class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
               </div>
               <div class="relative">
                   <i class="fas fa-lock absolute left-3 top-3 text-gray-500"></i>
                   <input type="password" name="password" placeholder="Password" required 
                       class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
               </div>
               <p class="text-right text-blue-500 text-sm"><a href="admin_forgot_password.php">Recover Password</a></p>
               <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-lg font-semibold hover:bg-blue-600 transition">Log In</button>
           </form>

           <p class="text-center my-3 text-gray-500">or</p>

           <!-- Switch to Super Admin Login -->
           <div class="text-center">
               <button id="login_super_btn" class="text-blue-500 font-semibold">Log In as Super Admin</button>
           </div>
       </div>
   </div>

   <!-- Footer -->
   <footer class="bg-white text-center py-4 shadow-md mt-5">
       <p class="text-gray-600">&copy; 2023 San Pablo City Mega Capitol. All rights reserved.</p>
   </footer>

   <!-- JavaScript for Form Switching -->
   <script>
       document.getElementById('login_admin_btn').addEventListener('click', function() {
           document.getElementById('super_admin').classList.add('hidden');
           document.getElementById('admin').classList.remove('hidden');
       });

       document.getElementById('login_super_btn').addEventListener('click', function() {
           document.getElementById('admin').classList.add('hidden');
           document.getElementById('super_admin').classList.remove('hidden');
       });
   </script>

</body>
</html>
