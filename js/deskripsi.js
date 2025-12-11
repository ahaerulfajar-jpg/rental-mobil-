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