document.addEventListener("DOMContentLoaded", () => {
    const addBtn = document.querySelector(".btn-add");

  
    // Hapus Admin
    document.querySelectorAll(".btn-delete").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        const url = e.target.getAttribute("href");
  
        Swal.fire({
          title: "Hapus Admin?",
          text: "Data tidak bisa dikembalikan!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonText: "Batal",
          confirmButtonText: "Ya, hapus",
        }).then((result) => {
          if (result.isConfirmed) {
            fetch(url)
              .then((res) => res.text())
              .then(() => {
                Swal.fire("Terhapus!", "Admin telah dihapus", "success").then(() =>
                  location.reload()
                );
              });
          }
        });
      });
    });
  });
  
// tambah & edit sopir
function openTambahSopir() {
    document.getElementById('formTitle').innerText = 'Tambah Sopir';

    document.getElementById('btnSubmit').innerText = 'Simpan';
    document.getElementById('btnSubmit').name = 'simpan_sopir';

    document.getElementById('sopirId').value = '';
    document.getElementById('formSopir').reset();
    document.getElementById('fotoPreview').src = '';

    document.getElementById('overlaySopir').style.display = 'flex';
}

function openEditSopir(id, nama, telepon, email, alamat, status, harga, tanggal, foto) {
    document.getElementById('formTitle').innerText = 'Edit Sopir';

    document.getElementById('btnSubmit').innerText = 'Update';
    document.getElementById('btnSubmit').name = 'update_sopir';

    document.getElementById('sopirId').value = id;
    document.getElementById('nama').value = nama;
    document.getElementById('telepon').value = telepon;
    document.getElementById('email').value = email;
    document.getElementById('alamat').value = alamat;
    document.getElementById('status').value = status;
    document.getElementById('harga').value = harga;
    document.getElementById('tanggal').value = tanggal;

    document.getElementById('fotoPreview').src =
        foto ? '../img/' + foto : '';

    document.getElementById('overlaySopir').style.display = 'flex';
}

function closeSopirForm() {
    document.getElementById('overlaySopir').style.display = 'none';
}

//detail sopir
  function showDetail(el) {
    document.getElementById("overlayDetail").style.display = "flex";

    document.getElementById("detailNama").innerText = el.dataset.nama;
    document.getElementById("detailTelepon").innerText = el.dataset.telepon;
    document.getElementById("detailHarga").innerText = "Rp " + el.dataset.harga;
    document.getElementById("detailEmail").innerText = el.dataset.email;
    document.getElementById("detailAlamat").innerText = el.dataset.alamat;
    document.getElementById("detailTanggal").innerText = el.dataset.tanggal;
    document.getElementById("detailFoto").src = el.dataset.foto;

    let status = el.dataset.status;
    let badge = document.getElementById("detailStatus");

    badge.innerText = status;

    if (status === "tersedia") {
        badge.className = "badge bg-green";
    } else if (status === "dipesan") {
        badge.className = "badge bg-orange";
    } else {
        badge.className = "badge bg-red";
    }
}

function closeDetail() {
    document.getElementById("overlayDetail").style.display = "none";
}

//profil admin
function openOverlay() {
    document.getElementById('overlayAccount').style.display = 'flex';
}

function closeOverlay() {
    document.getElementById('overlayAccount').style.display = 'none';
}

//tambah admin 
function openAddAdmin() {
  document.getElementById('addAdminOverlay').style.display = 'flex';
}

function closeAddAdmin() {
  document.getElementById('addAdminOverlay').style.display = 'none';
}

//view admin
function openAktivitasAdmin(adminId) {
  document.getElementById('overlayAktivitas').classList.remove('hidden');

  fetch('../../app/admin/view_admin.php?id=' + adminId)
    .then(res => res.text())
    .then(data => {
      document.getElementById('aktivitasContent').innerHTML = data;
    });
}

function closeAktivitas() {
  document.getElementById('overlayAktivitas').classList.add('hidden');
}