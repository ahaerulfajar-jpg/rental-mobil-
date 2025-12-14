# Docker Setup untuk Rental Mobil

## Persyaratan
- Docker
- Docker Compose

## Cara Menjalankan

1. **Build dan jalankan container:**
   ```bash
   docker-compose up -d --build
   ```

2. **Akses aplikasi:**
   - Web: http://localhost:8080
   - n8n: http://localhost:5678
     - Username: `admin` (default)
     - Password: `admin123` (default)
   - Database: localhost:3306
     - Username: rentuser
     - Password: rentpass
     - Database: rent_car

3. **Melihat logs:**
   ```bash
   docker-compose logs -f
   ```

4. **Stop container:**
   ```bash
   docker-compose down
   ```

5. **Stop dan hapus volume (data database akan terhapus):**
   ```bash
   docker-compose down -v
   ```

## Struktur Container

- **rental-mobil**: Container PHP Apache untuk aplikasi web
- **rental-mobil-db**: Container MySQL untuk database
- **rental-mobil-n8n**: Container n8n untuk workflow automation dan chat bot

## Setup n8n

n8n sudah dikonfigurasi untuk chat bot. Setelah container berjalan:

1. Akses n8n di http://localhost:5678
2. Login dengan kredensial default (ubah di production!)
3. Buat workflow webhook untuk chat bot
4. Lihat panduan lengkap di [N8N_SETUP.md](N8N_SETUP.md)

## Catatan

- Database akan otomatis di-import dari `rent_car.sql` saat pertama kali container dibuat
- File aplikasi di-mount sebagai volume, jadi perubahan file akan langsung terlihat
- Data database disimpan di volume Docker `db_data`
- Data n8n (workflows, credentials) disimpan di volume Docker `n8n_data`
- Workflows n8n juga disimpan di folder `n8n/workflows/` untuk backup

## Environment Variables (Opsional)

Anda bisa membuat file `.env` di root project untuk mengatur konfigurasi:

```env
# n8n Configuration
N8N_USER=admin
N8N_PASSWORD=your_secure_password
N8N_HOST=localhost
N8N_WEBHOOK_URL=http://localhost:5678/webhook/chat

# Google Maps API
GOOGLE_MAPS_API_KEY=your_api_key_here
```

