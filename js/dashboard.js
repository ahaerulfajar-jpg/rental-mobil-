//header
// Dapatkan elemen
const header = document.getElementById('header');
const topBar = document.getElementById('top-bar');
const body = document.body;
const mainContent = document.querySelector('.main-content'); // Ganti jika selector beda

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

// navbar versi2
document.addEventListener('DOMContentLoaded', function() {
  const header = document.getElementById('header2');
  const topBar = document.getElementById('top-bar2');
  const navbar = document.getElementById('navbar2');
  const topBarHeight = topBar.offsetHeight;

  // Tambahkan event listener untuk scroll
  window.addEventListener('scroll', function() {
    if (window.scrollY > topBarHeight) {
      // Tambahkan kelas 'scrolled' ke header untuk memicu animasi
      header.classList.add('scrolled');
    } else {
      // Hapus kelas 'scrolled' untuk mengembalikan ke posisi semula
      header.classList.remove('scrolled');
    }
  });
});

// ====== DROPDOWN USER ======
document.addEventListener('DOMContentLoaded', function() {
  const userBtn = document.querySelector('.user-btn');
  const dropdownMenu = document.querySelector('.dropdown-menu');
  console.log('JS loaded'); 
  if (userBtn && dropdownMenu) {
      console.log('Elements found'); 
      // Toggle dropdown saat klik user-btn
      userBtn.addEventListener('click', function(e) {
          e.preventDefault(); // Mencegah link default
          dropdownMenu.classList.toggle('show'); // Tambah/hapus class 'show'
          console.log('Dropdown toggled'); // Debug: Cek saat klik
      });
      // Tutup dropdown saat klik di luar
      document.addEventListener('click', function(e) {
          if (!userBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
              dropdownMenu.classList.remove('show');
              console.log('Dropdown closed'); 
          }
      });
  } else {
      console.log('Elements not found'); // Debug: Jika muncul, selector salah
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

// Slide Banner
let currentSlideBanner = 0;
const slidesBanner = document.querySelectorAll(".slide");
const totalSlidesBanner = slidesBanner.length;

function showSlideBanner(index) {
  slidesBanner.forEach((slide, i) => {
    slide.classList.remove("active");
    if (i === index) slide.classList.add("active");
  });
}

function nextSlideBanner() {
  currentSlideBanner = (currentSlideBanner + 1) % totalSlidesBanner;
  showSlideBanner(currentSlideBanner);
}

setInterval(nextSlideBanner, 5000);

// Slide Iklan
let currentSlideIklan = 0;
const slidesIklan = document.querySelectorAll(".slide-iklan");
const dotsIklan = document.querySelectorAll(".dot-iklan");
const totalSlidesIklan = slidesIklan.length;

function showSlideIklan(index) {
  slidesIklan.forEach((slide, i) => {
    slide.classList.remove("active");
    dotsIklan[i].classList.remove("active");
    if (i === index) {
      slide.classList.add("active");
      dotsIklan[i].classList.add("active");
    }
  });
}

function nextSlideIklan() {
  currentSlideIklan = (currentSlideIklan + 1) % totalSlidesIklan;
  showSlideIklan(currentSlideIklan);
}

let sliderInterval = null;

function startSliderIklan() {
  if (sliderInterval) return;
  sliderInterval = setInterval(nextSlideIklan, 5000);
}

const observer = new IntersectionObserver(entries => {
  if (entries[0].isIntersecting) {
    startSliderIklan();
    observer.disconnect();
  }
}, { threshold: 0.3 });

observer.observe(document.querySelector(".iklan-besar"));

// klik dots manual
dotsIklan.forEach((dot, index) => {
  dot.addEventListener("click", () => {
    currentSlideIklan = index;
    showSlideIklan(currentSlideIklan);
  });
});

// tampilkan pertama kali
showSlideIklan(currentSlideIklan);

//testimoni
  document.addEventListener("DOMContentLoaded", function () {
  const testimonis = document.querySelectorAll(".isi-testimoni");
  const btnNavs = document.querySelectorAll(".btn-nav");
  const prevBtn = btnNavs[0];
  const nextBtn = btnNavs[1];
  let currentIndex = 0;
  let autoSlide;

  // Hanya jalankan jika elemen testimoni dan tombol navigasi ada
  if (testimonis.length > 0 && prevBtn && nextBtn) {
    function showTestimoni(index) {
      testimonis.forEach((item, i) => {
        item.classList.toggle("active", i === index);
      });
    }

    function nextSlide() {
      currentIndex = (currentIndex + 1) % testimonis.length;
      showTestimoni(currentIndex);
    }

    function prevSlide() {
      currentIndex = (currentIndex - 1 + testimonis.length) % testimonis.length;
      showTestimoni(currentIndex);
    }

    // auto-slide tiap 5 detik
    function startAutoSlide() {
      autoSlide = setInterval(nextSlide, 5000);
    }
    function resetAutoSlide() {
      clearInterval(autoSlide);
      startAutoSlide();
    }

    // tombol manual
    prevBtn.addEventListener("click", () => {
      prevSlide();
      resetAutoSlide();
    });
    nextBtn.addEventListener("click", () => {
      nextSlide();
      resetAutoSlide();
    });

    // tampilkan pertama & mulai auto-slide
    showTestimoni(currentIndex);
    startAutoSlide();
  }
});


//deretan gambar tour
document.addEventListener("DOMContentLoaded", function() {
  const track = document.querySelector(".carousel-track");
  const cards = document.querySelectorAll(".card");
  const dotsContainer = document.querySelector(".carousel-dots");

  // Hanya jalankan jika elemen carousel ada
  if (track && cards.length > 0 && dotsContainer) {
    let index = 0;
    let interval;

    // --- Clone pertama & terakhir untuk efek loop ---
    const firstClone = cards[0].cloneNode(true);
    const lastClone = cards[cards.length - 1].cloneNode(true);

    track.appendChild(firstClone);
    track.insertBefore(lastClone, cards[0]);

    const allCards = document.querySelectorAll(".card");
    let currentIndex = 1; // mulai dari index ke-1 (karena 0 = clone terakhir)

    // Fungsi untuk mendapatkan lebar kartu (responsif)
    function getCardWidth() {
      const cardStyle = window.getComputedStyle(allCards[0]);
      const cardWidth = allCards[0].offsetWidth;
      const gap = parseInt(cardStyle.marginRight || cardStyle.gap || 16);
      return cardWidth + gap;
    }

    // Set posisi awal
    track.style.transform = `translateX(-${currentIndex * getCardWidth()}px)`;

    // --- Buat titik navigasi ---
    for (let i = 0; i < cards.length; i++) {
      const dot = document.createElement("div");
      if (i === 0) dot.classList.add("active");
      dotsContainer.appendChild(dot);
    }
    const dots = document.querySelectorAll(".carousel-dots div");

    // --- Fungsi update titik ---
    function updateDots() {
      dots.forEach((d) => d.classList.remove("active"));
      let dotIndex = currentIndex - 1;
      if (dotIndex >= cards.length) dotIndex = 0;
      if (dotIndex < 0) dotIndex = cards.length - 1;
      dots[dotIndex].classList.add("active");
    }

    // --- Fungsi geser otomatis ---
    function moveToNext() {
      if (currentIndex >= allCards.length - 1) return;
      currentIndex++;
      track.style.transition = "transform 0.5s ease-in-out";
      track.style.transform = `translateX(-${currentIndex * getCardWidth()}px)`;
      updateDots();
    }

    function moveToPrev() {
      if (currentIndex <= 0) return;
      currentIndex--;
      track.style.transition = "transform 0.5s ease-in-out";
      track.style.transform = `translateX(-${currentIndex * getCardWidth()}px)`;
      updateDots();
    }

    // --- Reset posisi saat sampai clone ---
    track.addEventListener("transitionend", () => {
      if (allCards[currentIndex] === firstClone) {
        track.style.transition = "none";
        currentIndex = 1;
        track.style.transform = `translateX(-${currentIndex * getCardWidth()}px)`;
      }
      if (allCards[currentIndex] === lastClone) {
        track.style.transition = "none";
        currentIndex = allCards.length - 2;
        track.style.transform = `translateX(-${currentIndex * getCardWidth()}px)`;
      }
    });

    // --- Auto slide tiap 3 detik ---
    function startAutoSlide() {
      interval = setInterval(moveToNext, 3000);
    }

    function stopAutoSlide() {
      clearInterval(interval);
    }

    startAutoSlide();

    // --- Pause saat hover ---
    track.addEventListener("mouseenter", stopAutoSlide);
    track.addEventListener("mouseleave", startAutoSlide);

    // --- Responsif: sesuaikan posisi saat resize ---
    window.addEventListener("resize", () => {
      track.style.transition = "none";
      track.style.transform = `translateX(-${currentIndex * getCardWidth()}px)`;
    });
  }
});


// form pesan mobil
const modal = document.getElementById("modalPesan");
const closeBtn = document.querySelector(".close");
const mobilGambar = document.getElementById("mobilGambar");
const mobilNama = document.getElementById("mobilNama");
const mobilHarga = document.getElementById("mobilHarga");
const tanggalMulai = document.getElementById("tanggal_mulai");
const tanggalSelesai = document.getElementById("tanggal_selesai");
const durasiInput = document.getElementById("durasi");
const totalBiaya = document.getElementById("totalBiaya");

let hargaSewa = 0;

// Tombol "Pesan Sekarang"
document.querySelectorAll(".btn-order").forEach(btn => {
  btn.addEventListener("click", (e) => {
    e.preventDefault();

    const card = e.target.closest(".card-mobil");
    const nama = card.querySelector(".nama-mobil").innerText;
    const hargaText = card.querySelector(".harga").innerText;
    const gambar = card.querySelector("img").src;

    mobilNama.innerText = nama;
    mobilGambar.src = gambar;
    mobilHarga.innerText = hargaText;
    hargaSewa = parseInt(hargaText.replace(/[^\d]/g, "")) * 1000;

    modal.style.display = "flex";
  });
});

// Tutup Modal
closeBtn.addEventListener("click", () => {
  modal.style.display = "none";
});
window.addEventListener("click", (e) => {
  if (e.target === modal) modal.style.display = "none";
});

// Hitung Durasi dan Total Otomatis
function hitungDurasi() {
  const mulai = new Date(tanggalMulai.value);
  const selesai = new Date(tanggalSelesai.value);
  if (mulai && selesai && selesai > mulai) {
    const diff = (selesai - mulai) / (1000 * 60 * 60 * 24);
    durasiInput.value = diff + " Hari";
    totalBiaya.value = "Rp " + (diff * hargaSewa).toLocaleString();
  } else {
    durasiInput.value = "";
    totalBiaya.value = "";
  }
}
tanggalMulai.addEventListener("change", hitungDurasi);
tanggalSelesai.addEventListener("change", hitungDurasi);

// Submit Form
document.getElementById("formPesan").addEventListener("submit", function(e) {
  e.preventDefault();
  alert("Pesanan berhasil dikirim!");
  modal.style.display = "none";
});
