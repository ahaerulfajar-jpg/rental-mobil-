<?php
// Start session before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/chat_config.php';

// Only allow POST requests for sending messages
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed. Use POST to send messages.'
    ]);
    exit;
}

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate input
if (!isset($data['message']) || empty(trim($data['message']))) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Message is required'
    ]);
    exit;
}

// Get user session info if available
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$user_name = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Guest';
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

// Prepare data to send to n8n
$payload = [
    'message' => trim($data['message']),
    'timestamp' => date('Y-m-d H:i:s'),
    'user' => [
        'id' => $user_id,
        'name' => $user_name,
        'email' => $user_email,
        'is_guest' => $user_id === null
    ],
    'session_id' => session_id()
];

// Add conversation history if provided
if (isset($data['conversation_history']) && is_array($data['conversation_history'])) {
    $payload['conversation_history'] = $data['conversation_history'];
}

// Send to n8n webhook
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, N8N_WEBHOOK_URL);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen(json_encode($payload))
]);
curl_setopt($ch, CURLOPT_TIMEOUT, CHAT_TIMEOUT);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

// Retry logic
$max_retries = CHAT_MAX_RETRIES;
$retry_delay = CHAT_RETRY_DELAY;
$response = false;
$http_code = 0;

for ($attempt = 1; $attempt <= $max_retries; $attempt++) {
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    
    if ($response !== false && $http_code >= 200 && $http_code < 300) {
        break;
    }
    
    // If not last attempt, wait before retrying
    if ($attempt < $max_retries) {
        sleep($retry_delay);
    }
}

curl_close($ch);

// Handle response
if ($response === false || $http_code < 200 || $http_code >= 300) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to connect to chat service. Please try again later.',
        'details' => $curl_error ?? 'Unknown error'
    ]);
    exit;
}

// Parse n8n response
$n8n_response = json_decode($response, true);

// If n8n returns an error
if (isset($n8n_response['error'])) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $n8n_response['error']
    ]);
    exit;
}

// Return success response
// n8n can return the response in various formats, so we handle common cases
$bot_message = '';
if (isset($n8n_response['message'])) {
    $bot_message = $n8n_response['message'];
} elseif (isset($n8n_response['response'])) {
    $bot_message = $n8n_response['response'];
} elseif (isset($n8n_response['text'])) {
    $bot_message = $n8n_response['text'];
} elseif (is_string($n8n_response)) {
    $bot_message = $n8n_response;
} elseif (is_array($n8n_response) && count($n8n_response) > 0) {
    // If it's an array, try to get the first meaningful value
    $bot_message = json_encode($n8n_response);
}

// If still no message, use the raw response
if (empty($bot_message)) {
    $bot_message = $response;
}

echo json_encode([
    'success' => true,
    'message' => $bot_message,
    'timestamp' => date('Y-m-d H:i:s')
]);
?>

