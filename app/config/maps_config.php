<?php
// Google Maps API Configuration
// Get your API key from: https://console.cloud.google.com/google/maps-apis
// Make sure to enable "Places API" and "Maps JavaScript API" in your Google Cloud Console

// Google Maps API Key - Replace with your actual API key
// You can also set it via environment variable: GOOGLE_MAPS_API_KEY
define('GOOGLE_MAPS_API_KEY', getenv('GOOGLE_MAPS_API_KEY') ?: 'YOUR_API_KEY');

// API Configuration
define('MAPS_DEFAULT_LAT', -5.1477); // Default latitude (Makassar)
define('MAPS_DEFAULT_LNG', 119.4327); // Default longitude (Makassar)
define('MAPS_DEFAULT_ZOOM', 13); // Default zoom level
?>

