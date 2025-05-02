<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil elemen-elemen yang diperlukan
        const getLocationBtn = document.getElementById('getLocationBtn');
        const locationStatus = document.getElementById('locationStatus');
        const mapContainer = document.getElementById('mapContainer');
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const bookingDateInput = document.getElementById('tanggal_booking');
        const bookingForm = document.getElementById('bookingForm');
        
        // Auto-fill functionality for user information
        const useAccountInfoCheckbox = document.getElementById('use_account_info');
        const contactEmailInput = document.getElementById('contact_email');
        const contactPhoneInput = document.getElementById('contact_phone');
        const userEmailInput = document.getElementById('user_email');
        const userPhoneInput = document.getElementById('user_phone');
        
        if (useAccountInfoCheckbox) {
            useAccountInfoCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    // Format phone number with +62 prefix if it starts with 0
                    let phoneValue = userPhoneInput.value;
                    if (phoneValue.startsWith('0')) {
                        phoneValue = '+62' + phoneValue.substring(1);
                    } else if (!phoneValue.startsWith('+')) {
                        phoneValue = '+' + phoneValue;
                    }
                    
                    contactEmailInput.value = userEmailInput.value;
                    contactPhoneInput.value = phoneValue;
                    
                    // Make inputs read-only when checkbox is checked
                    contactEmailInput.setAttribute('readonly', 'readonly');
                    contactPhoneInput.setAttribute('readonly', 'readonly');
                } else {
                    contactEmailInput.value = '';
                    contactPhoneInput.value = '';
                    
                    // Remove read-only attribute when checkbox is unchecked
                    contactEmailInput.removeAttribute('readonly');
                    contactPhoneInput.removeAttribute('readonly');
                }
            });
        }
        
        // Format address data before form submission
        if (bookingForm) {
            bookingForm.addEventListener('submit', function(e) {
                // Prevent the default form submission
                e.preventDefault();
                
                // Get all address fields
                const firstName = document.getElementById('first_name').value;
                const lastName = document.getElementById('last_name').value;
                const address = document.getElementById('address').value;
                const city = document.getElementById('city').value;
                const province = document.getElementById('province').value;
                const postalCode = document.getElementById('postal_code').value;
                const country = document.getElementById('country').value;
                
                // Format the complete address
                const completeAddress = `${address}, ${city}, ${province} ${postalCode}, ${country}`;
                
                // Validate required fields
                if (!latitudeInput.value || !longitudeInput.value) {
                    // Show custom modal alert instead of browser alert
                    showAlertModal('Silakan dapatkan lokasi Anda terlebih dahulu!');
                    return;
                }
                
                // Submit the form
                this.submit();
            });
        }
        
        // Tambahkan event listener untuk tombol lokasi
        getLocationBtn.addEventListener('click', function() {
            if (navigator.geolocation) {
                locationStatus.textContent = 'Mendapatkan lokasi Anda...';
                
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        
                        // Set nilai input hidden
                        latitudeInput.value = latitude;
                        longitudeInput.value = longitude;
                        
                        // Tampilkan map
                        mapContainer.classList.remove('hidden');
                        
                        // Initialize map
                        initMap(latitude, longitude);
                        
                        locationStatus.textContent = 'Lokasi berhasil didapatkan!';
                        locationStatus.classList.add('text-green-600');
                    },
                    function(error) {
                        let errorMessage = 'Gagal mendapatkan lokasi: ';
                        
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage += 'Izin lokasi ditolak.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage += 'Informasi lokasi tidak tersedia.';
                                break;
                            case error.TIMEOUT:
                                errorMessage += 'Permintaan lokasi habis waktu.';
                                break;
                            case error.UNKNOWN_ERROR:
                                errorMessage += 'Terjadi kesalahan yang tidak diketahui.';
                                break;
                        }
                        
                        locationStatus.textContent = errorMessage;
                        locationStatus.classList.remove('text-gray-500', 'text-green-500');
                        locationStatus.classList.add('text-red-500');
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                locationStatus.textContent = 'Geolocation tidak didukung oleh browser Anda.';
                locationStatus.classList.add('text-red-600');
            }
        });
        
        if (bookingDateInput) {
            bookingDateInput.addEventListener('change', function() {
                updateEstimatedCompletionDate();
            });
            
            // Function to update the estimated completion date
            function updateEstimatedCompletionDate() {
                const bookingDate = new Date(bookingDateInput.value);
                const estimatedCompletionElement = document.querySelector('.bg-blue-50.rounded-lg');
                
                if (bookingDate && estimatedCompletionElement) {
                    const durationType = "{{ $layanan->tipe_durasi }}";
                    const durationValue = {{ $layanan->durasi }};
                    
                    let estimatedCompletionDate = new Date(bookingDate);
                    
                    // Calculate estimated completion date based on duration type
                    if (durationType === "Jam") {
                        estimatedCompletionDate.setHours(estimatedCompletionDate.getHours() + durationValue);
                    } else if (durationType === "Hari") {
                        estimatedCompletionDate.setDate(estimatedCompletionDate.getDate() + durationValue);
                    } else if (durationType === "Pertemuan") {
                        estimatedCompletionDate.setDate(estimatedCompletionDate.getDate() + durationValue);
                    }
                    
                    // Format the date for display
                    const formattedDate = formatDate(estimatedCompletionDate);
                    estimatedCompletionElement.textContent = formattedDate;
                }
            }
            
            // Helper function to format date
            function formatDate(date) {
                const months = [
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                ];
                
                const day = date.getDate();
                const month = months[date.getMonth()];
                const year = date.getFullYear();
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                
                return `${day} ${month} ${year}, ${hours}:${minutes}`;
            }
        }
        
        // Tambahkan script untuk modal konfirmasi
        const homeLink = document.getElementById('homeLink');
        const confirmModal = document.getElementById('confirmModal');
        const cancelBtn = document.getElementById('cancelBtn');

        homeLink.addEventListener('click', function(e) {
            e.preventDefault();
            confirmModal.classList.remove('hidden');
        });

        cancelBtn.addEventListener('click', function() {
            confirmModal.classList.add('hidden');
        });
    });

    // Function to initialize map with Leaflet
    function initMap(latitude, longitude) {
        const mapDiv = document.getElementById('map');

        // Hapus map sebelumnya jika ada
        if (mapDiv._leaflet_id) {
            mapDiv._leaflet_id = null;
        }

        const map = L.map('map').setView([latitude, longitude], 15);

        // Gunakan OpenStreetMap sebagai tile layer (gratis)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Tambahkan marker
        L.marker([latitude, longitude]).addTo(map)
            .bindPopup('Lokasi Anda')
            .openPopup();
    }
    // Function to show custom alert modal
    function showAlertModal(message) {
        // Check if alert modal already exists, if not create it
        let alertModal = document.getElementById('alertModal');
        
        if (!alertModal) {
            // Create modal elements
            alertModal = document.createElement('div');
            alertModal.id = 'alertModal';
            alertModal.className = 'fixed inset-0 z-50 overflow-y-auto modal-backdrop hidden';
            alertModal.setAttribute('aria-labelledby', 'modal-title');
            alertModal.setAttribute('role', 'dialog');
            alertModal.setAttribute('aria-modal', 'true');
            
            alertModal.innerHTML = `
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full modal-content animate-fade-in">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="alert-modal-title">Perhatian</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500" id="alert-modal-message"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" id="alertCloseBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(alertModal);
            
            // Add event listener to close button
            document.getElementById('alertCloseBtn').addEventListener('click', function() {
                alertModal.classList.add('hidden');
            });
        }
        
        // Set the alert message
        document.getElementById('alert-modal-message').textContent = message;
        
        // Show the modal
        alertModal.classList.remove('hidden');
    }
</script>
