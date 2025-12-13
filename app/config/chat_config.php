<?php
// Chat configuration for n8n integration
// Set your n8n webhook URL here
// You can get this from your n8n workflow webhook node

// n8n Webhook URL - Replace with your actual webhook URL
define('N8N_WEBHOOK_URL', getenv('N8N_WEBHOOK_URL') ?: 'https://your-n8n-instance.com/webhook/your-webhook-id');

// Timeout settings (in seconds)
define('CHAT_TIMEOUT', 30);

// Retry settings
define('CHAT_MAX_RETRIES', 3);
define('CHAT_RETRY_DELAY', 2); // seconds

// Chat settings
define('CHAT_ENABLED', true);
define('CHAT_BOT_NAME', 'Simpati Trans Assistant');
?>

