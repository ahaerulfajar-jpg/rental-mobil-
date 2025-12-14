<?php
// Google Maps API Configuration
// Get your API key from: https://console.cloud.google.com/google/maps-apis
// Make sure to enable "Places API" and "Maps JavaScript API" in your Google Cloud Console
//
// IMPORTANT: If you get "ApiTargetBlockedMapError", check:
// 1. API key restrictions in Google Cloud Console
//    - Go to: APIs & Services > Credentials > Your API Key
//    - Under "Application restrictions", make sure:
//      * "HTTP referrers (web sites)" is selected
//      * Add your domain: localhost/* (for development)
//      * Add your production domain: yourdomain.com/*
// 2. Enable these APIs in Google Cloud Console:
//    - Places API
//    - Maps JavaScript API
// 3. Check billing is enabled in your Google Cloud project
// 4. Verify API key is not expired or revoked

// Google Maps API Key - Replace with your actual API key
// You can also set it via environment variable: GOOGLE_MAPS_API_KEY
$apiKey = getenv('GOOGLE_MAPS_API_KEY') ?: "AIzaSyDJ8ZoaewEEXhLCCLpDkc4f1eETAn-8UBE";

// Validate API key format (basic check)
if (empty($apiKey) || $apiKey === 'YOUR_API_KEY') {
    error_log('Warning: Google Maps API key is not set or using default value');
}

define('GOOGLE_MAPS_API_KEY', $apiKey);

// API Configuration
define('MAPS_DEFAULT_LAT', -5.1477); // Default latitude (Makassar)
define('MAPS_DEFAULT_LNG', 119.4327); // Default longitude (Makassar)
define('MAPS_DEFAULT_ZOOM', 13); // Default zoom level
?>

