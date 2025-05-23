<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Pemesanan - Homize</title>
    <link rel="icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('homizeiconblue.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>


    <style>
        :root {
            --homize-blue: #1E88E5;
            --homize-blue-dark: #1565C0;
            --homize-blue-light: #64B5F6;
            --homize-blue-second: #0D47A1;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F5F7FA;
        }

        .btn-primary {
            background-color: var(--homize-blue);
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--homize-blue-dark);
            transform: translateY(-2px);
        }

        .form-card {
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .form-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .form-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            background-color: rgba(30, 136, 229, 0.03);
        }

        .form-card-body {
            padding: 24px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 14px;
        }

        .form-input:focus {
            border-color: var(--homize-blue);
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.15);
            outline: none;
        }

        .form-input:disabled,
        .form-input[readonly] {
            background-color: #F8FAFC;
            cursor: not-allowed;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
            color: #4B5563;
        }

        .booking-progress {
            display: flex;
            justify-content: space-between;
            margin-bottom: 32px;
            position: relative;
        }

        .booking-progress::before {
            content: '';
            position: absolute;
            top: 16px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #E2E8F0;
            z-index: 0;
        }

        .booking-step {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 33.333%;
        }

        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: white;
            border: 2px solid #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            color: #64748B;
            transition: all 0.3s ease;
        }

        .step-label {
            font-size: 13px;
            font-weight: 500;
            color: #64748B;
            text-align: center;
        }

        .booking-step.active .step-number {
            background-color: var(--homize-blue);
            border-color: var(--homize-blue);
            color: white;
        }

        .booking-step.active .step-label {
            color: var(--homize-blue);
            font-weight: 600;
        }

        .booking-step.completed .step-number {
            background-color: #10B981;
            border-color: #10B981;
            color: white;
        }

        .location-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px 16px;
            background-color: #F3F4F6;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-weight: 500;
            color: #4B5563;
            transition: all 0.2s ease;
        }

        .location-btn:hover {
            background-color: #E5E7EB;
        }

        .location-btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.15);
        }

        .service-image {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            object-fit: cover;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
        }

        .price-row:not(:last-child) {
            border-bottom: 1px solid #F3F4F6;
        }

        .price-total {
            font-weight: 700;
            color: var(--homize-blue);
            font-size: 18px;
        }

        .navbar {
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        #map {
            border-radius: 8px;
            border: 1px solid #E2E8F0;
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" id="homeLink" class="flex items-center">
                    <img src="{{ asset('images/homizelogoblue.png') }}" alt="Homize Logo" class="h-8">
                </a>
                <h1 class="text-lg font-semibold text-gray-800 hidden md:block">Form Pemesanan Layanan</h1>
                <div class="w-8 md:w-24"></div>
            </div>
        </div>
    </nav>

    <div id="confirmModal" class="fixed inset-0 z-50 hidden overflow-y-auto modal-backdrop"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full modal-content animate-fade-in">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Batalkan Pemesanan?
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Apakah Anda yakin ingin kembali ke halaman utama? Data pemesanan Anda akan hilang.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <a href="{{ route('home') }}"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Ya, Batalkan
                    </a>
                    <button type="button" id="cancelBtn"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Lanjutkan Pemesanan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="py-8 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="md:hidden mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Form Pemesanan</h1>
            </div>

            <div class="booking-progress">
                <div class="booking-step completed">
                    <div class="step-number">1</div>
                    <div class="step-label">Pilih Layanan</div>
                </div>
                <div class="booking-step active">
                    <div class="step-number">2</div>
                    <div class="step-label">Isi Detail</div>
                </div>
                <div class="booking-step">
                    <div class="step-number">3</div>
                    <div class="step-label">Pembayaran</div>
                </div>
            </div>

            <form action="{{ route('booking.store') }}" method="POST" id="bookingForm" x-data="{ loading: false }" x-on:submit="loading = true">
                @csrf
                <input type="hidden" name="id_layanan" value="{{ $layanan->id }}">
                <input type="hidden" name="id_merchant" value="{{ $layanan->id_merchant }}">
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Kolom Kiri: Informasi Layanan -->
                    <div class="lg:col-span-5">
                        @include('booking.components.service-info')

                        @include('booking.components.booking-schedule')

                        @include('booking.components.cost-summary')
                    </div>

                    <!-- Kolom Kanan: Informasi Pemesan -->
                    <div class="lg:col-span-7">
                        @include('booking.components.user-info')

                        @include('booking.components.address-info')

                        @include('booking.components.additional-notes')

                        <button type="submit"
                            class="w-full btn-primary py-4 px-6 rounded-xl font-medium text-lg flex items-center justify-center gap-2 shadow-lg disabled:opacity-60 disabled:cursor-not-allowed"
                            x-bind:disabled="loading">
                            <template x-if="!loading">
                                <span class="flex items-center gap-2">
                                    Lanjutkan ke Pembayaran
                                </span>
                            </template>
                            <template x-if="loading">
                                <span class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                    </svg>
                                    Loading...
                                </span>
                            </template>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('booking.components.booking-scripts')
</body>

</html>
