# Setup n8n untuk Chat Bot - Panduan Lengkap

## Overview
n8n digunakan untuk menangani chat bot di aplikasi rental mobil. n8n sudah dikonfigurasi di Docker Compose.

## Akses n8n

Setelah Docker Compose berjalan, akses n8n di:
- **URL**: http://localhost:5678
- **Username**: `admin` (default)
- **Password**: `admin123` (default)

⚠️ **PENTING**: Ubah password default di production!

## Konfigurasi Environment Variables

Anda bisa mengubah konfigurasi n8n melalui environment variables di `docker-compose.yml` atau file `.env`:

```env
# n8n Configuration
N8N_USER=admin
N8N_PASSWORD=your_secure_password
N8N_HOST=localhost
```

## Membuat Workflow Chat Bot

### Langkah 1: Buat Workflow Baru
1. Login ke n8n di http://localhost:5678
2. Klik **"Add workflow"**
3. Beri nama: "Chat Bot - Rental Mobil"

### Langkah 2: Tambahkan Webhook Node
1. Drag **"Webhook"** node ke canvas
2. Klik node tersebut untuk konfigurasi
3. Pilih:
   - **HTTP Method**: POST
   - **Path**: `/webhook/chat` (atau path yang Anda inginkan)
   - **Response Mode**: "Respond to Webhook"
4. Klik **"Execute Node"** untuk mendapatkan URL webhook
5. Copy URL webhook (contoh: `http://localhost:5678/webhook/chat`)

### Langkah 3: Update Webhook URL di Aplikasi
Update file `app/config/chat_config.php`:
```php
define('N8N_WEBHOOK_URL', 'http://localhost:5678/webhook/chat');
```

Atau gunakan environment variable:
```env
N8N_WEBHOOK_URL=http://localhost:5678/webhook/chat
```

### Langkah 4: Tambahkan Logic Node
Setelah Webhook node, tambahkan node untuk memproses pesan:

**Contoh Workflow Sederhana:**
1. **Webhook** (menerima pesan dari aplikasi)
2. **IF** node (untuk conditional logic)
3. **Set** node (untuk mengatur response)
4. **Respond to Webhook** (mengembalikan response)

### Contoh Workflow Structure:
```
Webhook → IF (check message) → Set (prepare response) → Respond to Webhook
```

### Langkah 5: Konfigurasi Response
Di node terakhir (Respond to Webhook), pastikan response format:
```json
{
  "message": "Response dari bot",
  "success": true
}
```

Aplikasi mengharapkan format JSON dengan field `message`.

## Data yang Dikirim dari Aplikasi

Aplikasi mengirim data berikut ke n8n webhook:
```json
{
  "message": "Pesan dari user",
  "timestamp": "2024-01-01 12:00:00",
  "user": {
    "id": 1,
    "name": "Nama User",
    "email": "user@example.com",
    "is_guest": false
  },
  "session_id": "session_id_here",
  "conversation_history": []
}
```

## Contoh Workflow n8n

### Simple Echo Bot
1. **Webhook** node - menerima POST request
2. **Set** node - extract message:
   ```javascript
   {{ $json.body.message }}
   ```
3. **Set** node - prepare response:
   ```javascript
   {
     "message": "Anda berkata: " + $json.body.message,
     "success": true
   }
   ```
4. **Respond to Webhook** - return response

### Advanced Bot dengan Conditional Logic
1. **Webhook** node
2. **IF** node - check keywords:
   - Condition: `{{ $json.body.message }}` contains "harga"
   - True: Set response tentang harga
   - False: Set default response
3. **Respond to Webhook**

## Testing Webhook

### Test dengan cURL:
```bash
curl -X POST http://localhost:5678/webhook/chat \
  -H "Content-Type: application/json" \
  -d '{
    "message": "Halo",
    "user": {
      "name": "Test User",
      "is_guest": true
    }
  }'
```

### Test dari Aplikasi:
1. Buka halaman dengan chat bot
2. Kirim pesan test
3. Cek response di browser console

## Troubleshooting

### n8n tidak bisa diakses
- Pastikan container n8n berjalan: `docker ps`
- Cek logs: `docker logs rental-mobil-n8n`
- Pastikan port 5678 tidak digunakan aplikasi lain

### Webhook tidak menerima request
- Pastikan workflow sudah diaktifkan (toggle ON di n8n)
- Cek path webhook sudah benar
- Pastikan URL di `chat_config.php` sesuai dengan webhook URL

### Response tidak muncul di aplikasi
- Cek format response JSON sudah benar
- Pastikan field `message` ada di response
- Cek browser console untuk error
- Cek logs n8n: `docker logs rental-mobil-n8n`

### Error: Connection refused
- Pastikan n8n container berjalan
- Cek network Docker: `docker network inspect rental-mobil-rental-network`
- Pastikan aplikasi bisa mengakses `http://localhost:5678`

## Production Setup

Untuk production, pastikan:

1. **Ubah password default**:
   ```env
   N8N_PASSWORD=strong_secure_password_here
   ```

2. **Setup HTTPS** (jika diperlukan):
   - Gunakan reverse proxy (nginx/traefik)
   - Setup SSL certificate

3. **Backup workflows**:
   - Workflows tersimpan di volume `n8n_data`
   - Backup volume: `docker run --rm -v rental-mobil-_n8n_data:/data -v $(pwd):/backup alpine tar czf /backup/n8n_backup.tar.gz /data`

4. **Environment Variables**:
   ```env
   N8N_HOST=your-domain.com
   N8N_PROTOCOL=https
   WEBHOOK_URL=https://your-domain.com/
   ```

## Integrasi dengan AI/LLM

Anda bisa menambahkan node AI ke workflow n8n:
- **OpenAI** node untuk ChatGPT
- **Hugging Face** node untuk model AI lainnya
- **HTTP Request** node untuk API AI custom

Contoh dengan OpenAI:
1. Webhook → Extract message
2. OpenAI node → Generate response
3. Set response format
4. Respond to Webhook

## Resources

- [n8n Documentation](https://docs.n8n.io/)
- [n8n Community](https://community.n8n.io/)
- [n8n Workflow Examples](https://n8n.io/workflows/)

