<?php
// Digital Voting System – PHP test for getAPoll()
// Connects to Lisk and reads poll #1

$rpcUrl = "https://rpc.sepolia-api.lisk.com";
$contract = "0x94dd8c3f2Df10b43F7e3f1517A530F43cB8d609c";

// Encode function signature for getAPoll(uint256)
function encodeFunctionSignature($sig) {
    return "0x" . substr(keccak256($sig), 0, 8);
}

// Simple keccak256 helper using shell (Linux hosting)
function keccak256($input) {
    return hash('sha3-256', $input);
}

// 1️⃣ Prepare ABI-encoded call manually
// "getAPoll(uint256)" = 0x15d8c291  (already computed)
////////////////////////////REPLACEEMENT FOR DEBUG////////////////////////////////////////////
// getAllPolls()
// $functionSelector = "0x05e6a0ca"; // keccak256("getAllPolls()") first 4 bytes
// $data = $functionSelector;
//////////////////////////////////////////////////////////////////////////////////////////////
$functionSelector = "0x15d8c291";
// PollId = 1 (uint256 padded to 32 bytes)
$pollId = str_pad(dechex(1), 64, "0", STR_PAD_LEFT);
$data = $functionSelector . $pollId;

// 2️⃣ Build RPC request
$request = [
    "jsonrpc" => "2.0",
    "id" => 1,
    "method" => "eth_call",
    "params" => [[
        "to" => $contract,
        "data" => "0x" . substr($data, 2) // ensure no extra 0x
    ], "latest"]
];

// 3️⃣ Send request via cURL
$ch = curl_init($rpcUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
$response = curl_exec($ch);
curl_close($ch);

// 4️⃣ Decode and display
echo "<pre>";
print_r(json_decode($response, true));
echo "</pre>";
?>
