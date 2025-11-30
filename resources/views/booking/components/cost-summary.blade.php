<div class="form-card bg-white">
    <div class="form-card-header">
        <h3 class="text-lg font-semibold text-gray-800">Ringkasan Biaya</h3>
    </div>
    <div class="form-card-body">
        <div class="price-row">
            <p class="text-gray-700">Harga Layanan</p>
            <p class="font-semibold" id="original-price">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</p>
        </div>

        <!-- Promo Code Section -->
        <div class="mt-4 mb-4">
            <label class="form-label" for="kode_promo">Kode Promo (Opsional)</label>
            <div class="flex gap-2">
                <input type="text" 
                       name="kode_promo" 
                       id="kode_promo" 
                       class="form-input flex-1" 
                       placeholder="Masukkan kode promo"
                       autocomplete="off">
                <button type="button" 
                        id="apply-promo-btn" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    Terapkan
                </button>
            </div>
            
            <!-- Promo Status Messages -->
            <div id="promo-message" class="mt-2 text-sm hidden"></div>
            
            <!-- Applied Promo Display -->
            <div id="applied-promo" class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg hidden">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-green-800 font-medium" id="promo-name"></p>
                        <p class="text-green-600 text-sm" id="promo-description"></p>
                    </div>
                    <button type="button" 
                            id="remove-promo-btn" 
                            class="text-red-600 hover:text-red-800 font-medium text-sm">
                        Hapus
                    </button>
                </div>
            </div>
        </div>

        <!-- Discount Row (hidden by default) -->
        <div class="price-row hidden" id="discount-row">
            <p class="text-green-600">Diskon</p>
            <p class="font-semibold text-green-600" id="discount-amount">-</p>
        </div>

        <div class="price-row">
            <p class="font-semibold text-gray-800">Total Pembayaran</p>
            <p class="price-total" id="final-price">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

<!-- Hidden inputs for promo data -->
<input type="hidden" name="promo_data" id="promo-data" value="">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const promoInput = document.getElementById('kode_promo');
    const applyBtn = document.getElementById('apply-promo-btn');
    const removeBtn = document.getElementById('remove-promo-btn');
    const promoMessage = document.getElementById('promo-message');
    const appliedPromo = document.getElementById('applied-promo');
    const discountRow = document.getElementById('discount-row');
    const promoData = document.getElementById('promo-data');
    
    const originalPrice = {{ $layanan->harga }};
    let currentPromo = null;
    
    // Enable/disable apply button based on input
    promoInput.addEventListener('input', function() {
        applyBtn.disabled = this.value.trim() === '';
        if (this.value.trim() === '') {
            hideMessage();
        }
    });
    
    // Apply promo code
    applyBtn.addEventListener('click', function() {
        const promoCode = promoInput.value.trim();
        if (!promoCode) return;
        
        applyBtn.disabled = true;
        applyBtn.textContent = 'Memvalidasi...';
        
        fetch('{{ route("booking.validate-promo") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                kode_promo: promoCode,
                layanan_id: {{ $layanan->id }},
                kategori_id: {{ $layanan->id_kategori }},
                amount: originalPrice
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentPromo = data.promo;
                showAppliedPromo(data.promo);
                updatePricing(data.promo);
                hideMessage();
                promoInput.disabled = true;
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            showMessage('Terjadi kesalahan saat memvalidasi kode promo', 'error');
        })
        .finally(() => {
            applyBtn.disabled = false;
            applyBtn.textContent = 'Terapkan';
        });
    });
    
    // Remove promo code
    removeBtn.addEventListener('click', function() {
        fetch('{{ route("booking.remove-promo") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resetPromo();
            }
        })
        .catch(error => {
            console.error('Error removing promo:', error);
        });
    });
    
    // Enter key support
    promoInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !applyBtn.disabled) {
            e.preventDefault();
            applyBtn.click();
        }
    });
    
    function showAppliedPromo(promo) {
        document.getElementById('promo-name').textContent = promo.nama;
        document.getElementById('promo-description').textContent = 
            `Kode: ${promo.kode} - ${promo.tipe_diskon === 'percentage' ? promo.nilai_diskon + '%' : 'Rp ' + formatNumber(promo.nilai_diskon)} diskon`;
        appliedPromo.classList.remove('hidden');
        promoData.value = JSON.stringify(promo);
    }
    
    function updatePricing(promo) {
        const discountAmount = promo.discount_amount;
        const finalAmount = originalPrice - discountAmount;
        
        document.getElementById('discount-amount').textContent = '-Rp ' + formatNumber(discountAmount);
        document.getElementById('final-price').textContent = 'Rp ' + formatNumber(finalAmount);
        discountRow.classList.remove('hidden');
    }
    
    function resetPromo() {
        currentPromo = null;
        promoInput.value = '';
        promoInput.disabled = false;
        appliedPromo.classList.add('hidden');
        discountRow.classList.add('hidden');
        document.getElementById('final-price').textContent = 'Rp ' + formatNumber(originalPrice);
        promoData.value = '';
        applyBtn.disabled = true;
        hideMessage();
    }
    
    function showMessage(message, type) {
        promoMessage.textContent = message;
        promoMessage.className = `mt-2 text-sm ${type === 'error' ? 'text-red-600' : 'text-green-600'}`;
        promoMessage.classList.remove('hidden');
    }
    
    function hideMessage() {
        promoMessage.classList.add('hidden');
    }
    
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }
});
</script>
