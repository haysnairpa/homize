<div class="form-card mb-6 bg-white">
    <div class="form-card-header">
        <h3 class="text-lg font-semibold text-gray-800">Informasi Pemesan</h3>
    </div>
    <div class="form-card-body">
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label for="contact_email" class="form-label">Email</label>
                <input type="email" id="contact_email" name="contact_email" 
                    class="form-input" placeholder="Masukkan alamat email Anda">
            </div>

            <div>
                <label for="contact_phone" class="form-label">Nomor Telepon</label>
                <input type="text" id="contact_phone" name="contact_phone" 
                    class="form-input" placeholder="Masukkan nomor telepon Anda">
            </div>
            
            <div class="mt-2">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="use_account_info" class="form-checkbox h-5 w-5 text-blue-600">
                    <span class="ml-2 text-sm text-gray-700">Gunakan informasi akun saya</span>
                </label>
                <input type="hidden" id="user_email" value="{{ $user->email }}">
                <input type="hidden" id="user_phone" value="{{ $user->phone }}">
            </div>
        </div>
    </div>
</div>
