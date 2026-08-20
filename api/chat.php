<?php
header('Content-Type: application/json');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized. Please log in first.']);
    exit();
}

// Load configuration
$config_path = dirname(__DIR__) . '/config.php';
if (!file_exists($config_path)) {
    http_response_code(500);
    echo json_encode(['error' => 'Configuration file (config.php) is missing.']);
    exit();
}
require_once $config_path;

// Load API key from environment variable (for Vercel/Railway hosting) or fallback to config.php
$api_key = getenv('GEMINI_API_KEY');
if (empty($api_key) && defined('GEMINI_API_KEY')) {
    $api_key = GEMINI_API_KEY;
}

// Validate API Key
if (empty($api_key) || $api_key === 'YOUR_GEMINI_API_KEY_HERE') {
    echo json_encode([
        'error' => 'API Key not configured.',
        'setup_required' => true,
        'message' => 'To activate AgriAssist AI, please configure your Gemini API Key.'
    ]);
    exit();
}

// Get POST request body
$input = json_decode(file_get_contents('php://input'), true);
$user_message = isset($input['message']) ? trim($input['message']) : '';

if (empty($user_message)) {
    http_response_code(400);
    echo json_encode(['error' => 'Message cannot be empty.']);
    exit();
}

// System instructions to guide the model
$system_instruction = "You are AgriAssist AI, a helpful, friendly, and expert agricultural assistant. " .
                     "Your job is to help farmers diagnose crop diseases, choose the best crops for their soil type, " .
                     "suggest farming techniques, provide advice on fertilizers/pesticides, and answer weather/market queries. " .
                     "Provide precise, actionable, and easy-to-understand advice. If you do not know the answer to a highly local query, " .
                     "suggest contacting local agricultural extensions or experts.";

// Format payload for Gemini API (generateContent endpoint)
$payload = [
    'contents' => [
        [
            'parts' => [
                ['text' => $user_message]
            ]
        ]
    ],
    'systemInstruction' => [
        'parts' => [
            ['text' => $system_instruction]
        ]
    ]
];

// Set up cURL
$api_url = 'https://generativelanguage.googleapis.com/v1beta/models/' . (defined('GEMINI_MODEL') ? GEMINI_MODEL : 'gemini-2.5-flash') . ':generateContent?key=' . $api_key;

$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

// Execute and capture response
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

if ($curl_error) {
    http_response_code(500);
    echo json_encode(['error' => 'cURL error: ' . $curl_error]);
    exit();
}

if ($http_code !== 200) {
    http_response_code($http_code);
    $response_data = json_decode($response, true);
    $error_msg = isset($response_data['error']['message']) ? $response_data['error']['message'] : 'Failed to reach Gemini API';
    echo json_encode(['error' => $error_msg]);
    exit();
}

$response_data = json_decode($response, true);

// Extract generated text from response structure
if (isset($response_data['candidates'][0]['content']['parts'][0]['text'])) {
    $ai_reply = $response_data['candidates'][0]['content']['parts'][0]['text'];
    echo json_encode([
        'success' => true,
        'reply' => $ai_reply
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Invalid response structure from Gemini API.']);
}
?>
