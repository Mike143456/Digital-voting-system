<?php
include "config/nexus.php";


if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'logout') {
    logoutUser();
    exit;
}

$user = $_SESSION['user']; 

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IREV 2.1 | GRAVITAS</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.15.10/dist/css/uikit.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/uikit@3.15.10/dist/js/uikit.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/uikit@3.15.10/dist/js/uikit-icons.min.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900">

  <!-- Mobile Navbar -->
  <div class="md:hidden flex items-center justify-between bg-white dark:bg-gray-800 px-4 py-3 shadow">
    <h1 class="text-lg font-bold text-gray-800 dark:text-white">GRAVITAS <span class="text-sm mb-1 text-black/80 dark:text-white/80">2.1</span></h1>
    <button id="menu-toggle" class="text-gray-700 dark:text-gray-200 focus:outline-none">
      <i data-feather="menu"></i>
    </button>
  </div>

  <div class="flex min-h-screen">
<?php aside(); ?>


    <main class="flex-1 p-6 md:ml-24 md:mr-24">
	<header class="flex items-center justify-between mb-8">
	  
	  <div class="flex items-center gap-4 bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-500 
	              text-white rounded-3xl shadow-lg px-5 py-5 w-full md:w-auto relative overflow-hidden">
	    
	    <div class="absolute -top-6 -right-6 w-24 h-24 bg-blue-400 opacity-20 rounded-full blur-2xl"></div>
	    <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-indigo-400 opacity-20 rounded-full blur-2xl"></div>
	    
	    <div class="flex-shrink-0 bg-white/20 backdrop-blur-sm rounded-full px-1.5 shadow-md">
	      <ion-icon name="person-circle" class="mt-1.5 text-4xl text-white drop-shadow"></ion-icon>
	    </div>
	    
	    <div>
	      <h3 class="text-2xl font-bold tracking-tight"><span class="px-2 py-0.5 rounded-md text-white"><?= htmlspecialchars($user['fullname']) ?></span>
	      </h3>
	      <p class="text-sm mt-1 text-white/80">Your voter profile is secure and synced.</p>
	    </div>
	  </div>

	</header>

	<section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
	  <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
	    <h2 class="text-gray-500 text-sm">Your Voter ID</h2>
	    
	    <div class="flex-1 items-center justify-between mt-2 cursor-pointer">
	      <div class="flex items-center">
	      	<p class="text-xl font-bold text-gray-800 dark:text-white">
	        <?= htmlspecialchars($user['voter_id']); ?>
	      </p>
	      <span class="flex items-center justify-center w-4 h-4 rounded-full bg-green-500 text-white ml-1.5"
	            title="Verified by INEC">
	        <ion-icon name="checkmark-outline" class="text-sm"></ion-icon>
	      </span>
	      </div>
	      <div class="flex items-center w-full justify-center" onclick="toggleDetails(this)">
	      <!-- Arrow -->
	      <ion-icon name="chevron-down-outline" class="text-gray-500 dark:text-gray-300 transition-transform duration-300"></ion-icon>
	      </div>
	    </div>
	    
	    <!-- Collapsible details -->
	    <div class="voter-details mt-3 hidden text-gray-700 dark:text-gray-300 text-sm space-y-1">
	      <p><span class="font-semibold">Full Name:</span> <?= htmlspecialchars($user['fullname']); ?></p>
	      <p><span class="font-semibold">Polling Unit:</span> <?= htmlspecialchars($user['polling_unit'] ?? 'N/A'); ?></p>
	      <p><span class="font-semibold">Ward:</span> <?= htmlspecialchars($user['ward'] ?? 'N/A'); ?></p>
	      <p><span class="font-semibold">LGA:</span> <?= htmlspecialchars($user['lga'] ?? 'N/A'); ?></p>
	      <p><span class="font-semibold">State:</span> <?= htmlspecialchars($user['state'] ?? 'N/A'); ?></p>
	      <p><span class="font-semibold">DofB:</span> <?= htmlspecialchars($user['dob'] ?? 'N/A'); ?></p>
	    </div>
	  </div>

		<div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 relative">
		  <div class="flex items-center justify-between">
		    <h2 class="text-gray-500 text-sm">Public Key</h2>
		    <!-- Copy button -->
		    <button id="copyBtn" onclick="copyPublicKey()" 
		      class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
		      <ion-icon id="copyIcon" name="copy-outline" class="text-lg"></ion-icon>
		    </button>
		  </div>

			<p id="publicKeyText" class="text-sm text-gray-800 dark:text-white break-words mt-2">
			  <?= !empty($user['public_key']) ? htmlspecialchars(custom_echo($user['public_key'], 5)) : '57357537357357'; ?>
			</p>

		</div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 hidden">
          <h2 class="text-gray-500 text-sm">Date Added</h2>
          <p class="text-xl font-bold text-gray-800 dark:text-white mt-2">
            <?= timeSince(htmlspecialchars($user['date_added'])); ?>
          </p>
        </div>
      </section>

		<section class="grid grid-cols-1 md:grid-cols-2 gap-6">
		  <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-100 dark:border-gray-700">
		    <!-- Header -->
		    <div class="flex items-center justify-between mb-4">
		      <h2 class="text-lg font-extrabold text-gray-800 dark:text-white flex items-center gap-2">
		        <ion-icon name="pulse-outline" class="text-blue-500 text-xl"></ion-icon>
		        Election Feed
		      </h2>
		      <button class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
		        View All
		      </button>
		    </div>

		    <div class="space-y-4">
		      <div class="flex items-start gap-3 p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
		        <ion-icon name="cloud-upload-outline" class="text-blue-600 dark:text-blue-400 text-lg mt-1"></ion-icon>
		        <div>
		          <p class="text-sm font-medium text-gray-800 dark:text-white">
		            Polling Unit 32 <span class="font-semibold">uploaded results</span>
		          </p>
		          <span class="text-xs text-gray-500 dark:text-gray-400">2 mins ago</span>
		        </div>
		      </div>

		      <div class="flex items-start gap-3 p-3 bg-green-50 dark:bg-green-900/30 rounded-lg">
		        <ion-icon name="checkmark-done-outline" class="text-green-600 dark:text-green-400 text-lg mt-1"></ion-icon>
		        <div>
		          <p class="text-sm font-medium text-gray-800 dark:text-white">
		            <span class="font-semibold">450 new voters</span> verified
		          </p>
		          <span class="text-xs text-gray-500 dark:text-gray-400">10 mins ago</span>
		        </div>
		      </div>

		      <div class="flex items-start gap-3 p-3 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg">
		        <ion-icon name="newspaper-outline" class="text-yellow-600 dark:text-yellow-400 text-lg mt-1"></ion-icon>
		        <div>
		          <p class="text-sm font-medium text-gray-800 dark:text-white">
		            Electoral reform <span class="font-semibold">draft released</span>
		          </p>
		          <span class="text-xs text-gray-500 dark:text-gray-400">30 mins ago</span>
		        </div>
		      </div>
		    </div>
		  </div>
		</section>

    </main>
  </div>

