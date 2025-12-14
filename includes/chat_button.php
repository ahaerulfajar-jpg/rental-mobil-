<?php
// Chat Button Component
// Include this file in all guest pages to show the floating chat button
?>

<!-- Chat Floating Button -->
<button class="chat-floating-button" aria-label="Buka Chat">
    <i class="fa-solid fa-comments"></i>
</button>

<!-- Chat Popup Window -->
<div class="chat-popup">
    <div class="chat-popup-header">
        <h3>
            <span class="chat-status"></span>
            Simpati Trans Assistant
        </h3>
        <button class="chat-close" aria-label="Tutup Chat">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
    <div class="chat-popup-body">
        <!-- Messages will be inserted here by JavaScript -->
    </div>
    <div class="chat-popup-footer">
        <div class="chat-input-wrapper">
            <input 
                type="text" 
                class="chat-input" 
                placeholder="Ketik pesan Anda di sini..." 
                autocomplete="off"
            >
        </div>
        <button class="chat-send-button" aria-label="Kirim Pesan">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </div>
</div>

<!-- Chat CSS -->
<link rel="stylesheet" href="css/chat.css">

<!-- Chat JavaScript -->
<script src="js/chat.js"></script>

