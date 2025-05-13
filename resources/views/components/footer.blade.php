<!-- Footer -->
<footer class="bg-homize-blue py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Company Info -->
            <div class="text-left">
                <h3 class="text-white font-semibold mb-4">About Homize</h3>
                <p class="text-gray-300 text-sm mb-4">Your trusted platform for home services</p>
                <h3 class="text-white font-semibold mb-2">Our Address</h3>
                <p class="text-gray-300 text-sm leading-relaxed">
                    Jalan Colombo Nomor 1, Karang Malang,<br>
                    Kelurahan Caturtunggal, Catur Tunggal,<br>
                    Depok, Sleman, Daerah Istimewa Yogyakarta, 55281
                </p>
            </div>

            <!-- Contact Info -->
            <div class="text-left">
                <h3 class="text-white font-semibold mb-4">Contact Us</h3>
                <p class="text-gray-300 text-sm mb-2">Email: homizedigitalindonesia@gmail.com</p>
                <p class="text-gray-300 text-sm mb-4">Phone: +6281523740785</p>
                
                <h3 class="text-white font-semibold mb-2 mt-6">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('contact.index') }}" class="text-gray-300 hover:text-white text-sm">Contact Us</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-8 border-t border-homize-gray pt-8">
            <p class="text-gray-300 text-sm">&copy; {{ date('Y') }} Homize. All rights reserved.</p>
        </div>
    </div>
</footer>
