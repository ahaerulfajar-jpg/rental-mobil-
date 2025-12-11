// pelanggan pilih sopir
document.getElementById('pakai_sopir').addEventListener('change', function() {
    const pilihanSopir = document.getElementById('pilihan_sopir');
    if (this.value === 'ya') {
        pilihanSopir.style.display = 'block';
    } else {
        pilihanSopir.style.display = 'none';
    }
  });

// Lokasi Pelanggan
let map;
let marker;
let autocomplete;

function initMap() {
    // Inisialisasi peta dengan lokasi default (pusat Sulawesi)
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: -2.5, lng: 120 }, 
        zoom: 8  
    });

    // Inisialisasi autocomplete
    const input = document.getElementById('alamat_jemput');
    autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    // Event listener untuk saat tempat dipilih
    autocomplete.addListener('place_changed', function() {
        const place = autocomplete.getPlace();
        if (!place.geometry) {
            alert('Alamat tidak ditemukan.');
            return;
        }

        // Pindahkan peta ke lokasi yang dipilih
        map.setCenter(place.geometry.location);
        map.setZoom(17);

        // Tambahkan marker
        if (marker) marker.setMap(null); // Hapus marker lama
        marker = new google.maps.Marker({
            map: map,
            position: place.geometry.location
        });
    });
}

// Panggil initMap saat halaman dimuat
google.maps.event.addDomListener(window, 'load', initMap);