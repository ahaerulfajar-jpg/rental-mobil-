document.addEventListener("DOMContentLoaded", function () {
    const tableContainer = document.querySelector(".grid");
    const alertBox = document.createElement("div");
    alertBox.classList.add("alert-box");
    document.body.appendChild(alertBox);
  
    // === Fungsi tampil notifikasi ===
    function showAlert(message, type = "success") {
      alertBox.innerText = message;
      alertBox.className = `alert-box ${type}`;
      alertBox.style.display = "block";
  
      setTimeout(() => {
        alertBox.style.display = "none";
      }, 3000);
    }
  
    // === Tambah Mobil (AJAX) ===
    const formTambah = document.querySelector("#form-tambah-mobil");
    if (formTambah) {
      formTambah.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(formTambah);
  
        fetch("proses_tambah_mobil.php", {
          method: "POST",
          body: formData,
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.status === "success") {
              showAlert("✅ Mobil berhasil ditambahkan!");
              setTimeout(() => {
                window.location.href = "datamobil.php";
              }, 1000);
            } else {
              showAlert("❌ Gagal menambahkan mobil!", "error");
            }
          })
          .catch(() => showAlert("⚠️ Terjadi kesalahan server!", "error"));
      });
    }
  
    // === Hapus Mobil ===
    document.querySelectorAll(".btn-delete").forEach((btn) => {
      btn.addEventListener("click", function (e) {
        e.preventDefault();
        const id = this.dataset.id;
  
        if (confirm("Yakin ingin menghapus mobil ini?")) {
          fetch(`hapus_mobil.php?id=${id}`, {
            method: "GET",
          })
            .then((res) => res.json())
            .then((data) => {
              if (data.status === "success") {
                showAlert("🚗 Mobil berhasil dihapus!");
                setTimeout(() => location.reload(), 1000);
              } else {
                showAlert("❌ Gagal menghapus mobil!", "error");
              }
            })
            .catch(() => showAlert("⚠️ Terjadi kesalahan server!", "error"));
        }
      });
    });
  
    // === Edit Mobil ===
    const formEdit = document.querySelector("#form-edit-mobil");
    if (formEdit) {
      formEdit.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(formEdit);
  
        fetch("proses_edit_mobil.php", {
          method: "POST",
          body: formData,
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.status === "success") {
              showAlert("✏️ Data mobil berhasil diperbarui!");
              setTimeout(() => {
                window.location.href = "datamobil.php";
              }, 1000);
            } else {
              showAlert("❌ Gagal memperbarui mobil!", "error");
            }
          })
          .catch(() => showAlert("⚠️ Terjadi kesalahan server!", "error"));
      });
    }
  });
  