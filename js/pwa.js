/**
 * PWA - DISABLED sementara
 * Service worker tidak didaftar, prompt install disembunyikan.
 * Unregister SW yang sudah ada.
 */

// Jangan daftarkan service worker
// if ('serviceWorker' in navigator) { ... register ... }

// Unregister semua service worker yang ada
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.getRegistrations().then((registrations) => {
      registrations.forEach((registration) => {
        registration.unregister().then((success) => {
          if (success) console.log('[PWA] Service Worker unregistered (PWA disabled)');
        }); 
      });
    });
  });
}

// Jangan tampilkan prompt install (PWA disabled)
let deferredPrompt = null;
const installButton = document.getElementById('install-pwa-button');

window.addEventListener('beforeinstallprompt', (e) => {
  e.preventDefault();
  deferredPrompt = null;
  if (installButton) installButton.style.display = 'none';
});

// Install PWA
function installPWA() {
  if (!deferredPrompt) {
    return;
  }

  // Show the install prompt
  deferredPrompt.prompt();
  
  // Wait for the user to respond to the prompt
  deferredPrompt.userChoice.then((choiceResult) => {
    if (choiceResult.outcome === 'accepted') {
      console.log('[PWA] User accepted the install prompt');
    } else {
      console.log('[PWA] User dismissed the install prompt');
    }
    // Clear the deferredPrompt variable
    deferredPrompt = null;
    
    // Hide install button
    if (installButton) {
      installButton.style.display = 'none';
    }
  });
}

// Check if app is already installed
window.addEventListener('appinstalled', () => {
  console.log('[PWA] App installed successfully');
  deferredPrompt = null;
  
  // Hide install button
  if (installButton) {
    installButton.style.display = 'none';
  }
  
  // Show success message
  showNotification('Aplikasi berhasil diinstall!', 'success');
});

// Show update notification
function showUpdateNotification() {
  const notification = document.createElement('div');
  notification.className = 'pwa-update-notification';
  notification.innerHTML = `
    <div class="pwa-update-content">
      <p>Versi baru tersedia!</p>
      <button onclick="window.location.reload()">Update Sekarang</button>
      <button onclick="this.parentElement.parentElement.remove()">Nanti</button>
    </div>
  `;
  document.body.appendChild(notification);
  
  // Auto remove after 10 seconds
  setTimeout(() => {
    if (notification.parentElement) {
      notification.remove();
    }
  }, 10000);
}

// Show notification
function showNotification(message, type = 'info') {
  const notification = document.createElement('div');
  notification.className = `pwa-notification pwa-notification-${type}`;
  notification.textContent = message;
  document.body.appendChild(notification);
  
  // Animate in
  setTimeout(() => {
    notification.classList.add('show');
  }, 100);
  
  // Auto remove after 3 seconds
  setTimeout(() => {
    notification.classList.remove('show');
    setTimeout(() => {
      if (notification.parentElement) {
        notification.remove();
      }
    }, 300);
  }, 3000);
}

// Export install function for global use
window.installPWA = installPWA;

