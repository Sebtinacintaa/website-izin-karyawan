@extends('layouts.app')

@section('content')
<main class="flex-1 ml-[250px] m-5 transition-all duration-300 ease-in-out">
    <div class="container mx-auto px-2">
        <!-- Alert Sukses -->
        @if(session('success'))
            <div id="alert-success" class="mb-4 bg-blue-50 border border-blue-300 text-blue-700 px-4 py-3 rounded relative shadow-md" role="alert">
                <span class="block sm:inline">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                </span>
                <button type="button" onclick="closeAlert('alert-success')" class="absolute top-1 right-2 text-gray-400 hover:text-gray-600 focus:outline-none" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Alert Error Validasi -->
        @if ($errors->any())
            <div id="alert-error" class="mb-4 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded relative shadow-md animate-fade-in" role="alert">
                <ul class="list-disc pl-5 mt-1 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-triangle mr-1"></i>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" onclick="closeAlert('alert-error')" class="absolute top-1 right-2 text-gray-400 hover:text-gray-600 focus:outline-none" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <h1 class="text-xl font-bold mb-3">
            Silakan lengkapi formulir di bawah ini untuk melanjutkan permohonan cuti Anda.
        </h1>
        <p class="text-sm text-gray-600 mb-6">
            Isi formulir ini dan unggah dokumen yang dibutuhkan
        </p>

        <form action="{{ route('leave.request.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
            <div class="bg-white rounded-lg shadow-sm p-6 mb-5">
                <!-- Upload Section -->
                <div class="mb-2 px-4">
                    <h2 class="text-lg font-semibold mb-4">Upload Your Document</h2>
                    <label for="fileInput"
                        ondragover="handleDragOver(event)"
                        ondrop="handleDrop(event)"
                        class="relative flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-10 text-center cursor-pointer hover:border-[#9EC6F3] transition bg-gray-50 min-h-[200px]">
                        <div id="uploadIcon" class="flex flex-col items-center space-y-2">
                            <i class="fas fa-cloud-upload-alt text-4xl text-[#9FB3DF]"></i>
                            <p id="dragText" class="text-gray-500 hover:text-[#60B5FF]">
                                Drag your file to upload
                            </p>
                        </div>
                        <div id="filePreview" class="relative mt-4 w-full max-h-[300px] flex items-center justify-center overflow-y-auto opacity-0 pointer-events-none transition-opacity duration-300">
                            <img id="previewImage" src="" alt="Preview" class="max-w-full max-h-[200px] hidden">
                            <iframe id="previewPdf" src="" frameborder="0" class="w-full h-[200px] hidden"></iframe>
                            <p id="previewText" class="text-center text-gray-500 hidden">Dokumen tidak didukung.</p>
                        </div>
                    </label>
                    <input type="file" name="document" id="fileInput" class="hidden" required accept="application/pdf,image/*">
                    <p id="fileNamePreview" class="mt-3 text-sm text-gray-600 text-center"></p>
                </div>

                <!-- Notifikasi Modal -->
                <div id="uploadSuccess" class="hidden fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg flex items-center space-x-2 z-50">
                    <i class="fas fa-check-circle"></i>
                    <span>File berhasil diunggah!</span>
                    <button type="button" onclick="closeSuccessMessage()" class="ml-2 font-bold">×</button>
                </div>
                <div id="uploadError" class="hidden fixed top-5 right-5 bg-red-500 text-white px-4 py-2 rounded shadow-lg flex items-center space-x-2 z-50">
                    <i class="fas fa-times-circle"></i>
                    <span>Gagal mengunggah file.</span>
                    <button type="button" onclick="closeErrorMessage()" class="ml-2 font-bold">×</button>
                </div>

                <!-- Form Fields -->
                <div class="grid grid-cols-2 gap-4 px-4 py-4">
                    <div class="col-span-2 mb-2">
                        <label for="full_name" class="block text-med font-semibold mb-2">Nama Lengkap</label>
                        <input type="text" name="full_name" id="full_name" placeholder="Masukkan nama Anda" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#4F3A2D] focus:border-[#4F3A2D]" required>
                    </div>

                    <div class="mb-2">
                        <label for="leave_type" class="block text-med font-semibold mb-2">Jenis Cuti</label>
                        <select name="leave_type" id="leave_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#4F3A2D] focus:border-[#4F3A2D]" required>
                            <option value="" disabled selected>Pilih jenis cuti</option>
                            <option value="Cuti Tahunan">Cuti Tahunan</option>
                            <option value="Cuti Sakit">Cuti Sakit</option>
                            <option value="Cuti Besar">Cuti Besar</option>
                            <option value="Izin">Izin</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label for="department" class="block text-med font-semibold mb-2">Department</label>
                        <select name="department" id="department" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#4F3A2D] focus:border-[#4F3A2D]" required>
                            <option value="" disabled selected>Pilih department</option>
                            <option value="Datin">Datin</option>
                            <option value="Program">Program</option>
                            <option value="Keuangan">Keuangan</option>
                            <option value="Simak BMN">Simak BMN</option>
                            <option value="TU">Tata Usaha</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label for="phone_number" class="block text-med font-semibold mb-2">Nomor Telephone</label>
                        <div class="flex">
                            <span class="flex border border-gray-300 rounded-l-lg overflow-hidden">
                                <span class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 rounded-l-lg">+62</span>
                            </span>
                            <input type="tel" name="phone_number" id="phone_number" placeholder="Masukkan nomor telepon" class="flex-1 px-4 py-2.5 border border-l-0 border-gray-300 rounded-r-lg focus:outline-none focus:ring-1 focus:ring-[#4F3A2D] focus:border-[#4F3A2D]" required>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="nip" class="block text-med font-semibold mb-2">NIP</label>
                        <input type="text" name="nip" id="nip" placeholder="Masukkan NIP Anda" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#4F3A2D] focus:border-[#4F3A2D]" required>
                    </div>

                    <div class="col-span-2 grid grid-cols-2 gap-4">
                        <div class="mb-2">
                            <label for="start_date" class="block text-med font-semibold mb-2">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#4F3A2D] focus:border-[#4F3A2D]" required>
                        </div>
                        <div class="mb-2">
                            <label for="end_date" class="block text-med font-semibold mb-2">Tanggal Selesai</label>
                            <input type="date" name="end_date" id="end_date" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#4F3A2D] focus:border-[#4F3A2D]" required>
                        </div>
                    </div>

                    <div class="col-span-2 mb-2">
                        <label for="total_days" class="block text-med font-semibold mb-2">Total Hari Cuti</label>
                        <input type="number" name="total_days" id="total_days" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100" required readonly>
                    </div>

                    <div class="col-span-2 mb-2">
                        <label for="reason" class="block text-med font-semibold mb-2">Alasan</label>
                        <textarea name="reason" id="reason" rows="4" placeholder="Deskripsikan alasan Anda di sini" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#4F3A2D] focus:border-[#4F3A2D]" required></textarea>
                    </div>
                </div>

                <div class="flex justify-end mt-6 px-4 py-4">
                    <button type="reset" onclick="return confirm('Yakin ingin menghapus semua isian?')" class="px-8 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors mr-2">
                        Batal
                    </button>
                    <button type="submit" class="px-8 py-2.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                        Next
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>

@push('scripts')
<script src="{{ asset('js/upload-handler.js') }}"></script>
@endpush
@endsection