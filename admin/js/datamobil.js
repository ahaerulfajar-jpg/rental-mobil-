function hapusMobil(id) {
  if (!confirm("Yakin ingin menghapus mobil ini?")) return;

  fetch("../../app/admin/hapus_mobil.php?id=" + id)
      .then(response => response.json())
      .then(data => {
          if (data.status === "success") {
              alert("Mobil berhasil dihapus!");
              location.reload();
          } else {
              alert("Gagal menghapus mobil!");
          }
      });
}

function openMaintenanceForm() {
  document.getElementById('overlayMaintenance').style.display = 'block';
  document.getElementById('maintenancePopup').style.display = 'block';
}

function closeMaintenanceForm() {
  document.getElementById('overlayMaintenance').style.display = 'none';
  document.getElementById('maintenancePopup').style.display = 'none';
}


function openTambahMobil() {
  document.getElementById('modalTambahMobil').style.display = 'flex';
}
function closeTambahMobil() {
  document.getElementById('modalTambahMobil').style.display = 'none';
}

//edit mobil
function openEditMobil(id) {
  fetch('../../app/admin/get_mobil.php?id=' + id)
    .then(res => res.json())
    .then(data => {
      document.getElementById('edit_id').value = data.id;
      document.getElementById('edit_nama').value = data.nama_mobil;
      document.getElementById('edit_tipe').value = data.tipe_mobil;
      document.getElementById('edit_tahun').value = data.tahun;
      document.getElementById('edit_kapasitas').value = data.kapasitas;
      document.getElementById('edit_transmisi').value = data.transmisi;
      document.getElementById('edit_bahan').value = data.bahan_bakar;
      document.getElementById('edit_harga').value = data.harga_sewa_per_hari;
      document.getElementById('edit_status').value = data.status;
      document.getElementById('preview_gambar').src = '../img/' + data.gambar_mobil;

      document.getElementById('modalEditMobil').style.display = 'flex';
    });
}

function closeEditMobil() {
  document.getElementById('modalEditMobil').style.display = 'none';
}

//DETAIL MOBIL
function openDetailMaintenance(id) {
  fetch('../../app/admin/get_maintanance.php?id=' + id)
    .then(res => res.json())
    .then(data => {

      document.getElementById('d_mobil').innerText = data.nama_mobil;
      document.getElementById('d_jenis').innerText = data.jenis_perawatan;
      document.getElementById('d_km').innerText = data.kilometer + ' KM';
      document.getElementById('d_bengkel').innerText = data.bengkel;
      document.getElementById('d_biaya').innerText = 
        'Rp ' + Number(data.biaya).toLocaleString('id-ID');

      document.getElementById('d_tanggal').innerText =
        data.tanggal_mulai + ' s/d ' + (data.tanggal_selesai ?? '-');

      document.getElementById('d_deskripsi').innerText = data.deskripsi;

      const status = document.getElementById('d_status');
      status.innerText = data.status_perawatan.replace('_',' ');
      status.className = 'badge status-' + data.status_perawatan;

      document.getElementById('overlayDetail').style.display = 'block';
      document.getElementById('detailPopup').style.display = 'block';
    });
}

function closeDetailMaintenance() {
  document.getElementById('overlayDetail').style.display = 'none';
  document.getElementById('detailPopup').style.display = 'none';
}

// view mobil
function openDetailMobil(
    nama,
    tipe,
    tahun,
    kapasitas,
    transmisi,
    bahan_bakar,
    harga,
    status,
    gambar,
    created_at
) {
    // isi text
    document.getElementById('detailNama').innerText = nama;
    document.getElementById('detailTipe').innerText = tipe;
    document.getElementById('detailTahun').innerText = tahun;
    document.getElementById('detailKapasitas').innerText = kapasitas + ' Orang';
    document.getElementById('detailTransmisi').innerText = transmisi;
    document.getElementById('detailBahanBakar').innerText = bahan_bakar;
    document.getElementById('detailHarga').innerText =
        'Rp ' + Number(harga).toLocaleString('id-ID');
    document.getElementById('detailTanggal').innerText = created_at;

    // gambar
    document.getElementById('detailGambar').src =
        gambar ? '../../img/' + gambar : '../../img/';

    // status badge
    const statusEl = document.getElementById('detailStatus');
    statusEl.innerText = status;

    statusEl.className = 'badge'; // reset class
    if (status.toLowerCase() === 'tersedia') {
        statusEl.classList.add('tersedia');
    } else {
        statusEl.classList.add('tidak');
    }

    // tampilkan overlay
    document.getElementById('overlayDetailMobil').style.display = 'flex';
}

function closeDetailMobil() {
    document.getElementById('overlayDetailMobil').style.display = 'none';
}
