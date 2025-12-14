<?php
session_start();
include "app/config/database.php";
include "app/config/maps_config.php";

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header("Location: login1.php");
    exit;
}

$pelanggan_id = $_SESSION['user_id'];
$mobil_id = $_GET['id'];

// Ambil data mobil
$mobil = $conn->query("SELECT * FROM mobil WHERE id='$mobil_id'")->fetch_assoc();

// Ambil data pelanggan 
$pelanggan = $conn->query("SELECT * FROM pelanggan WHERE id='$pelanggan_id'")->fetch_assoc();

// Ambil daftar sopir
$sopir = $conn->query("SELECT * FROM sopir WHERE status='Tersedia'");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Form Pemesanan</title>
    <link rel="stylesheet" href="css/pesan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        // Error handler untuk Google Maps API (harus didefinisikan sebelum script dimuat)
        window.gm_authFailure = function() {
            console.error('Google Maps API: Authentication failed - ApiTargetBlockedMapError');
            showMapError('API key tidak valid atau diblokir. Periksa konfigurasi API key di Google Cloud Console.');
        };

        // Global error handler
        window.addEventListener('error', function(e) {
            if (e.message && e.message.includes('ApiTargetBlockedMapError')) {
                showMapError('API key diblokir. Periksa HTTP referrer restrictions di Google Cloud Console.');
            }
        });

        // Callback untuk inisialisasi Google Maps
        window.initGoogleMaps = function() {
            if (typeof google !== 'undefined' && google.maps && google.maps.places) {
                console.log('Google Maps API loaded successfully');
                // Autocomplete akan diinisialisasi oleh form.js
            } else {
                console.error('Google Maps API failed to load');
                showMapError('Gagal memuat Google Maps API. Periksa koneksi internet atau konfigurasi API key.');
            }
        };

        // Fungsi untuk menampilkan error
        function showMapError(message) {
            const input = document.getElementById('alamat_jemput');
            if (input) {
                const parent = input.closest('.input-group');
                let errorDiv = parent.querySelector('.map-error-message');
                
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'map-error-message';
                    parent.appendChild(errorDiv);
                }
                
                errorDiv.innerHTML = '<i class="fa-solid fa-exclamation-triangle"></i> ' + message;
                errorDiv.style.cssText = 'color: #d32f2f; font-size: 12px; margin-top: 5px; padding: 8px; background: #ffebee; border-radius: 5px; border-left: 3px solid #d32f2f; display: block;';
            }
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_API_KEY; ?>&libraries=places&language=id&region=ID&callback=initGoogleMaps"></script>
</head>

<body>

    <div class="page-wrapper">
        <div class="card-wrapper">
            <!-- BAGIAN GAMBAR KIRI -->
            <div class="left-box card-box">

                <!-- Gambar Mobil -->
                <img src="img/<?= $mobil['gambar_mobil']; ?>" class="car-image">

                <!-- Nama & Harga Mobil -->
                <div class="car-info">
                    <h2 class="car-name"><?= $mobil['nama_mobil']; ?></h2>
                    <p class="car-price">
                        Rp <?= number_format($mobil['harga_sewa_per_hari'], 0, ',', '.'); ?> / hari
                    </p>
                </div>
                <!-- FORM DATA USER -->
                <div class="user-form">

                    <div class="input-group">
                        <label><i class="fa-solid fa-user"></i> Nama Lengkap</label>
                        <input type="text" value="<?= $pelanggan['nama']; ?>" readonly>
                    </div>

                    <div class="input-group">
                        <label><i class="fa-solid fa-envelope"></i> Email</label>
                        <input type="email" value="<?= $pelanggan['email']; ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="right-box">

                <form action="app/pelanggan/proses_pesan.php" method="POST">

                    <input type="hidden" name="mobil_id" value="<?= $mobil_id; ?>">
                    <input type="hidden" name="pelanggan_id" value="<?= $pelanggan_id; ?>">


                    <div class="input-group">
                        <label><i class="fa-solid fa-phone"></i> No. Telepon</label>
                        <input type="text" name="telepon" placeholder="Masukkan telepon" required>
                    </div>

                    <div class="input-group">
                        <label><i class="fa-solid fa-calendar"></i> Tanggal Mulai Sewa</label>
                        <input type="date" name="tanggal_mulai" required>
                    </div>

                    <div class="input-group">
                        <label><i class="fa-solid fa-calendar-check"></i> Tanggal Selesai Sewa</label>
                        <input type="date" name="tanggal_selesai" required>
                    </div>

                    <div class="input-group">
                        <label><i class="fa-solid fa-clock"></i> Jam Mulai</label>
                        <input type="time" name="jam_mulai" required>
                    </div>

                    <div class="input-group">
                        <label><i class="fa-solid fa-id-card"></i> Pakai Sopir?</label>
                        <select name="pakai_sopir" id="pakai_sopir">
                            <option value="tidak">Tidak</option>
                            <option value="ya">Ya</option>
                        </select>
                    </div>

                    <div class="input-group" id="pilihan_sopir" style="display:none;">
                        <label><i class="fa-solid fa-user-tie"></i> Pilih Sopir</label>
                        <select name="sopir_id">
                            <option value="">-- Pilih Sopir --</option>
                            <?php
                            $sopir = $conn->query("SELECT * FROM sopir WHERE status='tersedia'");
                            while ($s = $sopir->fetch_assoc()):
                            ?>
                                <option value="<?= $s['id']; ?>">
                                    <?= $s['nama']; ?> - Rp <?= number_format($s['harga_per_hari']); ?>/hari
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="input-group">
                        <label><i class="fa-solid fa-location-dot"></i> Alamat Penjemputan</label>
                        <div class="address-input-wrapper">
                            <input type="text" id="alamat_jemput" name="alamat_jemput" class="address-input" required placeholder="Cari alamat penjemputan... (contoh: Jl. Sudirman, Makassar)">
                            <i class="fa-solid fa-map-marker-alt location-icon"></i>
                        </div>
                        <small class="address-hint">
                            <i class="fa-solid fa-info-circle"></i> Ketik alamat untuk melihat saran lokasi
                        </small>
                    </div>


                    <div class="input-group">
                        <label><i class="fa-solid fa-file"></i> Catatan Tambahan</label>
                        <textarea name="catatan"></textarea>
                    </div>

                    <button class="btn-submit" type="submit">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Pesanan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="js/form.js"></script>

</body>

</html>