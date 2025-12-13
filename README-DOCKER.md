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

## Catatan

- Database akan otomatis di-import dari `rent_car.sql` saat pertama kali container dibuat
- File aplikasi di-mount sebagai volume, jadi perubahan file akan langsung terlihat
- Data database disimpan di volume Docker `db_data`

