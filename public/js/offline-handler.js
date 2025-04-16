// Register Service Worker for offline support
if ('serviceWorker' in navigator) {
    // Check connection status immediately on page load
    const checkConnectionAndRedirect = () => {
        if (!navigator.onLine) {
            // If offline and not already on the offline page, redirect to offline page
            if (!window.location.pathname.includes('/offline')) {
                console.log('User is offline, redirecting to offline page');
                window.location.href = '/offline';
            }
        }
    };
    
    // Check connection immediately
    checkConnectionAndRedirect();
    
    window.addEventListener('load', function() {
        // Register service worker
        navigator.serviceWorker.register('/sw.js')
            .then(function(registration) {
                console.log('Service Worker registered with scope:', registration.scope);
                
                // Check for updates to the Service Worker
                registration.addEventListener('updatefound', function() {
                    // A new Service Worker is being installed
                    const newWorker = registration.installing;
                    
                    newWorker.addEventListener('statechange', function() {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            // New content is available, show notification if needed
                            if (confirm('Konten baru tersedia! Muat ulang untuk memperbarui?')) {
                                window.location.reload();
                            }
                        }
                    });
                });
            })
            .catch(function(error) {
                console.log('Service Worker registration failed:', error);
                // Even if service worker registration fails, still check connection
                checkConnectionAndRedirect();
            });
    });
    
    // Listen for online/offline events
    window.addEventListener('online', function() {
        document.body.classList.remove('offline');
        document.body.classList.add('online');
        
        // Show a notification that we're back online
        if (document.getElementById('online-status-notification')) {
            const notification = document.getElementById('online-status-notification');
            notification.textContent = 'Anda kembali online!';
            notification.classList.remove('hidden');
            notification.classList.add('bg-green-100', 'text-green-800', 'border-green-200');
            notification.classList.remove('bg-red-100', 'text-red-800', 'border-red-200');
            
            // Hide notification after 3 seconds
            setTimeout(function() {
                notification.classList.add('hidden');
            }, 3000);
        }
        
        // If we're on the offline page, redirect back to home
        if (window.location.pathname.includes('/offline')) {
            window.location.href = '/';
        }
    });
    
    window.addEventListener('offline', function() {
        document.body.classList.remove('online');
        document.body.classList.add('offline');
        
        // Immediately redirect to offline page if not already there
        if (!window.location.pathname.includes('/offline')) {
            window.location.href = '/offline';
        }
        
        // Show a notification that we're offline
        if (document.getElementById('online-status-notification')) {
            const notification = document.getElementById('online-status-notification');
            notification.textContent = 'Anda sedang offline. Beberapa fitur mungkin tidak tersedia.';
            notification.classList.remove('hidden');
            notification.classList.add('bg-red-100', 'text-red-800', 'border-red-200');
            notification.classList.remove('bg-green-100', 'text-green-800', 'border-green-200');
        }
    });
    
    // Initial check
    if (!navigator.onLine) {
        document.body.classList.add('offline');
    } else {
        document.body.classList.add('online');
    }
}

// Add online status notification element if it doesn't exist
document.addEventListener('DOMContentLoaded', function() {
    if (!document.getElementById('online-status-notification')) {
        const notification = document.createElement('div');
        notification.id = 'online-status-notification';
        notification.className = 'fixed top-4 right-4 z-50 px-4 py-2 rounded-lg border shadow-md hidden transition-all duration-300';
        notification.style.maxWidth = '300px';
        
        if (!navigator.onLine) {
            notification.textContent = 'Anda sedang offline. Beberapa fitur mungkin tidak tersedia.';
            notification.classList.remove('hidden');
            notification.classList.add('bg-red-100', 'text-red-800', 'border-red-200');
        }
        
        document.body.appendChild(notification);
    }
});
