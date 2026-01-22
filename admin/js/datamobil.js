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
