<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addModal = document.getElementById('layananModal');
        const editModal = document.getElementById('editLayananModal');
        const deleteModal = document.getElementById('deleteConfirmModal');
        const addBtn = document.getElementById('addLayananBtn');
        const addFirstBtn = document.getElementById('addFirstLayananBtn');
        const closeAddBtn = document.getElementById('closeModal');
        const closeEditBtn = document.getElementById('closeEditModal');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const editLayananForm = document.getElementById('editLayananForm');

        let currentLayananId = null;

        function openAddModal() {
            addModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAddModal() {
            addModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openEditModal() {
            editModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            editModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openDeleteModal() {
            deleteModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        if (addBtn) addBtn.addEventListener('click', openAddModal);
        if (addFirstBtn) addFirstBtn.addEventListener('click', openAddModal);
        closeAddBtn.addEventListener('click', closeAddModal);
        closeEditBtn.addEventListener('click', closeEditModal);
        cancelEditBtn.addEventListener('click', closeEditModal);
        cancelDeleteBtn.addEventListener('click', closeDeleteModal);

        // Close modals when clicking outside
        addModal.addEventListener('click', function(e) {
            if (e.target === addModal) {
                closeAddModal();
            }
        });

        editModal.addEventListener('click', function(e) {
            if (e.target === editModal) {
                closeEditModal();
            }
        });

        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                closeDeleteModal();
            }
        });

        // Add event listeners for edit buttons
        document.querySelectorAll('.edit-layanan').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const layananId = this.getAttribute('data-id');
                currentLayananId = layananId;
                fetchLayananDetails(layananId);
            });
        });

        // Add event listeners for delete buttons
        document.querySelectorAll('.delete-layanan').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const layananId = this.getAttribute('data-id');
                currentLayananId = layananId;
                openDeleteModal();
            });
        });

        // Fetch layanan details for editing
        function fetchLayananDetails(id) {
            fetch(`/merchant/layanan/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        populateEditForm(data);
                        openEditModal();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data layanan.');
                });
        }

        // Populate edit form with layanan data
        function populateEditForm(data) {
            const layanan = data.layanan;
            const selectedHari = data.selectedHari;

            // Set form action
            editLayananForm.action = `/merchant/layanan/${layanan.id}`;

            // Basic information
            document.getElementById('edit_nama_layanan').value = layanan.nama_layanan;
            document.getElementById('edit_deskripsi_layanan').value = layanan.deskripsi_layanan;
            document.getElementById('edit_id_sub_kategori').value = layanan.id_sub_kategori;
            document.getElementById('edit_pengalaman').value = layanan.pengalaman || 0;

            // Pricing
            if (layanan.tarif_layanan) {
                document.getElementById('edit_harga').value = layanan.tarif_layanan.harga;
                document.getElementById('edit_satuan').value = layanan.tarif_layanan.satuan;
                document.getElementById('edit_durasi').value = layanan.tarif_layanan.durasi;
                document.getElementById('edit_tipe_durasi').value = layanan.tarif_layanan.tipe_durasi;
            }

            // Jam operasional
            if (layanan.jam_operasional) {
                document.getElementById('edit_jam_buka').value = layanan.jam_operasional.jam_buka;
                document.getElementById('edit_jam_tutup').value = layanan.jam_operasional.jam_tutup;

                // Reset all checkboxes first
                document.querySelectorAll('input[name="jam_operasional[hari][]"]').forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Check the appropriate days
                selectedHari.forEach(hariId => {
                    const checkbox = document.querySelector(
                        `input[name="jam_operasional[hari][]"][value="${hariId}"]`);
                    if (checkbox) checkbox.checked = true;
                });
            }

            // Sertifikasi
            if (layanan.sertifikasi && layanan.sertifikasi.length > 0) {
                document.getElementById('edit_nama_sertifikasi').value = layanan.sertifikasi[0]
                    .nama_sertifikasi || '';
                if (layanan.sertifikasi[0].media_url) {
                    document.getElementById('edit_current_sertifikasi').textContent = 'Sertifikasi saat ini: ' +
                        layanan.sertifikasi[0].nama_sertifikasi;
                }
            }

            // Current image
            const currentImageDiv = document.getElementById('edit_current_image');
            currentImageDiv.innerHTML = '';
            if (layanan.aset && layanan.aset.media_url) {
                const img = document.createElement('img');
                img.src = `${layanan.aset.media_url}`;
                img.alt = layanan.nama_layanan;
                img.className = 'h-24 w-24 object-cover rounded-md';
                currentImageDiv.appendChild(img);
            }
        }

        // Handle delete confirmation
        confirmDeleteBtn.addEventListener('click', function() {
            if (currentLayananId) {
                deleteLayanan(currentLayananId);
            }
        });

        // Delete layanan
        function deleteLayanan(id) {
            fetch(`/merchant/layanan/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(async response => {
                let data;
                try {
                    data = await response.json();
                } catch (e) {
                    throw new Error('Server error, response is not JSON');
                }
                if (!response.ok) {
                    // Custom handling for active bookings (HTTP 400)
                    if (response.status === 400 && data.message && data.message.includes('booking aktif')) {
                        throw new Error('Layanan tidak dapat dihapus karena masih memiliki booking aktif.');
                    }
                    throw new Error(data.message || 'Terjadi kesalahan pada server.');
                }
                return data;
            })
            .then(data => {
                closeDeleteModal();
                if (data.success) {
                    alert('Layanan berhasil dihapus');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Gagal menghapus layanan.'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'Terjadi kesalahan saat menghapus layanan.');
                closeDeleteModal();
            });
        }
    });
</script>