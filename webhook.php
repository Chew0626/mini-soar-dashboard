<?php
// 1. Tell the browser we are sending back JSON data
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

// 2. Receive the simulated alert
$jsonInput = file_get_contents('php://input');
$alertData = json_decode($jsonInput, true);

// Check if we actually got a URL
if (!isset($alertData['url'])) {
    echo json_encode(["status" => "error", "message" => "No URL provided"]);
    exit;
}

$urlToScan = $alertData['url'];

// 3. SOAR AUTOMATION: Connect to VirusTotal securely
require 'config.php'; // This magically loads your $apiKey from the other file!

// We use parse_url to get just the domain (e.g., internetbadguys.com)
$domain = parse_url($urlToScan, PHP_URL_HOST);

// If the user forgot to type "http://", fallback to using exactly what they typed
if (!$domain) {
    $domain = $urlToScan; 
}

$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => "https://www.virustotal.com/api/v3/domains/" . $domain,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => [
    "accept: application/json",
    "x-apikey: " . $apiKey
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

// 4. SOAR RESPONSE: Decide what to do based on the results
if ($err) {
    echo json_encode(["status" => "error", "message" => "Failed to reach VirusTotal"]);
} else {
    $vtData = json_decode($response, true);
    
    // Check how many security vendors flagged this as malicious
    $maliciousVotes = $vtData['data']['attributes']['last_analysis_stats']['malicious'] ?? 0;
    
    if ($maliciousVotes > 0) {
        $action = "BLOCK_IP_AND_ISOLATE_HOST";
        $status = "CRITICAL THREAT";
        $color = "text-red-500";
    } else {
        $action = "IGNORE_FALSE_ALARM";
        $status = "SAFE";
        $color = "text-green-500";
    }

    // Send the final "Playbook" result back to our dashboard
    echo json_encode([
        "incident" => "Suspicious Link Clicked",
        "url_scanned" => $urlToScan,
        "threat_level" => $status,
        "action_taken" => $action,
        "color_code" => $color
    ]);
}
?>