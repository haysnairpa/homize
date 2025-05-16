<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar User') }}
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
                                        <a href="{{ route('admin.users', ['sort' => 'id', 'direction' => $sortField === 'id' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}"
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
                                        <a href="{{ route('admin.users', ['sort' => 'nama', 'direction' => $sortField === 'nama' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}"
                                            class="flex items-center cursor-pointer hover:text-gray-700">
                                            Nama
                                            @if ($sortField === 'nama')
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
                                        Tanggal Registrasi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Delete</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            #{{ $user->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $user->nama }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-500">
                                            <button class="delete-user-btn hover:underline" data-user-id="{{ $user->id }}">
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
<div id="delete-user-confirm-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-black bg-opacity-30">
    <div class="bg-white rounded-lg shadow-lg w-80 relative">
        <button id="close-delete-user-modal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Confirm Deletion</h3>
            <p class="mb-6">Are you sure you want to delete this user?</p>
            <div class="flex justify-end">
                <button id="confirm-delete-user-btn" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Confirm Deletion</button>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedUserId = null;
    const userModal = document.getElementById('delete-user-confirm-modal');
    const closeUserModalBtn = document.getElementById('close-delete-user-modal');
    const confirmDeleteUserBtn = document.getElementById('confirm-delete-user-btn');

    // Open modal on Delete button click
    document.querySelectorAll('.delete-user-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            selectedUserId = this.getAttribute('data-user-id');
            userModal.classList.remove('hidden');
        });
    });

    // Close modal
    closeUserModalBtn.addEventListener('click', function () {
        userModal.classList.add('hidden');
        selectedUserId = null;
    });

    // Confirm deletion
    confirmDeleteUserBtn.addEventListener('click', function () {
        if (!selectedUserId) return;
        fetch(`/admin/users/${selectedUserId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Remove the user row
                const row = document.querySelector(`button[data-user-id='${selectedUserId}']`).closest('tr');
                if (row) row.remove();
            } else {
                alert('Failed to delete user.');
            }
            userModal.classList.add('hidden');
            selectedUserId = null;
        })
        .catch(() => {
            alert('Error deleting user.');
            userModal.classList.add('hidden');
            selectedUserId = null;
        });
    });
</script>

</x-admin-layout>
