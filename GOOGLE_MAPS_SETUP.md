# Setup Google Maps API - Mengatasi Error ApiTargetBlockedMapError

## Error yang Terjadi
Jika Anda melihat error `ApiTargetBlockedMapError` di console browser, ini berarti API key Google Maps Anda memiliki masalah konfigurasi.

## Solusi

### 1. Periksa API Key Restrictions

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Pilih project Anda
3. Pergi ke **APIs & Services** > **Credentials**
4. Klik pada API key Anda
5. Di bagian **Application restrictions**, pastikan:
   - Pilih **HTTP referrers (web sites)**
   - Tambahkan referrer berikut:
     ```
     localhost/*
     localhost:8080/*
     127.0.0.1/*
     127.0.0.1:8080/*
     ```
   - Jika sudah di production, tambahkan juga:
     ```
     yourdomain.com/*
     *.yourdomain.com/*
     ```

### 2. Aktifkan API yang Diperlukan

Pastikan API berikut sudah diaktifkan:
- ✅ **Places API**
- ✅ **Maps JavaScript API**

Cara mengaktifkan:
1. Pergi ke **APIs & Services** > **Library**
2. Cari dan aktifkan kedua API di atas

### 3. Periksa Billing

Google Maps API memerlukan billing yang aktif:
1. Pergi ke **Billing** di Google Cloud Console
2. Pastikan ada billing account yang terhubung dengan project Anda
3. Google memberikan kredit gratis $200 per bulan untuk Maps API

### 4. Periksa API Key di File Konfigurasi

Pastikan API key sudah benar di file `app/config/maps_config.php`:
```php
define('GOOGLE_MAPS_API_KEY', "YOUR_ACTUAL_API_KEY_HERE");
```

### 5. Test API Key

Anda bisa test API key dengan mengakses URL ini di browser:
```
https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places
```

Jika API key valid, akan muncul JavaScript code. Jika tidak valid, akan muncul error message.

## Troubleshooting

### Error: "This API key is not authorized"
- Pastikan Places API dan Maps JavaScript API sudah diaktifkan
- Periksa apakah API key sudah benar

### Error: "RefererNotAllowedMapError"
- Periksa HTTP referrer restrictions
- Pastikan domain Anda sudah ditambahkan ke daftar allowed referrers

### Error: "ApiTargetBlockedMapError"
- Periksa Application restrictions di API key settings
- Pastikan "HTTP referrers" sudah dikonfigurasi dengan benar
- Untuk development, gunakan `localhost/*` atau `*` (tidak disarankan untuk production)

## Catatan Keamanan

⚠️ **JANGAN** commit API key ke repository public!
- Gunakan environment variable untuk production
- Tambahkan `app/config/maps_config.php` ke `.gitignore` jika berisi API key

## Environment Variable (Recommended)

Untuk production, gunakan environment variable:
```bash
GOOGLE_MAPS_API_KEY=your_actual_api_key_here
```

File `maps_config.php` sudah mendukung environment variable secara otomatis.

