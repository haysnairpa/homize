// Chat functionality for Homize
import './bootstrap';

// This will be automatically included in the main app.js file
document.addEventListener('alpine:init', () => {
    // Initialize Pusher channels for chat
    if (window.Echo) {
        console.log('Echo initialized for chat');
        
        // Register Pusher debug logs if in development
        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
            Pusher.logToConsole = true;
        }
    }

    // Format file size
    window.formatFileSize = function(bytes) {
        if (bytes >= 1073741824) {
            return (bytes / 1073741824).toFixed(2) + ' GB';
        } else if (bytes >= 1048576) {
            return (bytes / 1048576).toFixed(2) + ' MB';
        } else if (bytes >= 1024) {
            return (bytes / 1024).toFixed(2) + ' KB';
        } else {
            return bytes + ' bytes';
        }
    };
});
