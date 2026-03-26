@extends('layouts.app')
@section('title', 'Input Hasil Uji - ' . $vehicle->no_kendaraan)

@section('content')
<div class="container-fluid pb-5">
    <form action="{{ route('inspections.store') }}" method="POST" enctype="multipart/form-data" id="formInspection">
        @csrf
        
        {{-- Relasi Utama --}}
        <input type="hidden" name="rfid_id" value="{{ $rfid->id }}">

        @if ($errors->any())
        <div class="alert alert-danger shadow-sm border-0 mb-4">
            <div class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Gagal Menyimpan! Periksa isian berikut:</div>
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0 text-primary"><i class="bi bi-clipboard-check me-2"></i>Form Pengujian Kendaraan Berkala</h4>
                <p class="text-muted mb-0 small">RFID: <span class="fw-bold text-dark">{{ $rfid->kode_rfid }}</span> | Plat: <span class="badge bg-dark">{{ $vehicle->no_kendaraan }}</span></p>
            </div>
            <a href="{{ route('inspections.index', $rfid->id) }}" class="btn btn-outline-secondary shadow-sm">
                <i class="bi bi-x-lg"></i> Batal
            </a>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white p-0">
                <ul class="nav nav-tabs nav-fill border-bottom-0" id="inspectionTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active py-3 fw-bold border-0" id="tab-1" data-bs-toggle="tab" data-bs-target="#step-1" type="button">1. FOTO & VISUAL</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-3 fw-bold border-0" id="tab-2" data-bs-toggle="tab" data-bs-target="#step-2" type="button">2. MANUAL</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-3 fw-bold border-0" id="tab-3" data-bs-toggle="tab" data-bs-target="#step-3" type="button">3. ALAT UJI</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-3 fw-bold border-0" id="tab-4" data-bs-toggle="tab" data-bs-target="#step-4" type="button">4. FINALISASI</button>
                    </li>
                </ul>
            </div>

            <div class="card-body bg-light p-4">
                <div class="tab-content" id="inspectionTabContent">
                    
                    {{-- STEP 1: A. FOTO & B. VISUAL --}}
                    <div class="tab-pane fade show active" id="step-1" role="tabpanel">
                        <h6 class="fw-bold text-primary mb-3">A. FOTO KENDARAAN</h6>
                        <div class="row mb-4">
                            @foreach(['depan', 'belakang', 'kanan', 'kiri'] as $pos)
                            <div class="col-md-3 mb-3">
                                <label for="foto_{{ $pos }}" class="form-label small fw-bold">Foto {{ ucfirst($pos) }}</label>
                                <input type="file" name="foto_{{ $pos }}" id="foto_{{ $pos }}" class="form-control shadow-sm border-0">
                            </div>
                            @endforeach
                        </div>

                        <hr>

                        <h6 class="fw-bold text-primary mb-3">B. PEMERIKSAAN VISUAL (Geser jika Baik)</h6>
                        <div class="row g-2">
                            @php
                                $visuals = [
                                    'rangka' => 'Nomor & Kondisi Rangka', 'mesin' => 'Nomor & Tipe Motor Penggerak',
                                    'tangki' => 'Kondisi Tangki/Pipa BBM', 'pembuangan' => 'Kondisi Pipa Pembuangan',
                                    'ban' => 'Ukuran & Kondisi Ban', 'suspensi' => 'Kondisi Sistem Suspensi',
                                    'rem_utama' => 'Kondisi Sistem Rem Utama', 'lampu' => 'Penutup Lampu & Pemantul',
                                    'dashboard' => 'Panel Dashboard', 'spion' => 'Kondisi Kaca Spion',
                                    'spakbor' => 'Kondisi Spakbor', 'bumper' => 'Bentuk Bumper',
                                    'perlengkapan' => 'Perlengkapan Kendaraan', 'teknis' => 'Rancangan Teknis',
                                    'darurat' => 'Fasilitas Tanggap Darurat', 'badan' => 'Badan/Kaca/Engsel/Kursi',
                                    'converter' => 'Kondisi Converter Kit'
                                ];
                            @endphp
                            @foreach($visuals as $key => $label)
                            <div class="col-md-4 mb-2">
                                <div class="card border-0 p-2 shadow-sm h-100">
                                    <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-between">
                                        <label class="form-check-label small fw-semibold" for="v_{{ $key }}">{{ $label }}</label>
                                        <input class="form-check-input ms-0" type="checkbox" name="{{ $key }}" value="1" id="v_{{ $key }}">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- STEP 2: C. PEMERIKSAAN MANUAL --}}
                    <div class="tab-pane fade" id="step-2" role="tabpanel">
                        <h6 class="fw-bold text-primary mb-3">C. PEMERIKSAAN MANUAL (Geser jika Baik)</h6>
                        <div class="row g-2">
                            @php
                                $manuals = [
                                    'penerus_daya' => 'Kondisi Penerus Daya', 'kemudi' => 'Sudut Bebas Kemudi',
                                    'rem_parkir' => 'Kondisi Rem Parkir', 'lampu_manual' => 'Fungsi Lampu & Pemantul',
                                    'wiper' => 'Fungsi Penghapus Kaca', 'kaca' => 'Tingkat Kegelapan Kaca',
                                    'klakson' => 'Fungsi Klakson', 'sabuk' => 'Sabuk Keselamatan',
                                    'ukuran' => 'Ukuran Kendaraan', 'kursi' => 'Akses Keluar Darurat (Bus)'
                                ];
                            @endphp
                            @foreach($manuals as $key => $label)
                            <div class="col-md-6 mb-2">
                                <div class="card border-0 p-3 shadow-sm h-100">
                                    <div class="form-check form-switch mb-0 d-flex align-items-center justify-content-between">
                                        <label class="form-check-label fw-semibold" for="m_{{ $key }}">{{ $label }}</label>
                                        <input class="form-check-input ms-0" type="checkbox" name="{{ $key }}" value="1" id="m_{{ $key }}">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- STEP 3: D - H. PEMERIKSAAN ALAT UJI --}}
                    <div class="tab-pane fade" id="step-3" role="tabpanel">
                        <div class="row g-4">
                            {{-- D. Emisi --}}
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm p-3 h-100">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">D. EMISI</h6>
                                    <div class="row g-3">
                                        <div class="col-12"><label class="small fw-bold">Solar (%)</label><input type="number" step="0.01" name="emisi_solar" class="form-control"></div>
                                        <div class="col-6"><label class="small fw-bold">Bensin - CO (%)</label><input type="number" step="0.01" name="emisi_co" class="form-control"></div>
                                        <div class="col-6"><label class="small fw-bold">Bensin - HC (ppm)</label><input type="number" name="emisi_hc" class="form-control"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- F. Lampu Utama --}}
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm p-3 h-100">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">F. LAMPU UTAMA</h6>
                                    <div class="row g-3">
                                        <div class="col-6"><label class="small fw-bold">Pancar Kanan (cd)</label><input type="number" name="lampu_kanan" class="form-control"></div>
                                        <div class="col-6"><label class="small fw-bold">Pancar Kiri (cd)</label><input type="number" name="lampu_kiri" class="form-control"></div>
                                        <div class="col-6"><label class="small fw-bold">Deviasi Kanan</label><input type="number" step="0.01" name="deviasi_kanan" class="form-control"></div>
                                        <div class="col-6"><label class="small fw-bold">Deviasi Kiri</label><input type="number" step="0.01" name="deviasi_kiri" class="form-control"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- D. Rem Utama & Parkir --}}
                            <div class="col-12">
                                <div class="card border-0 shadow-sm p-3">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">D. REM UTAMA & PARKIR</h6>
                                    <div class="row g-3">
                                        <div class="col-md-4"><label class="small fw-bold">Total Gaya Rem Utama (%)</label><input type="number" step="0.1" name="rem_utama_total" class="form-control bg-light fw-bold"></div>
                                        <div class="col-md-2"><label class="small fw-bold">Selisih I (%)</label><input type="number" step="0.1" name="rem_utama_selisih_1" class="form-control"></div>
                                        <div class="col-md-2"><label class="small fw-bold">Selisih II (%)</label><input type="number" step="0.1" name="rem_utama_selisih_2" class="form-control"></div>
                                        <div class="col-md-2"><label class="small fw-bold">Selisih III (%)</label><input type="number" step="0.1" name="rem_utama_selisih_3" class="form-control"></div>
                                        <div class="col-md-2"><label class="small fw-bold">Selisih IV (%)</label><input type="number" step="0.1" name="rem_utama_selisih_4" class="form-control"></div>
                                        <div class="col-md-6"><label class="small fw-bold">Rem Parkir Tangan (%)</label><input type="number" step="0.1" name="rem_parkir_tangan" class="form-control"></div>
                                        <div class="col-md-6"><label class="small fw-bold">Rem Parkir Kaki (%)</label><input type="number" step="0.1" name="rem_parkir_kaki" class="form-control"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- E, G, H & Kincup --}}
                            <div class="col-12">
                                <div class="card border-0 shadow-sm p-3">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">E, G, H. KEBISINGAN, KECEPATAN & BAN</h6>
                                    <div class="row g-3">
                                        <div class="col-md-3"><label class="small fw-bold">Kebisingan Klakson (db)</label><input type="number" name="kebisingan" class="form-control"></div>
                                        <div class="col-md-3"><label class="small fw-bold">Kincup Roda Depan (mm/mnt)</label><input type="number" step="0.1" name="kincup_roda_depan" class="form-control"></div>
                                        <div class="col-md-3"><label class="small fw-bold">Speed Deviasi (km/jam)</label><input type="number" step="0.1" name="speed_deviasi" class="form-control"></div>
                                        <div class="col-md-3"><label class="small fw-bold">Alur Ban (mm)</label><input type="number" step="0.1" name="alur_ban" class="form-control"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- STEP 4: I - M. FINALISASI --}}
                    <div class="tab-pane fade" id="step-4" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm p-3 border-top border-warning h-100">
                                    <h6 class="fw-bold mb-3">I. KETERANGAN HASIL UJI</h6>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">Hasil Akhir</label>
                                        <select name="hasil" class="form-select fw-bold" required>
                                            <option value="Menunggu Hasil Uji">-- Pilih Hasil --</option>
                                            <option value="Lolos Uji Berkala" class="text-success">Lolos Uji Berkala</option>
                                            <option value="Tidak Lolos Uji Berkala" class="text-danger">Tidak Lolos Uji Berkala</option>
                                        </select>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6"><label class="small fw-bold">Tanggal Uji</label><input type="date" name="tgl_uji" class="form-control" value="{{ date('Y-m-d') }}"></div>
                                        <div class="col-6">
                                            <label class="small fw-bold">Masa Berlaku</label>
                                            <input type="date" name="tgl_berlaku" class="form-control" value="{{ now()->addMonths(6)->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label class="small fw-bold text-muted">J. Unit Pelaksana</label>
                                        {{-- Mengambil dinas dari tabel admin (jika ada), atau fallback ke nama Dishub wilayah --}}
                                        <input type="text" name="nama_unit" class="form-control form-control-sm" 
                                            value="{{ Auth::guard('admin')->user()->dinas ?? ($dishub->nama ?? '-') }}" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm p-3 h-100">
                                    <h6 class="fw-bold mb-3 text-secondary">K, L, M. PETUGAS & PEJABAT</h6>
                                    <div class="mb-3 bg-light p-2 rounded">
                                        <label class="small fw-bold text-primary">K. Petugas Penguji</label>
                                        <input type="text" name="nama_petugas" class="form-control form-control-sm mb-1" value="{{ Auth::guard('admin')->user()->nama }}" readonly>
                                        <input type="text" name="nrp" class="form-control form-control-sm mb-1" value="{{ Auth::guard('admin')->user()->nrp }}" readonly>
                                        <input type="text" name="pangkat_petugas" class="form-control form-control-sm" value="{{ Auth::guard('admin')->user()->pangkat }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="small fw-bold text-primary">L. Kepala Dinas</label>
                                        <input type="text" name="kepala_dinas_nama" class="form-control form-control-sm mb-1" value="{{ $dishub->kepala_dinas_nama ?? '' }}" readonly>
                                        <input type="text" name="kepala_dinas_nip" class="form-control form-control-sm mb-1" value="{{ $dishub->kepala_dinas_nip ?? '' }}" readonly>
                                        <input type="text" name="kepala_dinas_pangkat" class="form-control form-control-sm bg-white" value="Kepala Dinas" readonly>
                                    </div>
                                    <div>
                                        <label class="small fw-bold text-primary">M. Direktur</label>
                                        <input type="text" name="direktur_nama" class="form-control form-control-sm mb-1" value="{{ $dishub->direktur_nama ?? '' }}" readonly>
                                        <input type="text" name="direktur_nip" class="form-control form-control-sm mb-1" value="{{ $dishub->direktur_nip ?? '' }}" readonly>
                                        <input type="text" name="direktur_pangkat" class="form-control form-control-sm bg-white" value="Direktur" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- FOOTER NAVIGASI --}}
            <div class="card-footer bg-white p-3 d-flex justify-content-between">
                <button type="button" class="btn btn-light px-4 fw-bold border" id="prevBtn" onclick="nextPrev(-1)">KEMBALI</button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary px-4 fw-bold" id="nextBtn" onclick="nextPrev(1)">LANJUT</button>
                    <button type="submit" class="btn btn-success px-5 fw-bold d-none" id="submitBtn">SIMPAN DATA UJI</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let currentTab = 1;
    const totalTabs = 4;

    function showTab(n) {
        document.getElementById("prevBtn").style.visibility = (n === 1) ? "hidden" : "visible";
        if (n === totalTabs) {
            document.getElementById("nextBtn").classList.add("d-none");
            document.getElementById("submitBtn").classList.remove("d-none");
        } else {
            document.getElementById("nextBtn").classList.remove("d-none");
            document.getElementById("submitBtn").classList.add("d-none");
        }
        const tabTriggerEl = document.querySelector(`#tab-${n}`);
        const tab = new bootstrap.Tab(tabTriggerEl);
        tab.show();
    }

    function nextPrev(n) {
        currentTab = currentTab + n;
        if (currentTab < 1) currentTab = 1;
        if (currentTab > totalTabs) currentTab = totalTabs;
        showTab(currentTab);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    document.addEventListener('DOMContentLoaded', function() { showTab(currentTab); });
</script>
@endsection