<?php
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/db.php';

function go($sql) {
    global $pdo;
    return $pdo->prepare($sql);
}

$federal_candidates = [
    'president' => [
        ['name'=>'Candidate A','party'=>'Party XPP'],
        ['name'=>'Candidate B','party'=>'Party YPY'],
        ['name'=>'Candidate C','party'=>'Party ZDP']
    ],
    'senate' => [
        ['name'=>'Candidate A','party'=>'Party GNPP'],
        ['name'=>'Candidate B','party'=>'Party YDC'],
        ['name'=>'Candidate C','party'=>'Party AZZ']
    ],
    'reps' => [
        ['name'=>'Candidate A','party'=>'Party TXT'],
        ['name'=>'Candidate B','party'=>'Party FDT'],
        ['name'=>'Candidate C','party'=>'Party ZDP']
    ]
];

$state_candidates = [
    'governor' => [
        ['name'=>'Candidate A','party'=>'Party MWK'],
        ['name'=>'Candidate B','party'=>'Party NNPP'],
        ['name'=>'Candidate C','party'=>'Party ONDP']
    ],
    'assembly' => [
        ['name'=>'Candidate A','party'=>'Party LP'],
        ['name'=>'Candidate B','party'=>'Party NNPP'],
        ['name'=>'Candidate C','party'=>'Party ONDP']
    ]
];

function custom_echo($x, $length){
  if(strlen($x)<=$length){
    echo $x;
  }else{
    $y=substr($x,0,$length) . '***';
    echo $y;
  }
}

function loggedin() {
    return isset($_SESSION["user_id"]);
}

function logoutUser() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_unset(); 
    session_destroy();
    http_response_code(200);
    return true;
}

function loginUser($v_id, $name) {
    global $pdo;
    $sql = "SELECT * FROM users WHERE voter_id = ? AND fullname = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$v_id, $name]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user'] = $user;
        return true;
    }
    return false;
}

function timeSince($timestamp){
  $datetime1=new DateTime("now");
  $datetime2=date_create($timestamp);
  $diff=date_diff($datetime1, $datetime2);
  $timemsg='';
  if($diff->y > 0){
    $timemsg = $diff->y .' year'. ($diff->y > 1?"s":'');
  }else if($diff->m > 0){
    $timemsg = $diff->m . ' month'. ($diff->m > 1?"s":'');
  }else if($diff->d > 0){
    $timemsg = $diff->d .' day'. ($diff->d > 1?"s":'');
  }else if($diff->h > 0){
    $timemsg = $diff->h .' hr'.($diff->h > 1 ? "s":'');
  }else if($diff->i > 0){
    $timemsg = $diff->i .' min'. ($diff->i > 1?"s":'');
  }else if($diff->s > 0){
    $timemsg = $diff->s .' sec'. ($diff->s > 1?"s":'');
  }
  $timemsg = $timemsg.' ago';
  return $timemsg;
}

function aside(){
?>
<aside id="sidebar" class="fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 w-64 bg-white dark:bg-gray-800 shadow-lg flex-col transition-transform duration-200 z-40">
  <div class="p-6 border-b border-gray-200 dark:border-gray-700">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">IREV 2.1</h2>
  </div>

  <nav class="flex-1 px-4 py-6 space-y-2">
    <a href="dashboard.php" class="flex items-center p-2 rounded-lg text-gray-700 dark:text-gray-200 
      hover:bg-green-100 hover:text-green-700 
      dark:hover:bg-yellow-600 dark:hover:text-yellow-200 no-underline transition">
      <i data-feather="home" class="mr-3"></i> Dashboard
    </a>

    <a href="election.php" class="flex items-center p-2 rounded-lg text-gray-700 dark:text-gray-200 
      hover:bg-green-100 hover:text-green-700 
      dark:hover:bg-yellow-600 dark:hover:text-yellow-200 no-underline transition">
      <i data-feather="file-text" class="mr-3"></i> Elections
    </a>

    <!--<a href="#" class="flex items-center p-2 rounded-lg text-gray-700 dark:text-gray-200 -->
    <!--  hover:bg-green-100 hover:text-green-700 -->
    <!--  dark:hover:bg-yellow-600 dark:hover:text-yellow-200 no-underline transition">-->
    <!--  <i data-feather="users" class="mr-3"></i> Voter Records-->
    <!--</a>-->

    <!--<a href="#" class="flex items-center p-2 rounded-lg text-gray-700 dark:text-gray-200 -->
    <!--  hover:bg-green-100 hover:text-green-700 -->
    <!--  dark:hover:bg-yellow-600 dark:hover:text-yellow-200 no-underline transition">-->
    <!--  <i data-feather="activity" class="mr-3"></i> Results-->
    <!--</a>-->

    <!--<a href="#" class="flex items-center p-2 rounded-lg text-gray-700 dark:text-gray-200 -->
    <!--  hover:bg-green-100 hover:text-green-700 -->
    <!--  dark:hover:bg-yellow-600 dark:hover:text-yellow-200 no-underline transition">-->
    <!--  <i data-feather="settings" class="mr-3"></i> Settings-->
    <!--</a>-->
  </nav>

  <div class="p-4 border-t border-gray-200 dark:border-gray-700">
    <button onclick="window.location.href='logout.php'" 
        class="w-full flex items-center p-2 text-red-600 dark:text-red-400 
         hover:bg-green-100 hover:text-green-700 
         dark:hover:bg-yellow-600 dark:hover:text-yellow-200 
         rounded-lg text-left transition no-underline">
      <i data-feather="log-out" class="mr-3"></i> Logout
    </button>

  </div>
</aside>
<?php
}




// INSERT INTO `rev_users` (`id`, `v_id`, `name`, `public_key`, `credential`, `date_added`) VALUES (NULL, '202567890', 'Kalaiyo Godwin', '023456789', '0123456789', '2025-09-15 15:54:13.000000');


?>
  























