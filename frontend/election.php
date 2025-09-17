<?php
include "config/nexus.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gamified Election Experience</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<style>
  .glow-btn { transition: 0.3s; }
  .glow-btn:hover { box-shadow: 0 0 15px #00f0ff, 0 0 30px #00f0ff; transform: translateY(-2px);}
  .candidate-btn { transition: all 0.3s; }
  .candidate-btn:hover { transform: scale(1.02); }
  .step-panel { min-height: 300px; }
</style>
</head>
<body class="bg-gray-100 dark:bg-gray-900">

  <div class="md:hidden flex items-center justify-between bg-white dark:bg-gray-800 px-4 py-3 shadow">
    <h1 class="text-lg font-bold text-gray-800 dark:text-white">
      Election Experience <span class="text-sm mb-1 text-black/80 dark:text-white/80">2.1</span>
    </h1>
    <button id="menu-toggle" class="text-gray-700 dark:text-gray-200 focus:outline-none">
      <i data-feather="menu"></i>
    </button>
  </div>

  <div class="flex min-h-screen">
    <?php aside(); ?>

    <main class="flex-1 p-6 max-w-5xl mx-auto space-y-6">

<div class="space-y-4">

  <h1 class="text-4xl md:text-5xl font-extrabold text-center bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-cyan-400 dark:from-indigo-400 dark:to-purple-400 animate-pulse">
    DEMO Election
  </h1>

  <div id="breadcrumb" class="flex justify-center md:justify-between text-sm md:text-base px-4 py-3 rounded-xl bg-gray-100 dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700">
    
    <span id="step-federal-breadcrumb" class="font-semibold text-blue-600 dark:text-cyan-400 px-3 py-1 rounded-lg transition-colors duration-300">
      Federal: President
    </span>

    <span id="step-state-breadcrumb" class="font-semibold text-gray-500 dark:text-gray-400 px-3 py-1 rounded-lg transition-colors duration-300">
      State: Governor
    </span>

  </div>

</div>

<div id="step-select-election" class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

  <button onclick="startElection('federal')" 
          class="group relative flex flex-col items-center justify-center p-6 rounded-2xl shadow-lg bg-gradient-to-r from-blue-500 to-cyan-500 hover:scale-105 hover:shadow-2xl transition-transform duration-300 text-white font-semibold overflow-hidden">
    
    <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/10 rounded-full animate-pulse"></div>
    <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-white/10 rounded-full animate-pulse delay-150"></div>

    <i data-feather="users" class="w-10 h-10 mb-4"></i>

    <h3 class="text-xl font-bold mb-1 text-center">Federal Election</h3>
    <p class="text-sm text-white/90 text-center">Vote for President, Senate & House of Representatives.</p>

    <span class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></span>
  </button>

  <button onclick="startElection('state')" 
          class="group relative flex flex-col items-center justify-center p-6 rounded-2xl shadow-lg bg-gradient-to-r from-indigo-500 to-purple-500 hover:scale-105 hover:shadow-2xl transition-transform duration-300 text-white font-semibold overflow-hidden">
    
    <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/10 rounded-full animate-pulse"></div>
    <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-white/10 rounded-full animate-pulse delay-150"></div>

    <i data-feather="map" class="w-10 h-10 mb-4"></i>

    <h3 class="text-xl font-bold mb-1 text-center">State Election</h3>
    <p class="text-sm text-white/90 text-center">Vote for Governor & State Assembly representatives.</p>

    <span class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></span>
  </button>

</div>

    <div id="federal-steps" class="space-y-6 hidden">
    <?php 
    $federal_order = ['president','senate','reps'];
    foreach($federal_order as $i => $step):
        $candidates = $federal_candidates[$step];
    ?>
      <div class="step hidden" id="federal-<?= $step ?>">
        <div class="flex flex-col lg:flex-row gap-6">
          <div class="flex-1 space-y-4">
            <h2 class="text-xl font-bold mb-2 capitalize dark:text-white"><?= $step ?> Candidates</h2>
            <div class="grid grid-cols-1 gap-4">
              <?php foreach($candidates as $c): ?>
              <button class="candidate-btn flex items-center gap-4 p-4 bg-gray-200 dark:bg-gray-800 rounded-xl border-2 border-transparent hover:border-cyan-500 dark:hover:border-cyan-400 focus:ring-2 focus:ring-cyan-400"
                      onclick="selectCandidate(this)">
                <div class="w-12 h-12 bg-gray-300 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-500">Img</div>
                <div class="flex-1 text-left">
                  <p class="font-semibold dark:text-white"><?= $c['name'] ?></p>
                  <p class="text-sm text-gray-500 dark:text-gray-300 flex items-center gap-2">
                    <span class="w-4 h-4 bg-gray-400 rounded-sm"></span>
                    <?= $c['party'] ?>
                  </p>
                </div>
              </button>
              <?php endforeach; ?>
            </div>

            <?php if($i < count($federal_order)-1):
              $next_step = $federal_order[$i+1]; ?>
              <button onclick="nextFederalStep('<?= $next_step ?>','<?= $step ?>')" class="w-full py-3 mt-4 font-semibold rounded-xl bg-green-500 hover:bg-green-600 text-white">
                ✅ Vote & Advance to <?= ucfirst($next_step) ?>
              </button>
            <?php else: ?>
              <button onclick="finishFederal('<?= $step ?>')" class="w-full py-3 mt-4 font-semibold rounded-xl bg-purple-500 hover:bg-purple-600 text-white">
                Finish Federal Election
              </button>
            <?php endif; ?>
          </div>

          <div class="flex-1 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl shadow-lg step-panel flex flex-col items-center justify-center">
            <h3 class="text-lg font-bold mb-2 dark:text-white">Selected Candidate</h3>
            <div id="selected-candidate-<?= $step ?>" class="flex flex-col items-center gap-4 opacity-50">
              <div class="w-24 h-24 bg-gray-300 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-500">Img</div>
              <p class="font-semibold dark:text-white">No candidate selected</p>
              <p class="text-sm text-gray-500 dark:text-gray-300">Party info will appear here</p>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
    </div>

    <div id="state-steps" class="space-y-6 hidden">
    <?php 
    $state_order = ['governor','assembly'];
    foreach($state_order as $i => $step):
        $candidates = $state_candidates[$step];
    ?>
      <div class="step hidden" id="state-<?= $step ?>">
        <div class="flex flex-col lg:flex-row gap-6">
          <div class="flex-1 space-y-4">
            <h2 class="text-xl font-bold mb-2 capitalize dark:text-white"><?= $step ?> Candidates</h2>
            <div class="grid grid-cols-1 gap-4">
              <?php foreach($candidates as $c): ?>
              <button class="candidate-btn flex items-center gap-4 p-4 bg-gray-200 dark:bg-gray-800 rounded-xl border-2 border-transparent hover:border-indigo-500 dark:hover:border-indigo-400 focus:ring-2 focus:ring-indigo-400"
                      onclick="selectCandidate(this)">
                <div class="w-12 h-12 bg-gray-300 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-500">Img</div>
                <div class="flex-1 text-left">
                  <p class="font-semibold dark:text-white"><?= $c['name'] ?></p>
                  <p class="text-sm text-gray-500 dark:text-gray-300 flex items-center gap-2">
                    <span class="w-4 h-4 bg-gray-400 rounded-sm"></span>
                    <?= $c['party'] ?>
                  </p>
                </div>
              </button>
              <?php endforeach; ?>
            </div>

            <?php if($i < count($state_order)-1):
              $next_step = $state_order[$i+1]; ?>
              <button onclick="nextStateStep('<?= $next_step ?>','<?= $step ?>')" class="w-full py-3 mt-4 font-semibold rounded-xl bg-green-500 hover:bg-green-600 text-white">
                ✅ Vote & Advance to <?= ucfirst($next_step) ?>
              </button>
            <?php else: ?>
              <button onclick="finishState('<?= $step ?>')" class="w-full py-3 mt-4 font-semibold rounded-xl bg-purple-500 hover:bg-purple-600 text-white">
                Finish State Election
              </button>
            <?php endif; ?>
          </div>

          <div class="flex-1 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl shadow-lg step-panel flex flex-col items-center justify-center">
            <h3 class="text-lg font-bold mb-2 dark:text-white">Selected Candidate</h3>
            <div id="selected-candidate-<?= $step ?>" class="flex flex-col items-center gap-4 opacity-50">
              <div class="w-24 h-24 bg-gray-300 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-500">Img</div>
              <p class="font-semibold dark:text-white">No candidate selected</p>
              <p class="text-sm text-gray-500 dark:text-gray-300">Party info will appear here</p>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
    </div>

    <div id="confirm-modal" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 opacity-0 pointer-events-none transition-opacity duration-300">
      <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-3xl shadow-2xl max-w-sm w-full text-center transform scale-95 transition-transform duration-300">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Confirm Your Choice</h3>
        <p class="text-gray-700 dark:text-gray-300 mb-4">Are you sure you want to VOTE this candidate? You won't be able to change it later.</p>
        
        <div id="confirm-candidate" class="mb-6 flex flex-col items-center gap-2 opacity-0 transition-opacity duration-300">
        </div>
        
        <div class="flex justify-around gap-4">
          <button id="confirm-yes" class="py-2 px-4 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold">Yes, Lock</button>
          <button id="confirm-no" class="py-2 px-4 bg-red-500 hover:bg-red-600 text-white rounded-xl font-semibold">Cancel</button>
        </div>
      </div>
    </div>

    <div id="completion-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
      <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-2xl shadow-xl max-w-sm w-full text-center">
        <h3 id="completion-title" class="text-lg font-bold text-gray-900 dark:text-white mb-2"></h3>
        <p id="completion-msg" class="text-gray-700 dark:text-gray-300 mb-4"></p>
        <button id="completion-ok" class="py-2 px-4 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold">Submit</button>
      </div>
    </div>

  </main>
</div>

<script>
feather.replace();
const menuToggle = document.getElementById('menu-toggle');
const sidebar = document.getElementById('sidebar');

menuToggle.addEventListener('click', () => {
  sidebar.classList.toggle('-translate-x-full');
});


function startElection(type){
    document.getElementById('step-select-election').classList.add('hidden');
    if(type==='federal'){
        document.getElementById('federal-steps').classList.remove('hidden');
        document.querySelector('#federal-steps .step').classList.remove('hidden');
        updateBreadcrumb('federal','president');
    } else {
        document.getElementById('state-steps').classList.remove('hidden');
        document.querySelector('#state-steps .step').classList.remove('hidden');
        updateBreadcrumb('state','governor');
    }
}

let pendingAction = null;
function nextFederalStep(nextStep, currentStep){
    const currentDiv = document.getElementById('federal-' + currentStep);
    const selected = currentDiv.querySelector('.candidate-btn.bg-green-200, .candidate-btn.dark\\:bg-green-900\\/30');
    if(!selected){ 
        showToast("Please select a candidate before proceeding."); 
        return; 
    }
    pendingAction = ()=>{
        currentDiv.classList.add('hidden'); 
        document.getElementById('federal-'+nextStep).classList.remove('hidden'); 
        updateBreadcrumb('federal', nextStep);
        showToast(`You voted for ${selected.querySelector('p.font-semibold').innerText}`);
    }
    showConfirmationModal(selected);
}

function finishFederal(currentStep){
    const currentDiv = document.getElementById('federal-' + currentStep);
    const selected = currentDiv.querySelector('.candidate-btn.bg-green-200, .candidate-btn.dark\\:bg-green-900\\/30');
    if(!selected){ 
        showToast("Please select a candidate before finishing."); 
        return; 
    }
    pendingAction = ()=>{
        showCompletionModal("Federal Election Completed!", `You successfully voted for ${selected.querySelector('p.font-semibold').innerText}`);
    }
    showConfirmationModal(selected);
}

function nextStateStep(nextStep, currentStep){
    const currentDiv = document.getElementById('state-' + currentStep);
    const selected = currentDiv.querySelector('.candidate-btn.bg-green-200, .candidate-btn.dark\\:bg-green-900\\/30');
    if(!selected){ 
        showToast("Please select a candidate before proceeding."); 
        return; 
    }
    pendingAction = ()=>{
        currentDiv.classList.add('hidden'); 
        document.getElementById('state-'+nextStep).classList.remove('hidden'); 
        updateBreadcrumb('state', nextStep);
        showToast(`You voted for ${selected.querySelector('p.font-semibold').innerText}`);
    }
    showConfirmationModal(selected);
}

function finishState(currentStep){
    const currentDiv = document.getElementById('state-' + currentStep);
    const selected = currentDiv.querySelector('.candidate-btn.bg-green-200, .candidate-btn.dark\\:bg-green-900\\/30');
    if(!selected){ 
        showToast("Please select a candidate before finishing."); 
        return; 
    }
    pendingAction = ()=>{
        showCompletionModal("State Election Completed!", `You successfully voted for ${selected.querySelector('p.font-semibold').innerText}`);
    }
    showConfirmationModal(selected);
}

function showToast(msg){
    let toast = document.createElement('div');
    toast.className = "fixed bottom-6 right-6 bg-green-500 text-white px-4 py-3 rounded-xl shadow-lg animate-slideIn";
    toast.innerText = msg;
    document.body.appendChild(toast);
    setTimeout(()=>{ toast.classList.add("opacity-0"); setTimeout(()=>toast.remove(),300); }, 2500);
}

function showCompletionModal(title, message){
    const modal = document.getElementById('completion-modal');
    modal.querySelector('#completion-title').innerText = title;
    modal.querySelector('#completion-msg').innerText = message;
    modal.classList.remove('hidden');
}

document.getElementById('completion-ok').addEventListener('click', ()=>{
    document.getElementById('completion-modal').classList.add('hidden');
    location.reload();
});


function showConfirmationModal(selectedBtn){
    const modal = document.getElementById('confirm-modal');
    const candidatePanel = modal.querySelector('#confirm-candidate');
    
    const name = selectedBtn.querySelector('p.font-semibold').innerText;
    const party = selectedBtn.querySelector('p.text-sm').innerText;

    candidatePanel.innerHTML = `
        <div class="w-24 h-24 bg-gray-300 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-500 mb-2">Img</div>
        <p class="font-semibold text-gray-900 dark:text-white">${name}</p>
        <p class="text-sm text-gray-600 dark:text-gray-300">${party}</p>
    `;

    modal.classList.remove('opacity-0', 'pointer-events-none');
    setTimeout(()=>{ candidatePanel.classList.add('opacity-100'); }, 50);
}

document.getElementById('confirm-no').addEventListener('click', ()=>{
    const modal = document.getElementById('confirm-modal');
    const candidatePanel = modal.querySelector('#confirm-candidate');

    candidatePanel.classList.remove('opacity-100');
    modal.classList.add('opacity-0', 'pointer-events-none');
    pendingAction=null;
});
document.getElementById('confirm-yes').addEventListener('click', ()=>{
    const modal = document.getElementById('confirm-modal');
    const candidatePanel = modal.querySelector('#confirm-candidate');

    candidatePanel.classList.remove('opacity-100');
    modal.classList.add('opacity-0', 'pointer-events-none');

    if(pendingAction) pendingAction();
    pendingAction=null;
});


function selectCandidate(btn) {
    const parent = btn.closest('.step');

    parent.querySelectorAll('.candidate-btn').forEach(b => {
        b.classList.remove(
            'border-cyan-500', 'dark:border-cyan-400',
            'border-indigo-500', 'dark:border-indigo-400',
            'bg-green-200', 'dark:bg-green-900/30'
        );
        const existingIcon = b.querySelector('.vote-icon');
        if (existingIcon) existingIcon.remove();
    });

    btn.classList.add('bg-green-200', 'dark:bg-green-900/30');

    const icon = document.createElement('span');
    icon.className = `
        vote-icon absolute right-4 top-1/2 -translate-y-1/2 
        w-6 h-6 flex items-center justify-center 
        text-green-600 dark:text-green-400 
        scale-0 transition-transform duration-300
    `;
    icon.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
        </svg>
    `;
    btn.style.position = 'relative';
    btn.appendChild(icon);

    setTimeout(() => icon.classList.add('scale-100'), 50);

    const name = btn.querySelector('p.font-semibold').innerText;
    const party = btn.querySelector('p.text-sm').innerText;
    const panel = parent.querySelector('[id^="selected-candidate"]');
    panel.innerHTML = `
        <div class="w-24 h-24 bg-gray-300 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-500 mb-2">Img</div>
        <p class="font-semibold dark:text-white">${name}</p>
        <p class="text-sm text-gray-500 dark:text-gray-300">${party}</p>
        <p class="text-green-600 dark:text-green-400 font-semibold mt-2 flex items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
          </svg>
          Vote
        </p>
    `;
    panel.classList.remove('opacity-50');
}


function updateBreadcrumb(type, step){
    const federalSteps = { president: 'President', senate: 'Senate', house: 'House' };
    const stateSteps = { governor: 'Governor', assembly: 'Assembly' };

    const fedBreadcrumb = document.getElementById('step-federal-breadcrumb');
    const stateBreadcrumb = document.getElementById('step-state-breadcrumb');

    if(type === 'federal'){
        fedBreadcrumb.innerText = 'Federal: ' + federalSteps[step];
        fedBreadcrumb.classList.remove('text-gray-500', 'dark:text-gray-400');
        fedBreadcrumb.classList.add('text-cyan-400');

        stateBreadcrumb.classList.remove('text-indigo-500', 'text-cyan-400');
        stateBreadcrumb.classList.add('text-gray-500', 'dark:text-gray-400');
    } else {
        stateBreadcrumb.innerText = 'State: ' + stateSteps[step];
        stateBreadcrumb.classList.remove('text-gray-500', 'dark:text-gray-400');
        stateBreadcrumb.classList.add('text-indigo-500');

        fedBreadcrumb.classList.remove('text-cyan-400', 'text-indigo-500');
        fedBreadcrumb.classList.add('text-gray-500', 'dark:text-gray-400');
    }
}

</script>
</body>
</html>
