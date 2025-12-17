<footer class="bg-[#5BC6BC]  text-white mt-16">
    <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
            <h2 class="text-lg font-semibold mb-4">Tentang Kami</h2>
            <ul class="space-y-2">
                <li><a href="#" class="hover:underline">Home</a></li>
                <li><a href="#" class="hover:underline">Blog</a></li>
                <li><a href="#" class="hover:underline">Kategori</a></li>
            </ul>
        </div>

        <div>
            <h2 class="text-lg font-semibold mb-4">ToskaKirim</h2>
            <p class="text-sm leading-relaxed">
               {{ $toko_alamat }}
            </p>
            <p class="mt-3 text-sm">
                <a href="https://wa.me/{{ $toko_wa }}" class="btn-wa">
                    Hubungi Admin
                </a>
            </p>
        </div>

        <div class="flex justify-center md:justify-end">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.045983747098!2d107.6695838!3d-6.9206801!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68ddc9d79811bf%3A0xc2a1fc8d67dc0fc4!2sToko%20Sembako%20Toska%20Mart%20-%20Toska%20Kirim!5e0!3m2!1sid!2sid!4v1731156000000!5m2!1sid!2sid"
                width="250"
                height="150"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                class="rounded-lg shadow-md">
            </iframe>
        </div>
    </div>

    <div class="border-t border-white/40 my-4"></div>

    <div class="max-w-7xl mx-auto px-6 pb-6 flex flex-col md:flex-row justify-between items-center text-sm">
        <p>© {{ date('Y') }} ToskaKirim. All rights reserved.</p>

        <div class="flex space-x-4 mt-4 md:mt-0">
            <a href="#" class="hover:text-gray-200"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="hover:text-gray-200"><i class="fab fa-instagram"></i></a>
            <a href="#" class="hover:text-gray-200"><i class="fab fa-twitter"></i></a>
        </div>
    </div>
</footer>
