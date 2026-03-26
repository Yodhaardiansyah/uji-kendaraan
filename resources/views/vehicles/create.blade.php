@extends('layouts.app')

@section('title', 'Tambah Kendaraan - Dishub System')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-primary mb-0"><i class="bi bi-truck me-2"></i>Tambah Data Kendaraan</h4>
            <a href="{{ route('vehicles.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        {{-- ERROR --}}
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

        <form method="POST" action="{{ route('vehicles.store') }}">
            @csrf

            {{-- ================= A. IDENTITAS PEMILIK ================= --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white fw-bold pt-3">A. Identitas Pemilik Kendaraan</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Nama Pemilik <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-select" required>
                                <option value="">-- Pilih Pemilik dari Data User --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                            <input type="text" name="no_uji" class="form-control" value="{{ old('no_uji') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">No Kendaraan (Plat) <span class="text-danger">*</span></label>
                            <input type="text" name="no_kendaraan" class="form-control text-uppercase" value="{{ old('no_kendaraan') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nomor Rangka <span class="text-danger">*</span></label>
                            <input type="text" name="no_rangka" class="form-control text-uppercase" value="{{ old('no_rangka') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nomor Mesin <span class="text-danger">*</span></label>
                            <input type="text" name="no_mesin" class="form-control text-uppercase" value="{{ old('no_mesin') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">No SRUT</label>
                            <input type="text" name="no_srut" class="form-control" value="{{ old('no_srut') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal SRUT</label>
                            <input type="date" name="tgl_srut" class="form-control" value="{{ old('tgl_srut') }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= C. SPESIFIKASI TEKNIS ================= --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white fw-bold pt-3">C. Spesifikasi Teknis Kendaraan</div>
                <div class="card-body">
                    
                    {{-- Sub 1: Info Dasar Mesin & Tipe --}}
                    <div class="p-3 bg-light rounded mb-4">
                        <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2">1. Data Dasar & Mesin</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Merk <span class="text-danger">*</span></label>
                                <input type="text" name="merk" class="form-control" value="{{ old('merk') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tipe <span class="text-danger">*</span></label>
                                <input type="text" name="tipe" class="form-control" value="{{ old('tipe') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                                <select name="jenis" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Sepeda Motor" {{ old('jenis') == 'Sepeda Motor' ? 'selected' : '' }}>Sepeda Motor</option>
                                    <option value="Mobil Penumpang" {{ old('jenis') == 'Mobil Penumpang' ? 'selected' : '' }}>Mobil Penumpang</option>
                                    <option value="Mobil Bus" {{ old('jenis') == 'Mobil Bus' ? 'selected' : '' }}>Mobil Bus</option>
                                    <option value="Mobil Barang" {{ old('jenis') == 'Mobil Barang' ? 'selected' : '' }}>Mobil Barang</option>
                                    <option value="Kendaraan Khusus" {{ old('jenis') == 'Kendaraan Khusus' ? 'selected' : '' }}>Kendaraan Khusus</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tahun Pembuatan <span class="text-danger">*</span></label>
                                <input type="number" name="tahun" class="form-control" value="{{ old('tahun') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Bahan Bakar <span class="text-danger">*</span></label>
                                <select name="bahan_bakar" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Bensin" {{ old('bahan_bakar')=='Bensin'?'selected':'' }}>Bensin</option>
                                    <option value="Solar" {{ old('bahan_bakar')=='Solar'?'selected':'' }}>Solar</option>
                                    <option value="Listrik" {{ old('bahan_bakar')=='Listrik'?'selected':'' }}>Listrik</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Isi Silinder</label>
                                <div class="input-group">
                                    <input type="number" name="cc" class="form-control" value="{{ old('cc') }}">
                                    <span class="input-group-text">CC</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Daya Motor (HP)</label>
                                <div class="input-group">
                                    <input type="number" name="daya_hp" class="form-control" value="{{ old('daya_hp') }}">
                                    <span class="input-group-text">HP</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sub 2: Berat Kendaraan --}}
                    <div class="p-3 bg-light rounded mb-4">
                        <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2">2. Kapasitas & Berat</h6>
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">JBB</label>
                                <div class="input-group"><input type="number" name="jbb" class="form-control" value="{{ old('jbb') }}"><span class="input-group-text px-2">Kg</span></div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">JBKB</label>
                                <div class="input-group"><input type="number" name="jbkb" class="form-control" value="{{ old('jbkb') }}"><span class="input-group-text px-2">Kg</span></div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">JBI</label>
                                <div class="input-group"><input type="number" name="jbi" class="form-control" value="{{ old('jbi') }}"><span class="input-group-text px-2">Kg</span></div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">JBKI</label>
                                <div class="input-group"><input type="number" name="jbki" class="form-control" value="{{ old('jbki') }}"><span class="input-group-text px-2">Kg</span></div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">MST</label>
                                <div class="input-group"><input type="number" name="mst" class="form-control" value="{{ old('mst') }}"><span class="input-group-text px-2">Kg</span></div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Brt Kosong</label>
                                <div class="input-group"><input type="number" name="berat_kosong" class="form-control" value="{{ old('berat_kosong') }}"><span class="input-group-text px-2">Kg</span></div>
                            </div>
                        </div>
                    </div>

                    {{-- Sub 3: Ban & Sumbu --}}
                    <div class="p-3 bg-light rounded mb-4">
                        <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2">3. Konfigurasi Roda, Ban & Sumbu</h6>
                        <div class="row g-3">
                            <div class="col-md-12 mb-2">
                                <label class="form-label fw-semibold d-block">Ukuran Ban (Dipisah)</label>
                                <div class="row g-2">
                                    <div class="col-md-4"><input type="text" name="ban_depan" class="form-control" value="{{ old('ban_depan') }}" placeholder="Ban Depan (Cth: 750)"></div>
                                    <div class="col-md-4"><input type="text" name="ban_belakang" class="form-control" value="{{ old('ban_belakang') }}" placeholder="Ban Belakang (Cth: 16)"></div>
                                    <div class="col-md-4"><input type="text" name="ban_ring" class="form-control" value="{{ old('ban_ring') }}" placeholder="Ring (Cth: 14)"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Konfigurasi Sumbu</label>
                                <input type="text" name="konfigurasi_sumbu" class="form-control" value="{{ old('konfigurasi_sumbu') }}" placeholder="Cth: 1.2">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Jarak Sumbu I-II</label>
                                <div class="input-group"><input type="number" name="sumbu_1_2" class="form-control" value="{{ old('sumbu_1_2') }}"><span class="input-group-text">mm</span></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Jarak Sumbu II-III</label>
                                <div class="input-group"><input type="number" name="sumbu_2_3" class="form-control" value="{{ old('sumbu_2_3') }}"><span class="input-group-text">mm</span></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Jarak Sumbu III-IV</label>
                                <div class="input-group"><input type="number" name="sumbu_3_4" class="form-control" value="{{ old('sumbu_3_4') }}"><span class="input-group-text">mm</span></div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jalur Depan</label>
                                <div class="input-group"><input type="number" name="jalur_depan" class="form-control" value="{{ old('jalur_depan') }}"><span class="input-group-text">mm</span></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jalur Belakang</label>
                                <div class="input-group"><input type="number" name="jalur_belakang" class="form-control" value="{{ old('jalur_belakang') }}"><span class="input-group-text">mm</span></div>
                            </div>
                        </div>
                    </div>

                    {{-- Sub 4: Dimensi --}}
                    <div class="p-3 bg-light rounded mb-4">
                        <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2">4. Dimensi Utama & Bak/Tangki</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary">Dimensi Utama Kendaraan</label>
                                <div class="row g-2">
                                    <div class="col-4">
                                        <div class="input-group"><input type="number" name="panjang" class="form-control" value="{{ old('panjang') }}" placeholder="Panjang"><span class="input-group-text p-1">mm</span></div>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group"><input type="number" name="lebar" class="form-control" value="{{ old('lebar') }}" placeholder="Lebar"><span class="input-group-text p-1">mm</span></div>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group"><input type="number" name="tinggi" class="form-control" value="{{ old('tinggi') }}" placeholder="Tinggi"><span class="input-group-text p-1">mm</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-primary">Dimensi Bak/Tangki</label>
                                <div class="row g-2">
                                    <div class="col-4">
                                        <div class="input-group"><input type="number" name="panjang_bak" class="form-control" value="{{ old('panjang_bak') }}" placeholder="Panjang"><span class="input-group-text p-1">mm</span></div>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group"><input type="number" name="lebar_bak" class="form-control" value="{{ old('lebar_bak') }}" placeholder="Lebar"><span class="input-group-text p-1">mm</span></div>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group"><input type="number" name="tinggi_bak" class="form-control" value="{{ old('tinggi_bak') }}" placeholder="Tinggi"><span class="input-group-text p-1">mm</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sub 5: Daya Angkut & Jalan --}}
                    <div class="p-3 bg-light rounded">
                        <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2">5. Daya Angkut & Kelas Jalan</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Daya Angkut Orang</label>
                                <div class="input-group">
                                    <input type="number" name="daya_orang" class="form-control" value="{{ old('daya_orang') }}" placeholder="Jumlah">
                                    <span class="input-group-text">Penumpang</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Daya Angkut Barang</label>
                                <div class="input-group">
                                    <input type="number" name="daya_barang" class="form-control" value="{{ old('daya_barang') }}" placeholder="Berat">
                                    <span class="input-group-text">Kg</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Kelas Jalan Terendah</label>
                                <select name="kelas_jalan" class="form-select">
                                    <option value="">-- Pilih --</option>
                                    <option value="Kelas I" {{ old('kelas_jalan') == 'Kelas I' ? 'selected' : '' }}>Kelas I</option>
                                    <option value="Kelas II" {{ old('kelas_jalan') == 'Kelas II' ? 'selected' : '' }}>Kelas II</option>
                                    <option value="Kelas III" {{ old('kelas_jalan') == 'Kelas III' ? 'selected' : '' }}>Kelas III</option>
                                    <option value="Kelas Khusus" {{ old('kelas_jalan') == 'Kelas Khusus' ? 'selected' : '' }}>Kelas Khusus</option>
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
                                    <option value="{{ $dishub->nama }}" {{ old('wilayah') == $dishub->nama ? 'selected' : '' }}>
                                        {{ $dishub->nama }} ({{ $dishub->singkatan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUBMIT BUTTONS --}}
            <div class="text-end mb-5">
                <button type="reset" class="btn btn-light shadow-sm me-2 border"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</button>
                <button type="submit" class="btn btn-primary shadow-sm px-4"><i class="bi bi-save me-1"></i> Simpan Data Kendaraan</button>
            </div>

        </form>
    </div>
</div>
@endsection