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
