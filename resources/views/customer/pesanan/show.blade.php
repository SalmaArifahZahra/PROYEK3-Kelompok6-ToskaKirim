@if($pesanan->status_pesanan == 'menunggu_pembayaran')
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6 text-center">
        <p class="text-orange-600 font-medium mb-1">Selesaikan Pembayaran Dalam:</p>
        
        <div id="countdown-timer" 
             class="text-2xl font-bold text-orange-700 tracking-wider"
             data-deadline="{{ $deadlineTimestamp }}">
             Loading...
        </div>
        
        <p class="text-xs text-gray-500 mt-2">
            Batas Akhir: {{ \Carbon\Carbon::parse($deadline)->format('d M Y, H:i') }} WIB
        </p>
    </div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timerEl = document.getElementById('countdown-timer');
        
        // Cek jika elemen ada (hanya muncul saat status menunggu_pembayaran)
        if (timerEl) {
            const deadlineTime = parseInt(timerEl.getAttribute('data-deadline'));
            const updateTimer = setInterval(function() {
                const now = new Date().getTime();
                
                const distance = deadlineTime - now;

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                const formattedTime = 
                    (hours < 10 ? "0" + hours : hours) + " : " + 
                    (minutes < 10 ? "0" + minutes : minutes) + " : " + 
                    (seconds < 10 ? "0" + seconds : seconds);

                timerEl.innerHTML = formattedTime;

                if (distance < 0) {
                    clearInterval(updateTimer);
                    timerEl.innerHTML = "WAKTU HABIS";
                    timerEl.classList.add('text-red-600');
                    
                    setTimeout(() => {
                        location.reload(); 
                    }, 2000);
                }
            }, 1000); // Update setiap 1000ms (1 detik)
        }
    });
</script>
@endpush