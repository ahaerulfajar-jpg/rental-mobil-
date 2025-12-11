document.addEventListener("DOMContentLoaded", () => {
    const addBtn = document.querySelector(".btn-add");
  
    // Tambah Admin
    addBtn.addEventListener("click", (e) => {
      e.preventDefault();
      Swal.fire({
        title: "Tambah Admin",
        html: `
          <input id="username" class="swal2-input" placeholder="Username">
          <input id="email" class="swal2-input" placeholder="Email">
          <input id="password" type="password" class="swal2-input" placeholder="Password">
        `,
        confirmButtonText: "Simpan",
        showCancelButton: true,
        preConfirm: () => {
          const username = document.getElementById("username").value;
          const email = document.getElementById("email").value;
          const password = document.getElementById("password").value;
          if (!username || !email || !password) {
            Swal.showValidationMessage("Harap isi semua kolom");
          }
          return { username, email, password };
        },
      }).then((result) => {
        if (result.isConfirmed) {
          const data = result.value;
          fetch("tambah_admin.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `username=${data.username}&email=${data.email}&password=${data.password}`,
          })
            .then((res) => res.text())
            .then(() => {
              Swal.fire("Berhasil!", "Admin berhasil ditambahkan", "success").then(() =>
                location.reload()
              );
            });
        }
      });
    });
  
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
  