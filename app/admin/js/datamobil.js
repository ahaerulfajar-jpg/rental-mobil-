
// === Validasi Form Tambah Mobil ===
document.querySelector('form').addEventListener('submit', function(e) {
  const nama = document.querySelector('[name="nama_mobil"]').value.trim();
  const tipe = document.querySelector('[name="tipe_mobil"]').value.trim();
  const tahun = document.querySelector('[name="tahun"]').value.trim();
  const harga = document.querySelector('[name="harga_sewa_per_hari"]').value.trim();
  const file = document.querySelector('[name="gambar_mobil"]').value.trim();

  if (!nama || !tipe || !tahun || !harga || !file) {
    e.preventDefault();
    alert("⚠️ Semua kolom wajib diisi termasuk gambar mobil!");
  } else if (isNaN(tahun) || tahun < 2000 || tahun > new Date().getFullYear()) {
    e.preventDefault();
    alert("⚠️ Tahun tidak valid!");
  } else if (isNaN(harga) || harga <= 0) {
    e.preventDefault();
    alert("⚠️ Harga sewa harus berupa angka dan lebih dari 0!");
  }
});

// === Preview Gambar Mobil ===
const inputGambar = document.querySelector('[name="gambar_mobil"]');
inputGambar.addEventListener('change', function() {
  const previewContainer = document.createElement('div');
  previewContainer.classList.add('preview-container');

  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      previewContainer.innerHTML = `<img src="${e.target.result}" alt="Preview Gambar" style="max-width:200px; margin-top:10px; border-radius:8px;">`;
      inputGambar.parentNode.appendChild(previewContainer);
    };
    reader.readAsDataURL(file);
  }
});


function confirmDelete() {
  return confirm('Apakah Anda yakin ingin menghapus data mobil ini?');
}

document.addEventListener('DOMContentLoaded', () => {
  const formTambah = document.getElementById('formTambah');
  if (formTambah) {
      formTambah.addEventListener('submit', e => {
          const harga = formTambah.querySelector('input[name="harga"]').value;
          if (harga <= 0) {
              alert('Harga harus lebih besar dari 0');
              e.preventDefault();
          }
      });
  }
  const formEdit = document.getElementById('formEdit');
  if (formEdit) {
      formEdit.addEventListener('submit', e => {
          const harga = formEdit.querySelector('input[name="harga"]').value;
          if (harga <= 0) {
              alert('Harga harus lebih besar dari 0');
              e.preventDefault();
          }
      });
  }
});


