{{-- Mewarisi kerangka utama website --}}
@extends('layouts.app')

{{-- Menentukan judul tab browser secara dinamis dengan menyertakan Nomor Uji --}}
@section('title', 'Edit Kendaraan: ' . $vehicle->no_uji . ' - Dishub System')

{{-- Membuka blok konten utama --}}
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">

        {{-- ================= HEADER & TOMBOL KEMBALI ================= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-primary mb-0">
                <i class="bi bi-pencil-square me-2"></i>Edit Data Kendaraan
                <span class="text-secondary fs-6 fw-normal ms-2">(No Uji: {{ $vehicle->no_uji }})</span>
            </h4>
            <a href="{{ route('vehicles.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        {{-- ================= BLOK NOTIFIKASI ERROR ================= --}}
        {{-- Muncul jika validasi di VehicleController@update gagal --}}
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm alert-dismissible fade show">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat kesalahan:</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- 
          FORM UPDATE DATA 
          Action: Mengarah ke route update dengan parameter ID kendaraan terkait.
          Method: POST yang di-spoofing menjadi PUT menggunakan @method('PUT').
        --}}
        <form method="POST" action="{{ route('vehicles.update', $vehicle->id) }}">
            @csrf
            @method('PUT') 

            {{-- ================= A. IDENTITAS PEMILIK ================= --}}
            {{-- Bagian untuk mengubah relasi kendaraan ke pemilik (User) lain --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white fw-bold pt-3">A. Identitas Pemilik Kendaraan</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Nama Pemilik <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select" required>
                                <option value="">-- Pilih Pemilik dari Data User --</option>
                                @foreach ($users as $user)
                                    {{-- 
                                      LOGIKA SELECTION:
                                      1. Cek inputan terakhir user (old).
                                      2. Jika tidak ada, gunakan user_id yang tersimpan di database saat ini.
                                    --}}
                                    <option value="{{ $user->id }}" {{ old('user_id', $vehicle->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= B. IDENTITAS KENDARAAN ================= --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white fw-bold pt-3">B. Identitas Kendaraan Bermotor</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">No Uji Kendaraan <span class="text-danger">*</span></label>
                            <input type="text" name="no_uji" class="form-control" value="{{ old('no_uji', $vehicle->no_uji) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">No Kendaraan (Plat) <span class="text-danger">*</span></label>
                            <input type="text" name="no_kendaraan" class="form-control text-uppercase" value="{{ old('no_kendaraan', $vehicle->no_kendaraan) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nomor Rangka <span class="text-danger">*</span></label>
                            <input type="text" name="no_rangka" class="form-control text-uppercase" value="{{ old('no_rangka', $vehicle->no_rangka) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nomor Mesin <span class="text-danger">*</span></label>
                            <input type="text" name="no_mesin" class="form-control text-uppercase" value="{{ old('no_mesin', $vehicle->no_mesin) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">No SRUT</label>
                            <input type="text" name="no_srut" class="form-control" value="{{ old('no_srut', $vehicle->no_srut) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal SRUT</label>
                            {{-- Memformat objek Carbon menjadi string Y-m-d agar bisa terbaca oleh tag input date --}}
                            <input type="date" name="tgl_srut" class="form-control" value="{{ old('tgl_srut', $vehicle->tgl_srut ? $vehicle->tgl_srut->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= C. SPESIFIKASI TEKNIS ================= --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white fw-bold pt-3">C. Spesifikasi Teknis Kendaraan</div>
                <div class="card-body">
                    
                    {{-- 1. Data Dasar & Mesin --}}
                    <div class="p-3 bg-light rounded mb-4 border">
                        <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2">1. Data Dasar & Mesin</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Merk <span class="text-danger">*</span></label>
                                <input type="text" name="merk" class="form-control" value="{{ old('merk', $vehicle->merk) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tipe <span class="text-danger">*</span></label>
                                <input type="text" name="tipe" class="form-control" value="{{ old('tipe', $vehicle->tipe) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                                <select name="jenis" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    {{-- Looping array statis untuk pilihan Jenis Kendaraan --}}
                                    @foreach(['Sepeda Motor', 'Mobil Penumpang', 'Mobil Bus', 'Mobil Barang', 'Kendaraan Khusus'] as $jenis)
                                        <option value="{{ $jenis }}" {{ old('jenis', $vehicle->jenis) == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tahun Pembuatan <span class="text-danger">*</span></label>
                                <input type="number" name="tahun" class="form-control" value="{{ old('tahun', $vehicle->tahun) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Bahan Bakar <span class="text-danger">*</span></label>
                                <select name="bahan_bakar" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach(['Bensin', 'Solar', 'Listrik'] as $bb)
                                        <option value="{{ $bb }}" {{ old('bahan_bakar', $vehicle->bahan_bakar) == $bb ? 'selected' : '' }}>{{ $bb }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Isi Silinder</label>
                                <div class="input-group">
                                    <input type="number" name="cc" class="form-control" value="{{ old('cc', $vehicle->cc) }}">
                                    <span class="input-group-text">CC</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Daya Motor (HP)</label>
                                <div class="input-group">
                                    <input type="number" name="daya_hp" class="form-control" value="{{ old('daya_hp', $vehicle->daya_hp) }}">
                                    <span class="input-group-text">HP</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Kapasitas & Berat --}}
                    <div class="p-3 bg-light rounded mb-4 border">
                        <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2">2. Kapasitas & Berat</h6>
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label fw-semibold small">JBB (Kg)</label>
                                <input type="number" name="jbb" class="form-control" value="{{ old('jbb', $vehicle->jbb) }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold small">JBKB (Kg)</label>
                                <input type="number" name="jbkb" class="form-control" value="{{ old('jbkb', $vehicle->jbkb) }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold small">JBI (Kg)</label>
                                <input type="number" name="jbi" class="form-control" value="{{ old('jbi', $vehicle->jbi) }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold small">JBKI (Kg)</label>
                                <input type="number" name="jbki" class="form-control" value="{{ old('jbki', $vehicle->jbki) }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold small">MST (Kg)</label>
                                <input type="number" name="mst" class="form-control" value="{{ old('mst', $vehicle->mst) }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold small">Kosong (Kg)</label>
                                <input type="number" name="berat_kosong" class="form-control" value="{{ old('berat_kosong', $vehicle->berat_kosong) }}">
                            </div>
                        </div>
                    </div>

                    {{-- 3. Ban & Sumbu --}}
                    <div class="p-3 bg-light rounded mb-4 border">
                        <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2">3. Konfigurasi Roda, Ban & Sumbu</h6>
                        <div class="row g-3">
                            <div class="col-md-12 mb-2">
                                <label class="form-label fw-semibold d-block">Ukuran Ban (Dipisah)</label>
                                <div class="row g-2">
                                    <div class="col-md-4"><input type="text" name="ban_depan" class="form-control" value="{{ old('ban_depan', $vehicle->ban_depan) }}" placeholder="Ban Depan"></div>
                                    <div class="col-md-4"><input type="text" name="ban_belakang" class="form-control" value="{{ old('ban_belakang', $vehicle->ban_belakang) }}" placeholder="Ban Belakang"></div>
                                    <div class="col-md-4"><input type="text" name="ban_ring" class="form-control" value="{{ old('ban_ring', $vehicle->ban_ring) }}" placeholder="Ring"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Konfigurasi Sumbu</label>
                                <input type="text" name="konfigurasi_sumbu" class="form-control" value="{{ old('konfigurasi_sumbu', $vehicle->konfigurasi_sumbu) }}" placeholder="Cth: 1.2">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Jarak Sumbu I-II</label>
                                <div class="input-group"><input type="number" name="sumbu_1_2" class="form-control" value="{{ old('sumbu_1_2', $vehicle->sumbu_1_2) }}"><span class="input-group-text">mm</span></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Jarak Sumbu II-III</label>
                                <div class="input-group"><input type="number" name="sumbu_2_3" class="form-control" value="{{ old('sumbu_2_3', $vehicle->sumbu_2_3) }}"><span class="input-group-text">mm</span></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Jarak Sumbu III-IV</label>
                                <div class="input-group"><input type="number" name="sumbu_3_4" class="form-control" value="{{ old('sumbu_3_4', $vehicle->sumbu_3_4) }}"><span class="input-group-text">mm</span></div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jalur Depan</label>
                                <div class="input-group"><input type="number" name="jalur_depan" class="form-control" value="{{ old('jalur_depan', $vehicle->jalur_depan) }}"><span class="input-group-text">mm</span></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jalur Belakang</label>
                                <div class="input-group"><input type="number" name="jalur_belakang" class="form-control" value="{{ old('jalur_belakang', $vehicle->jalur_belakang) }}"><span class="input-group-text">mm</span></div>
                            </div>
                        </div>
                    </div>

                    {{-- 4. Dimensi --}}
                    <div class="p-3 bg-light rounded mb-4 border">
                        <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2">4. Dimensi Utama & Bak/Tangki</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary">Dimensi Utama Kendaraan</label>
                                <div class="row g-2">
                                    <div class="col-4">
                                        <div class="input-group"><input type="number" name="panjang" class="form-control" value="{{ old('panjang', $vehicle->panjang) }}" placeholder="Panjang"><span class="input-group-text p-1">mm</span></div>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group"><input type="number" name="lebar" class="form-control" value="{{ old('lebar', $vehicle->lebar) }}" placeholder="Lebar"><span class="input-group-text p-1">mm</span></div>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group"><input type="number" name="tinggi" class="form-control" value="{{ old('tinggi', $vehicle->tinggi) }}" placeholder="Tinggi"><span class="input-group-text p-1">mm</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary">Dimensi Bak/Tangki</label>
                                <div class="row g-2">
                                    <div class="col-4">
                                        <div class="input-group"><input type="number" name="panjang_bak" class="form-control" value="{{ old('panjang_bak', $vehicle->panjang_bak) }}" placeholder="Panjang"><span class="input-group-text p-1">mm</span></div>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group"><input type="number" name="lebar_bak" class="form-control" value="{{ old('lebar_bak', $vehicle->lebar_bak) }}" placeholder="Lebar"><span class="input-group-text p-1">mm</span></div>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group"><input type="number" name="tinggi_bak" class="form-control" value="{{ old('tinggi_bak', $vehicle->tinggi_bak) }}" placeholder="Tinggi"><span class="input-group-text p-1">mm</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 5. Daya Angkut & Jalan --}}
                    <div class="p-3 bg-light rounded border">
                        <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2">5. Daya Angkut & Kelas Jalan</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Daya Angkut Orang</label>
                                <div class="input-group">
                                    <input type="number" name="daya_orang" class="form-control" value="{{ old('daya_orang', $vehicle->daya_orang) }}" placeholder="Jumlah">
                                    <span class="input-group-text">Penumpang</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Daya Angkut Barang</label>
                                <div class="input-group">
                                    <input type="number" name="daya_barang" class="form-control" value="{{ old('daya_barang', $vehicle->daya_barang) }}" placeholder="Berat">
                                    <span class="input-group-text">Kg</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Kelas Jalan Terendah</label>
                                <select name="kelas_jalan" class="form-select">
                                    <option value="">-- Pilih --</option>
                                    @foreach(['Kelas I', 'Kelas II', 'Kelas III', 'Kelas Khusus'] as $kelas)
                                        <option value="{{ $kelas }}" {{ old('kelas_jalan', $vehicle->kelas_jalan) == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ================= D. WILAYAH ASAL ================= --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white fw-bold pt-3">D. Wilayah Asal</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Nama Wilayah Asal <span class="text-danger">*</span></label>
                            <select name="wilayah" class="form-select" required>
                                <option value="">-- Pilih Wilayah Asal --</option>
                                @foreach ($dishubs as $dishub)
                                    <option value="{{ $dishub->nama }}" {{ old('wilayah', $vehicle->wilayah) == $dishub->nama ? 'selected' : '' }}>
                                        {{ $dishub->nama }} ({{ $dishub->singkatan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= TOMBOL AKSI ================= --}}
            <div class="text-end mb-5">
                {{-- Tombol Batal: Mengembalikan ke halaman index kendaraan --}}
                <a href="{{ route('vehicles.index') }}" class="btn btn-light shadow-sm me-2 border"><i class="bi bi-x-circle me-1"></i> Batal</a>
                {{-- Tombol Simpan: Mengirimkan form --}}
                <button type="submit" class="btn btn-primary shadow-sm px-4"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
            </div>

        </form>
    </div>
</div>
@endsection