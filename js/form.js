// pelanggan pilih sopir
document.getElementById('pakai_sopir').addEventListener('change', function() {
    const pilihanSopir = document.getElementById('pilihan_sopir');
    if (this.value === 'ya') {
        pilihanSopir.style.display = 'block';
    } else {
        pilihanSopir.style.display = 'none';
    }
});

// Google Places Autocomplete untuk Alamat Penjemputan (seperti Gojek/Grab)
let autocomplete;
let placeSearch;

function initAutocomplete() {
    const input = document.getElementById('alamat_jemput');
    
    if (!input) {
        console.error('Input alamat_jemput tidak ditemukan');
        return;
    }

    // Konfigurasi autocomplete dengan batasan ke Indonesia
    const options = {
        componentRestrictions: { country: 'id' }, // Batasi ke Indonesia
        fields: ['formatted_address', 'geometry', 'name', 'address_components', 'place_id'],
        types: ['address', 'establishment'] // Hanya alamat dan tempat
    };

    autocomplete = new google.maps.places.Autocomplete(input, options);

    // Styling untuk dropdown autocomplete
    const autocompleteContainer = document.querySelector('.pac-container');
    if (autocompleteContainer) {
        autocompleteContainer.style.zIndex = '9999';
        autocompleteContainer.style.fontFamily = 'Poppins, sans-serif';
    }

    // Event listener saat user memilih alamat
    autocomplete.addListener('place_changed', function() {
        const place = autocomplete.getPlace();
        
        if (!place.geometry) {
            console.warn('Tidak ada detail untuk alamat yang dipilih');
            return;
        }

        // Format alamat lengkap
        let fullAddress = place.formatted_address || place.name;
        
        // Update input dengan alamat lengkap
        input.value = fullAddress;
        
        // Simpan data alamat ke hidden fields (opsional, untuk keperluan backend)
        if (place.geometry.location) {
            const lat = place.geometry.location.lat();
            const lng = place.geometry.location.lng();
            
            // Bisa ditambahkan hidden input untuk koordinat jika diperlukan
            let latInput = document.getElementById('alamat_lat');
            let lngInput = document.getElementById('alamat_lng');
            
            if (!latInput) {
                latInput = document.createElement('input');
                latInput.type = 'hidden';
                latInput.id = 'alamat_lat';
                latInput.name = 'alamat_lat';
                input.parentElement.appendChild(latInput);
            }
            
            if (!lngInput) {
                lngInput = document.createElement('input');
                lngInput.type = 'hidden';
                lngInput.id = 'alamat_lng';
                lngInput.name = 'alamat_lng';
                input.parentElement.appendChild(lngInput);
            }
            
            latInput.value = lat;
            lngInput.value = lng;
        }

        // Tambahkan visual feedback
        input.style.borderColor = '#4e73df';
        input.style.backgroundColor = '#fff';
    });

    // Event listener untuk saat user mulai mengetik
    input.addEventListener('input', function() {
        if (this.value.length > 0) {
            this.style.borderColor = '#4e73df';
        }
    });

    // Event listener untuk saat input kehilangan fokus
    input.addEventListener('blur', function() {
        // Delay kecil untuk memastikan autocomplete bisa dipilih
        setTimeout(() => {
            if (this.value.length === 0) {
                this.style.borderColor = '#d8dce6';
            }
        }, 200);
    });
}

// Inisialisasi saat halaman dimuat
if (typeof google !== 'undefined' && google.maps && google.maps.places) {
    initAutocomplete();
} else {
    // Tunggu sampai Google Maps API selesai dimuat
    window.addEventListener('load', function() {
        if (typeof google !== 'undefined' && google.maps && google.maps.places) {
            initAutocomplete();
        } else {
            console.error('Google Maps API belum dimuat');
        }
    });
}