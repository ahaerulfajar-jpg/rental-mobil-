//header
// Dapatkan elemen
const header = document.getElementById('header');
const topBar = document.getElementById('top-bar');
const body = document.body;
const mainContent = document.querySelector('.main-content'); 

// Event listener untuk scroll
window.addEventListener('scroll', () => {
  const scrollY = window.scrollY;
  if (scrollY > 0) {
      // Sembunyikan top bar
      topBar.classList.add('hidden');
      body.classList.add('with-hidden-topbar');
      
      // Ubah navbar ke putih (scrolled)
      header.classList.add('scrolled');
      
      // Set tinggi header ke navbar saja
      header.style.height = '72px'; // Sesuaikan
  } else {
      // Tampilkan top bar
      topBar.classList.remove('hidden');
      body.classList.remove('with-hidden-topbar');
      
      // Kembali ke transparan
      header.classList.remove('scrolled');
      
      header.style.height = 'auto';
  }
});
// Handle resize (re-apply styles)
window.addEventListener('resize', () => {
  if (topBar.classList.contains('hidden')) {
      mainContent.style.marginTop = '72px';
  } else {
      mainContent.style.marginTop = '100px';
  }
});

// ====== HAMBURGER MENU ======
document.addEventListener('DOMContentLoaded', function() {
  const hamburgerMenu = document.querySelector('.hamburger-menu');
  const mobileMenu = document.querySelector('.mobile-menu');
  const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
  const mobileNavLinks = document.querySelectorAll('.mobile-nav-links a');
  const body = document.body;

  if (hamburgerMenu && mobileMenu && mobileMenuOverlay) {
    // Toggle menu saat hamburger diklik
    hamburgerMenu.addEventListener('click', function() {
      hamburgerMenu.classList.toggle('active');
      mobileMenu.classList.toggle('active');
      mobileMenuOverlay.classList.toggle('active');
      body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
    });

    // Tutup menu saat overlay diklik
    mobileMenuOverlay.addEventListener('click', function() {
      hamburgerMenu.classList.remove('active');
      mobileMenu.classList.remove('active');
      mobileMenuOverlay.classList.remove('active');
      body.style.overflow = '';
    });

    // Tutup menu saat link diklik
    mobileNavLinks.forEach(link => {
      link.addEventListener('click', function() {
        hamburgerMenu.classList.remove('active');
        mobileMenu.classList.remove('active');
        mobileMenuOverlay.classList.remove('active');
        body.style.overflow = '';
      });
    });

    // Tutup menu saat resize window menjadi desktop size
    window.addEventListener('resize', function() {
      if (window.innerWidth > 768) {
        hamburgerMenu.classList.remove('active');
        mobileMenu.classList.remove('active');
        mobileMenuOverlay.classList.remove('active');
        body.style.overflow = '';
      }
    });
  }
});

//deskripsi mobil
const mainImage = document.querySelector('.car-image');
  const thumbnails = document.querySelectorAll('.thumb');

  thumbnails.forEach(thumb => {
    thumb.addEventListener('click', () => {
      // ganti gambar utama
      mainImage.src = thumb.src;

      // ubah highlight
      thumbnails.forEach(t => t.classList.remove('active'));
      thumb.classList.add('active');
    });
  });

  const minus = document.querySelector('.minus');
  const plus = document.querySelector('.plus');
  const input = document.querySelector('.quantity-box input');

  minus.addEventListener('click', () => {
    if (input.value > 1) input.value--;
  });

  plus.addEventListener('click', () => {
    input.value++;
  });

  // tab info
document.addEventListener("DOMContentLoaded", function () {
    const tabButtons = document.querySelectorAll(".tab-btn");
    const tabContents = document.querySelectorAll(".tab-content");

    tabButtons.forEach(button => {
        button.addEventListener("click", function () {

            // Hapus status aktif semua tombol
            tabButtons.forEach(btn => btn.classList.remove("active"));
            // Sembunyikan semua konten
            tabContents.forEach(content => content.classList.remove("active"));

            // Aktifkan tombol yang diklik
            this.classList.add("active");

            // Tampilkan konten sesuai target
            const targetId = this.getAttribute("data-target");
            document.getElementById(targetId).classList.add("active");
        });
    });
});
