<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Merchant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('admin.merchants', ['sort' => 'id', 'direction' => $sortField === 'id' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}"
                                            class="flex items-center cursor-pointer hover:text-gray-700">
                                            ID
                                            @if ($sortField === 'id')
                                                <span class="ml-1">
                                                    @if ($sortDirection === 'asc')
                                                        ↑
                                                    @else
                                                        ↓
                                                    @endif
                                                </span>
                                            @endif
                                        </a>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('admin.merchants', ['sort' => 'nama_usaha', 'direction' => $sortField === 'nama_usaha' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}"
                                            class="flex items-center cursor-pointer hover:text-gray-700">
                                            Nama Usaha
                                            @if ($sortField === 'nama_usaha')
                                                <span class="ml-1">
                                                    @if ($sortDirection === 'asc')
                                                        ↑
                                                    @else
                                                        ↓
                                                    @endif
                                                </span>
                                            @endif
                                        </a>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('admin.merchants', ['sort' => 'pemilik', 'direction' => $sortField === 'pemilik' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}"
                                            class="flex items-center cursor-pointer hover:text-gray-700">
                                            Pemilik
                                            @if ($sortField === 'pemilik')
                                                <span class="ml-1">
                                                    @if ($sortDirection === 'asc')
                                                        ↑
                                                    @else
                                                        ↓
                                                    @endif
                                                </span>
                                            @endif
                                        </a>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Alamat</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Bergabung</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Delete</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($merchants as $merchant)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            #{{ $merchant->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $merchant->nama_usaha }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $merchant->user->nama }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $merchant->user->email }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $merchant->alamat }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $merchant->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-500">
                                            <button class="delete-merchant-btn hover:underline"
                                                data-merchant-id="{{ $merchant->id }}">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <div id="delete-confirm-modal"
        class="fixed inset-0 flex items-center justify-center z-50 hidden bg-black bg-opacity-30">
        <div class="bg-white rounded-lg shadow-lg w-80 relative">
            <button id="close-delete-modal"
                class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Confirm Deletion</h3>
                <p class="mb-6">Are you sure you want to delete this merchant?</p>
                <div class="flex justify-end">
                    <button id="confirm-delete-btn"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Confirm Deletion</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedMerchantId = null;
        const modal = document.getElementById('delete-confirm-modal');
        const closeModalBtn = document.getElementById('close-delete-modal');
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');

        // Open modal on Delete button click
        document.querySelectorAll('.delete-merchant-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                selectedMerchantId = this.getAttribute('data-merchant-id');
                modal.classList.remove('hidden');
            });
        });

        // Close modal
        closeModalBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            selectedMerchantId = null;
        });

        // Confirm deletion
        confirmDeleteBtn.addEventListener('click', function() {
            if (!selectedMerchantId) return;
            fetch(`/admin/merchants/${selectedMerchantId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Remove the merchant row
                        const row = document.querySelector(`button[data-merchant-id='${selectedMerchantId}']`)
                            .closest('tr');
                        if (row) row.remove();
                    } else {
                        alert('Failed to delete merchant.');
                    }
                    modal.classList.add('hidden');
                    selectedMerchantId = null;
                })
                .catch(() => {
                    alert('Error deleting merchant.');
                    modal.classList.add('hidden');
                    selectedMerchantId = null;
                });
        });
    </script>

</x-admin-layout>
