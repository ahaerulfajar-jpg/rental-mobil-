<?php
// Chat configuration for n8n integration
// Set your n8n webhook URL here
// You can get this from your n8n workflow webhook node

// n8n Webhook URL - Replace with your actual webhook URL
// For Docker setup, n8n service name is 'n8n', so use: http://n8n:5678/webhook/chat
// For local development (outside Docker), use: http://localhost:5678/webhook/chat
// You can also set it via environment variable: N8N_WEBHOOK_URL (highest priority)

// Auto-detect Docker environment
$isDocker = file_exists('/.dockerenv');

// Default URL: use Docker service name if in Docker, otherwise localhost
$defaultUrl = $isDocker ? 'http://n8n:5678/webhook/chat' : 'http://localhost:5678/webhook/chat';

// Use environment variable if set, otherwise use auto-detected default
$n8nWebhookUrl = getenv('N8N_WEBHOOK_URL') ?: $defaultUrl;
define('N8N_WEBHOOK_URL', $n8nWebhookUrl);

// Timeout settings (in seconds)
define('CHAT_TIMEOUT', 30);

// Retry settings
define('CHAT_MAX_RETRIES', 3);
define('CHAT_RETRY_DELAY', 2); // seconds

// Chat settings
define('CHAT_ENABLED', true);
define('CHAT_BOT_NAME', 'Simpati Trans Assistant');
?>

