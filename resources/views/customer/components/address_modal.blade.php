<div id="addressModal" class="hidden fixed inset-0 z-[9999]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="fixed inset-0 bg-gray-900/75 transition-opacity backdrop-blur-sm"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-200">
                
                <div class="bg-white px-4 py-4 sm:px-6 border-b border-gray-100 flex justify-between items-center sticky top-0 z-20">
                    <div>
                        <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">Pilih Alamat Pengiriman</h3>
                    </div>
                    <button type="button" id="closeAddressModal" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-4 py-5 sm:p-6 max-h-[70vh] overflow-y-auto bg-gray-50/50">
                    
                    <div id="addressList" class="space-y-3"></div>

                    <div id="addressFormContainer" class="hidden mt-6 bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <h4 id="formTitle" class="text-md font-bold text-teal-700">Tambah Alamat Baru</h4>
                            <button type="button" id="cancelAddressForm" class="text-xs text-gray-500 hover:text-red-500 underline">Batal</button>
                        </div>
                        
                        <form id="addressForm" class="space-y-4">
                            @csrf
                            <input type="hidden" id="addressId" name="id_alamat">

                            <input type="hidden" name="kota_kabupaten" id="kota_kabupaten_hidden">
                            <input type="hidden" name="kecamatan" id="kecamatan_hidden">
                            <input type="hidden" name="kelurahan" id="kelurahan_hidden">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Label Alamat <span class="text-red-500">*</span></label>
                                    <input type="text" id="label_alamat" name="label_alamat" required placeholder="Rumah, Kantor" class="w-full text-sm border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Penerima <span class="text-red-500">*</span></label>
                                    <input type="text" id="nama_penerima" name="nama_penerima" required class="w-full text-sm border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">No. Telepon <span class="text-red-500">*</span></label>
                                <input type="tel" id="telepon_penerima" name="telepon_penerima" required class="w-full text-sm border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                                <select id="provinsi" class="w-full text-sm border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500" required>
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kota/Kabupaten <span class="text-red-500">*</span></label>
                                    <select id="kota_kabupaten_select" required class="w-full text-sm border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500">
                                        <option value="">Pilih Kota</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                                    <select id="kecamatan_select" required class="w-full text-sm border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500">
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Kelurahan <span class="text-red-500">*</span></label>
                                <select id="kelurahan_select" required class="w-full text-sm border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500">
                                    <option value="">Pilih Kelurahan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Jalan & Patokan <span class="text-red-500">*</span></label>
                                <textarea id="jalan_patokan" name="jalan_patokan" rows="2" required class="w-full text-sm border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500"></textarea>
                            </div>

                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[10px] font-semibold text-gray-500">No. Rumah <span class="text-red-500">*</span></label>
                                    <input type="text" id="no_rumah" name="no_rumah" required class="w-full text-sm border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-semibold text-gray-500">RT <span class="text-red-500">*</span></label>
                                    <input type="text" id="rt" name="rt" required class="w-full text-sm border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-semibold text-gray-500">RW <span class="text-red-500">*</span></label>
                                    <input type="text" id="rw" name="rw" required class="w-full text-sm border-gray-300 rounded-md focus:border-teal-500 focus:ring-teal-500">
                                </div>
                            </div>

                            <button type="submit" class="w-full justify-center rounded-md bg-teal-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500">
                                Simpan Alamat
                            </button>
                        </form>
                    </div>

                    <div id="addAddressButtonContainer" class="mt-6 pt-4 border-t border-gray-200">
                        <button type="button" id="btnAddAddress" class="flex w-full items-center justify-center gap-2 rounded-md border-2 border-dashed border-gray-300 bg-white px-3 py-3 text-sm font-semibold text-gray-600 hover:border-teal-500 hover:text-teal-600 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah Alamat Baru
                        </button>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100">
                    <button type="button" id="submitModal" class="inline-flex w-full justify-center rounded-md bg-teal-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-500 sm:ml-3 sm:w-auto">
                        Pilih Alamat
                    </button>
                    <button type="button" id="cancelModal" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>