<footer class="bg-white dark:bg-gray-800 text-center py-4 text-gray-600 dark:text-gray-400 text-sm w-full">
  &copy; <?= date("Y") ?> | GRAVITAS
</footer>



  <script>
    feather.replace();
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("menu-toggle");
    toggleBtn.addEventListener("click", () => {
      sidebar.classList.toggle("-translate-x-full");
    });
	  async function logoutUser() {
	    const res = await fetch('logout.php', { method: 'POST' });
	    if (res.ok) {
	      window.location.href = "login.php";
	    } else {
	      alert("Logout failed. Try again.");
	    }
	  }
function copyPublicKey() {
  const keyText = "<?= addslashes($_SESSION['user']['public_key'] ?? '') ?>";
  const copyIcon = document.getElementById("copyIcon");
  const copyBtn  = document.getElementById("copyBtn");

  navigator.clipboard.writeText(keyText).then(() => {
    copyIcon.setAttribute("name", "checkmark-outline");
    copyBtn.classList.add("text-green-600", "dark:text-green-400");

    UIkit.notification({
      message: "<span class='text-green-600 dark:text-green-400 font-semibold'>✔ Copied to clipboard</span>",
      pos: "top-center",
      timeout: 2000
    });

    setTimeout(() => {
      copyIcon.setAttribute("name", "copy-outline");
      copyBtn.classList.remove("text-green-600", "dark:text-green-400");
    }, 2000);

  }).catch(() => {
    UIkit.notification({
      message: "<span class='text-red-600 dark:text-red-400 font-semibold'>❌ Failed to copy</span>",
      pos: "top-center",
      timeout: 2000
    });
  });
}


		function toggleDetails(el) {
		  const card = el.closest('div.bg-white'); // find the closest card
		  const details = card.querySelector('.voter-details');
		  const arrow = el.querySelector('ion-icon');
		  if(details) details.classList.toggle('hidden');
		  if(arrow) arrow.classList.toggle('rotate-180');
		}

    function showNotification(title, body, timeAgo, icon = null, delay = 0) {
        let iconHTML = icon ? `<div class='rounded-full bg-slate-200 p-2 inline-flex ring ring-slate-100 ring-offset-1'>
                                <ion-icon name='${icon}' class='text-xl text-slate-600 drop-shadow-md'></ion-icon>
                              </div>` : '';

        setTimeout(() => {
            UIkit.notification({
                message: `<div class='flex gap-4 items-center'>
                            ${iconHTML}
                            <div class='flex-1'>
                                <strong>${title}</strong><br>
                                <span>${body}</span><br>
                                <small class='text-gray-500 dark:text-gray-400'>${timeAgo}</small>
                            </div>
                          </div>`,
                pos: 'bottom-right',
                timeout: 4000
            });
        }, delay);
    }

    showNotification("Electoral Reform", "New reform draft released.", "30 mins ago", "document-text-outline", 9000);

    <?php if (!empty($_SESSION['notification'])): 
        $title = htmlspecialchars($_SESSION['notification']['title']);
        $body = htmlspecialchars($_SESSION['notification']['body']);
        $timeAgo = htmlspecialchars($_SESSION['notification']['timeAgo']);
        $icon = htmlspecialchars($_SESSION['notification']['icon']);
    ?>
        showNotification("<?= $title ?>", "<?= $body ?>", "<?= $timeAgo ?>", "<?= $icon ?>", 1000);
        <?php unset($_SESSION['notification']); ?>
    <?php endif; ?>
  </script>
</body>
</html>


