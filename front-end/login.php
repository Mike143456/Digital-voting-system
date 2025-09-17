<?php
include "config/nexus.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voter_id = trim($_POST['voter_id']);
    $fullname = trim($_POST['fullname']);

    if (loginUser($voter_id, $fullname)) {
        $_SESSION['notification'] = [
            "title" => "Login Successful 🎉",
            "body" => "Welcome back to IREV 2.1, $fullname!",
            "timeAgo" => "Just now",
            "icon" => "person-circle-outline"
        ];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid Voter's ID or Full Name!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>IREV 2.1 | Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.15.10/dist/css/uikit.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/uikit@3.15.10/dist/js/uikit.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/uikit@3.15.10/dist/js/uikit-icons.min.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

  <style>
    /* Floating Blobs */
    .blob {
      position: absolute;
      border-radius: 50%;
      filter: blur(90px);
      opacity: 0.5;
      animation: float 14s infinite ease-in-out;
      z-index: 0;
    }
    .blob1 { width: 300px; height: 300px; background: #3b82f6; top: -80px; left: -80px; animation-delay: 0s; }
    .blob3 { width: 260px; height: 260px; background: #93c5fd; top: 40%; left: 50%; transform: translate(-50%, -50%); animation-delay: 6s; }
    @keyframes float {
      0%, 100% { transform: translateY(0) scale(1); }
      50% { transform: translateY(-25px) scale(1.1); }
    }
    @keyframes fade-in-up {
      0% { opacity: 0; transform: translateY(20px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fade-in-up 0.4s ease-out; }
  </style>
</head>
<body class="relative h-screen flex items-center justify-center 
             bg-gradient-to-br from-blue-50 to-blue-100 
             dark:from-gray-900 dark:to-gray-800 
             overflow-hidden">


  <div class="blob blob1"></div>
  <div class="blob blob3"></div>

  <main class="relative z-10 w-full max-w-md mx-4 sm:mx-6 md:mx-0 my-6
             px-6 sm:px-8 py-8 
             bg-white/80 dark:bg-gray-900/90 backdrop-blur-xl 
             shadow-2xl rounded-2xl border border-gray-200 dark:border-gray-700">
    
    <div class="text-center m-6">
      <h2 class="text-4xl font-extrabold bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent">
        GRAVITAS
      </h2>
      <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm sm:text-base">
        IREV 2.1 Portal
      </p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="p-3 mb-4 rounded-lg bg-red-100 text-red-700 text-sm font-medium shadow-sm">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-5">
      <div>
        <label class="block text-gray-700 dark:text-gray-300 font-medium">Voter’s ID</label>
        <input type="text" name="voter_id" placeholder="Enter your Voter’s ID"
          class="w-full px-4 py-3 mt-1 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-gray-50 dark:bg-gray-800 dark:text-white transition"/>
      </div>

      <div>
        <label class="block text-gray-700 dark:text-gray-300 font-medium">Full Name</label>
        <input type="text" name="fullname" placeholder="Enter your Full Name"
          class="w-full px-4 py-3 mt-1 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-gray-50 dark:bg-gray-800 dark:text-white transition"/>
      </div>

      <button id="loginBtn" type="submit"
        class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 
               text-white py-3 rounded-xl font-semibold text-lg shadow-lg transform hover:scale-[1.02] 
               transition-all flex items-center justify-center gap-2">
        <span id="loginText">Login</span>
        <span id="spinner" class="hidden">
          <svg class="animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
          </svg>
        </span>
      </button>
    </form>
    <div class="text-center text-sm text-gray-600 dark:text-gray-400 space-y-3 pt-8">
        <p>Don’t have a Voter’s ID? 
          <button id="demoNotifyBtn" 
            class="text-blue-600 dark:text-blue-400 font-medium hover:underline">
            INEC Portal
          </button>
        </p>
      <p>
        <a href="dashboard.php" class="text-red-600/50 dark:text-orange-400/50 font-medium hover:underline italic">This is a DEMO NERCO hybrid DVS</a>
      </p>
    </div>
        <div id="notification" 
          class="hidden fixed left-6 right-6 bg-white dark:bg-gray-800 shadow-2xl rounded-xl p-4 
                 border border-gray-200 dark:border-gray-700 animate-fade-in-up">
          <div class="flex items-start gap-3">
            <ion-icon name="information-circle-outline" class="text-yellow-500 text-2xl"></ion-icon>
            <div>
              <p class="font-semibold text-gray-800 dark:text-gray-100">Demo Notice</p>
              <p class="text-gray-600 dark:text-gray-400 text-sm">
                This is a <span class="font-semibold">DEMO</span> — no redirect will happen, bro ✌️
              </p>
              <p class="text-gray-400 text-xs mt-1">Just now</p>
            </div>
          </div>
        </div>
  </main>

  <script>
    document.querySelector("form").addEventListener("submit", function(e) {
      let btn = document.getElementById("loginBtn");
      let text = document.getElementById("loginText");
      let spinner = document.getElementById("spinner");
      text.classList.add("hidden");
      spinner.classList.remove("hidden");
      btn.disabled = true;
    });

    document.getElementById("demoNotifyBtn").addEventListener("click", () => {
      const notify = document.getElementById("notification");
      notify.classList.remove("hidden");

      setTimeout(() => {
        notify.classList.add("hidden");
      }, 5000); // hides after 5s
    });
  </script>
</body>
</html>


