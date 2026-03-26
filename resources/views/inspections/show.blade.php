@extends('layouts.app')
@section('title', 'Detail Uji - ' . $vehicle->no_kendaraan)

@section('content')
<div class="container-fluid pb-5">
    
    {{-- TOMBOL AKSI --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        @if(Auth::guard('admin')->check())
            <a href="{{ route('inspections.index', $rfid->id) }}" class="btn btn-light border shadow-sm fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Log
            </a>
        @else
            <a href="{{ url('/') }}" class="btn btn-light border shadow-sm fw-bold">
                <i class="bi bi-house-door me-1"></i> Beranda
            </a>
        @endif

        {{-- PERUBAHAN DISINI: Mengarahkan ke route cetak di tab baru --}}
        <a href="{{ route('inspections.print', $inspection->id) }}" target="_blank" class="btn btn-warning shadow-sm fw-bold px-4 text-dark">
            <i class="bi bi-printer-fill me-2"></i> Cetak / Download PDF
        </a>
    </div>

    {{-- PENANDA BUKU (HISTORY NAVIGATOR) --}}
    <div class="card border-0 shadow-sm mb-4 bg-white rounded-3">
        <div class="card-body p-3">
            <h6 class="fw-bold text-muted mb-2 small text-uppercase"><i class="bi bi-bookmarks-fill me-2 text-primary"></i>Riwayat Pengujian Kartu Ini</h6>
            <div class="d-flex gap-2 overflow-auto pb-1" style="white-space: nowrap;">
               @foreach($history as $index => $item)
                    <a href="{{ route('inspections.show', $item->id) }}" 
                    class="btn btn-sm rounded-pill px-3 fw-bold transition-all {{ $item->id == $inspection->id ? 'btn-primary shadow' : 'btn-outline-secondary' }}">
                        Uji Ke-{{ $index + 1 }} <span class="ms-1 fw-normal" style="font-size: 0.75rem;">({{ $item->tgl_uji->format('d/m/Y') }})</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- KONTEN UTAMA UNTUK TAMPILAN WEB --}}
    <div class="card border-0 shadow-sm bg-white" style="font-family: 'Plus Jakarta Sans', sans-serif;">
        <div class="card-body p-3 p-md-4">
            
            <div class="text-center border-bottom border-dark border-2 pb-2 mb-3">
                <h5 class="fw-bolder text-uppercase mb-1 text-dishub">LAPORAN HASIL PENGUJIAN KENDARAAN BERMOTOR</h5>
                <div class="text-muted small">Nomor RFID: <b class="text-dark">{{ $rfid->kode_rfid }}</b></div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-md-5">
                    <div class="border border-secondary-subtle h-100">
                        <div class="bg-dishub text-white fw-bold px-2 py-1 small">Identitas Pemilik Kendaraan</div>
                        <div class="p-2">
                            <table class="table table-sm table-borderless mb-0 data-table">
                                <tr><td width="35%" class="text-muted">1. Nama Pemilik</td><td width="2%">:</td><td class="fw-bold">{{ $user->nama ?? '-' }}</td></tr>
                                <tr><td class="text-muted">2. Alamat Pemilik</td><td>:</td><td>{{ $user->alamat ?? '-' }}</td></tr>
                                <tr><td class="text-muted">3. No. Identitas</td><td>:</td><td>{{ $user->nomor_identitas ?? '-' }}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="border border-secondary-subtle h-100">
                        <div class="bg-dishub text-white fw-bold px-2 py-1 small">Identitas Kendaraan Bermotor</div>
                        <div class="p-2">
                            <table class="table table-sm table-borderless mb-0 data-table">
                                <tr><td width="25%" class="text-muted">1. Nomor Uji</td><td width="2%">:</td><td width="25%" class="fw-bold">{{ $vehicle->no_uji }}</td><td width="23%" class="text-muted">4. No. Kendaraan</td><td width="2%">:</td><td class="fw-bold text-dishub fs-6">{{ $vehicle->no_kendaraan }}</td></tr>
                                <tr><td class="text-muted">2. Nomor SRUT</td><td>:</td><td>{{ $vehicle->no_srut ?? '-' }}</td><td class="text-muted">5. Nomor Mesin</td><td>:</td><td>{{ $vehicle->no_mesin ?? '-' }}</td></tr>
                                <tr><td class="text-muted">3. Tanggal SRUT</td><td>:</td><td>{{ $vehicle->tgl_srut ? \Carbon\Carbon::parse($vehicle->tgl_srut)->format('d/m/Y') : '-' }}</td><td class="text-muted">6. Nomor Rangka</td><td>:</td><td>{{ $vehicle->no_rangka ?? '-' }}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-12">
                    <div class="border border-secondary-subtle h-100">
                        <div class="bg-dishub text-white fw-bold px-2 py-1 small">Spesifikasi Teknis Kendaraan</div>
                        <div class="p-2 row g-0">
                            <div class="col-md-4 pe-2 border-end border-secondary-subtle">
                                <table class="table table-sm table-borderless mb-0 data-table">
                                    <tr><td width="55%" class="text-muted">1. Merk</td><td width="2%">:</td><td class="fw-bold">{{ $vehicle->merk ?? '-' }}</td></tr>
                                    <tr><td class="text-muted">2. Tipe</td><td>:</td><td>{{ $vehicle->tipe ?? '-' }}</td></tr>
                                    <tr><td class="text-muted">3. Jenis</td><td>:</td><td>{{ $vehicle->jenis ?? '-' }}</td></tr>
                                    <tr><td class="text-muted">4. Tahun Pembuatan</td><td>:</td><td>{{ $vehicle->tahun ?? '-' }}</td></tr>
                                    <tr><td class="text-muted">5. Bahan Bakar</td><td>:</td><td>{{ $vehicle->bahan_bakar ?? '-' }}</td></tr>
                                    <tr><td class="text-muted">6. Isi Silinder</td><td>:</td><td>{{ $vehicle->cc ?? '-' }} <span class="text-muted small">cc</span></td></tr>
                                    <tr><td class="text-muted">7. Daya Motor</td><td>:</td><td>{{ $vehicle->daya_hp ?? '-' }} <span class="text-muted small">HP</span></td></tr>
                                    <tr><td class="text-muted">15. Ukuran Ban</td><td>:</td><td>{{ $vehicle->ban_depan ?? '-' }}/{{ $vehicle->ban_belakang ?? '-' }} R{{ $vehicle->ban_ring ?? '-' }}</td></tr>
                                    <tr><td class="text-muted">25. Kelas Jalan</td><td>:</td><td>{{ $vehicle->kelas_jalan ?? '-' }}</td></tr>
                                </table>
                            </div>
                            <div class="col-md-4 px-2 border-end border-secondary-subtle">
                                <table class="table table-sm table-borderless mb-0 data-table">
                                    <tr><td width="55%" class="text-muted">8. JBB</td><td width="2%">:</td><td class="fw-bold">{{ $vehicle->jbb ?? '-' }} <span class="text-muted small">kg</span></td></tr>
                                    <tr><td class="text-muted">9. JBKB</td><td>:</td><td>{{ $vehicle->jbkb ?? '-' }} <span class="text-muted small">kg</span></td></tr>
                                    <tr><td class="text-muted">10. JBI</td><td>:</td><td>{{ $vehicle->jbi ?? '-' }} <span class="text-muted small">kg</span></td></tr>
                                    <tr><td class="text-muted">11. JBKI</td><td>:</td><td>{{ $vehicle->jbki ?? '-' }} <span class="text-muted small">kg</span></td></tr>
                                    <tr><td class="text-muted">12. MST</td><td>:</td><td>{{ $vehicle->mst ?? '-' }} <span class="text-muted small">kg</span></td></tr>
                                    <tr><td class="text-muted">13. Berat Kosong</td><td>:</td><td>{{ $vehicle->berat_kosong ?? '-' }} <span class="text-muted small">kg</span></td></tr>
                                    <tr><td class="text-muted">14. Konfigurasi Sumbu</td><td>:</td><td>{{ $vehicle->konfigurasi_sumbu ?? '-' }}</td></tr>
                                    <tr><td class="text-muted">23. Daya Angkut Orang</td><td>:</td><td>{{ $vehicle->daya_orang ?? '-' }} <span class="text-muted small">pnp</span></td></tr>
                                    <tr><td class="text-muted">24. Daya Angkut Barang</td><td>:</td><td>{{ $vehicle->daya_barang ?? '-' }} <span class="text-muted small">kg</span></td></tr>
                                </table>
                            </div>
                            <div class="col-md-4 ps-2">
                                <table class="table table-sm table-borderless mb-0 data-table">
                                    <tr><td colspan="3" class="fw-bold bg-light py-0">16. Dimensi Utama Kendaraan</td></tr>
                                    <tr><td width="55%" class="text-muted ps-3">P / L / T</td><td width="2%">:</td><td>{{ $vehicle->panjang ?? '-' }} / {{ $vehicle->lebar ?? '-' }} / {{ $vehicle->tinggi ?? '-' }} <span class="text-muted small">mm</span></td></tr>
                                    <tr><td colspan="3" class="fw-bold bg-light mt-1 py-0">17. Dimensi Utama Bak/Tangki</td></tr>
                                    <tr><td class="text-muted ps-3">P / L / T</td><td>:</td><td>{{ $vehicle->panjang_bak ?? '-' }} / {{ $vehicle->lebar_bak ?? '-' }} / {{ $vehicle->tinggi_bak ?? '-' }} <span class="text-muted small">mm</span></td></tr>
                                    <tr><td class="text-muted">18. Jalur Depan</td><td>:</td><td>{{ $vehicle->jalur_depan ?? '-' }} <span class="text-muted small">mm</span></td></tr>
                                    <tr><td class="text-muted">19. Jalur Belakang</td><td>:</td><td>{{ $vehicle->jalur_belakang ?? '-' }} <span class="text-muted small">mm</span></td></tr>
                                    <tr><td class="text-muted">20. Jarak Sumbu I-II</td><td>:</td><td>{{ $vehicle->sumbu_1_2 ?? '-' }} <span class="text-muted small">mm</span></td></tr>
                                    <tr><td class="text-muted">21. Jarak Sumbu II-III</td><td>:</td><td>{{ $vehicle->sumbu_2_3 ?? '-' }} <span class="text-muted small">mm</span></td></tr>
                                    <tr><td class="text-muted">22. Jarak Sumbu III-IV</td><td>:</td><td>{{ $vehicle->sumbu_3_4 ?? '-' }} <span class="text-muted small">mm</span></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-md-4">
                    <div class="border border-secondary-subtle h-100">
                        <div class="bg-dishub text-white fw-bold px-2 py-1 small">Wilayah Asal</div>
                        <div class="p-2 d-flex align-items-center justify-content-center h-75 text-center fw-bold text-uppercase fs-5">
                            {{ $vehicle->wilayah ?? '-' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="border border-secondary-subtle h-100">
                        <div class="bg-dishub text-white fw-bold px-2 py-1 small">Foto Kendaraan</div>
                        <div class="p-2 row g-2 text-center">
                            @foreach(['depan' => '1. Depan', 'belakang' => '2. Belakang', 'kanan' => '3. Kanan', 'kiri' => '4. Kiri'] as $field => $title)
                                <div class="col-3">
                                    <div class="border bg-light p-1" style="height: 100px; display: flex; align-items: center; justify-content: center;">
                                        @php $foto = 'foto_' . $field; @endphp
                                        @if(isset($inspection->$foto) && $inspection->$foto)
                                            <img src="{{ asset('storage/' . $inspection->$foto) }}" class="img-fluid" style="max-height: 90px; object-fit: contain;">
                                        @else
                                            <span class="text-muted small" style="font-size: 0.65rem;">Tidak Ada Foto</span>
                                        @endif
                                    </div>
                                    <div class="fw-bold mt-1 text-muted small" style="font-size: 0.7rem;">{{ $title }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-md-6">
                    <div class="border border-secondary-subtle h-100">
                        <div class="bg-dishub text-white fw-bold px-2 py-1 small">Pemeriksaan Visual</div>
                        <div class="p-2">
                            <div class="row g-1 visual-grid">
                                @php
                                    $visualItems = [
                                        'rangka' => '1. Kondisi Rangka', 'mesin' => '2. Tipe Motor', 'tangki' => '3. Kondisi Tangki',
                                        'pembuangan' => '4. Pipa Pembuangan', 'ban' => '5. Ukuran & Kondisi Ban', 'suspensi' => '6. Sistem Suspensi',
                                        'rem_utama' => '7. Sistem Rem Utama', 'lampu' => '8. Penutup Lampu', 'dashboard' => '9. Panel Dashboard',
                                        'spion' => '10. Kaca Spion', 'spakbor' => '11. Kondisi Spakbor', 'bumper' => '12. Bentuk Bumper',
                                        'perlengkapan' => '13. Perlengkapan', 'teknis' => '14. Rancangan Teknis', 'darurat' => '15. Fasilitas Darurat',
                                        'badan' => '16. Kondisi Badan/Kaca', 'converter' => '17. Kondisi Converter Kit'
                                    ];
                                @endphp
                                @foreach($visualItems as $key => $label)
                                <div class="col-6 border-bottom border-light-subtle pb-1">
                                    <div class="d-flex justify-content-between pe-2 align-items-center h-100">
                                        <span class="text-muted text-truncate" style="max-width: 80%;" title="{{ $label }}">{{ $label }}</span>
                                        <span class="badge {{ isset($inspection->$key) && $inspection->$key ? 'bg-success' : 'bg-danger' }}">
                                            {{ isset($inspection->$key) && $inspection->$key ? 'BAIK' : 'GAGAL' }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border border-secondary-subtle h-100">
                        <div class="bg-dishub text-white fw-bold px-2 py-1 small">Pemeriksaan Manual</div>
                        <div class="p-2">
                            <div class="row g-1 visual-grid">
                                @php
                                    $manualItems = [
                                        'penerus_daya' => '1. Kondisi Penerus Daya', 'kemudi' => '2. Sudut Bebas Kemudi', 'rem_parkir' => '3. Kondisi Rem Parkir',
                                        'lampu_manual' => '4. Fungsi Lampu & Pemantul', 'wiper' => '5. Fungsi Penghapus Kaca', 'kaca' => '6. Tingkat Kegelapan Kaca',
                                        'klakson' => '7. Fungsi Klakson', 'sabuk' => '8. Sabuk Keselamatan', 'ukuran' => '9. Ukuran Kendaraan',
                                        'kursi' => '10. Akses Darurat & Tempat Duduk'
                                    ];
                                @endphp
                                @foreach($manualItems as $key => $label)
                                <div class="col-12 border-bottom border-light-subtle pb-1">
                                    <div class="d-flex justify-content-between pe-2 align-items-center h-100">
                                        <span class="text-muted">{{ $label }}</span>
                                        <span class="badge {{ isset($inspection->$key) && $inspection->$key ? 'bg-success' : 'bg-danger' }}">
                                            {{ isset($inspection->$key) && $inspection->$key ? 'BAIK' : 'GAGAL' }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-2 mb-2 align-items-stretch">
                <div class="col-md-3">
                    <div class="border border-secondary-subtle h-100">
                        <div class="bg-dishub text-white fw-bold px-2 py-1 small">Pemeriksaan Alat Uji</div>
                        <div class="p-2">
                            <table class="table table-sm table-striped table-bordered mb-0 data-table">
                                <tbody>
                                    <tr><td colspan="2" class="fw-bold bg-light py-0">1. Emisi</td></tr>
                                    <tr><td width="55%" class="text-muted ps-2">Solar</td><td class="text-center fw-bold">{{ $inspection->emisi_solar ?? '-' }} %</td></tr>
                                    <tr><td class="text-muted ps-2">Bensin CO</td><td class="text-center fw-bold">{{ $inspection->emisi_co ?? '-' }} %</td></tr>
                                    <tr><td class="text-muted ps-2">Bensin HC</td><td class="text-center fw-bold">{{ $inspection->emisi_hc ?? '-' }} ppm</td></tr>
                                    <tr><td colspan="2" class="fw-bold bg-light mt-1 py-0">2. Rem Utama</td></tr>
                                    <tr><td class="text-muted ps-2">Total Gaya</td><td class="text-center fw-bold">{{ $inspection->rem_utama_total ?? '-' }} %</td></tr>
                                    <tr><td class="text-muted ps-2">Selisih I/II</td><td class="text-center fw-bold">{{ $inspection->rem_utama_selisih_1 ?? '-' }} / {{ $inspection->rem_utama_selisih_2 ?? '-' }} %</td></tr>
                                    <tr><td class="text-muted ps-2">Selisih III/IV</td><td class="text-center fw-bold">{{ $inspection->rem_utama_selisih_3 ?? '-' }} / {{ $inspection->rem_utama_selisih_4 ?? '-' }} %</td></tr>
                                    <tr><td colspan="2" class="fw-bold bg-light mt-1 py-0">3. Rem Parkir & 4. Kincup</td></tr>
                                    <tr><td class="text-muted ps-2">Gaya Parkir</td><td class="text-center fw-bold">{{ $inspection->rem_parkir_tangan ?? '-' }} / {{ $inspection->rem_parkir_kaki ?? '-' }} %</td></tr>
                                    <tr><td class="text-muted ps-2">Kincup Roda</td><td class="text-center fw-bold">{{ $inspection->kincup_roda_depan ?? '-' }} mm/m</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 d-flex flex-column gap-2">
                    <div class="border border-secondary-subtle">
                        <div class="bg-dishub text-white fw-bold px-2 py-1 small">Kebisingan</div>
                        <div class="p-2 py-2 data-table text-center">
                            <span class="fw-bold">{{ $inspection->kebisingan ?? '-' }} dbA</span>
                        </div>
                    </div>
                    <div class="border border-secondary-subtle">
                        <div class="bg-dishub text-white fw-bold px-2 py-1 small">Kecepatan</div>
                        <div class="p-2 py-2 data-table text-center">
                            <span class="fw-bold">{{ $inspection->speed_deviasi ?? '-' }} km/jam</span>
                        </div>
                    </div>
                    <div class="border border-secondary-subtle flex-grow-1">
                        <div class="bg-dishub text-white fw-bold px-2 py-1 small">Kedalaman Alur</div>
                        <div class="p-2 py-2 data-table text-center">
                            <span class="fw-bold">{{ $inspection->alur_ban ?? '-' }} mm</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="border border-secondary-subtle h-100">
                        <div class="bg-dishub text-white fw-bold px-2 py-1 small">Lampu Utama</div>
                        <div class="p-2">
                            <table class="table table-sm table-striped table-bordered mb-0 data-table">
                                <tbody>
                                    <tr><td colspan="2" class="fw-bold bg-light py-0">1. Kekuatan Pancar</td></tr>
                                    <tr><td width="50%" class="text-muted ps-2">Kanan</td><td class="text-center fw-bold">{{ $inspection->lampu_kanan ?? '-' }} cd</td></tr>
                                    <tr><td class="text-muted ps-2">Kiri</td><td class="text-center fw-bold">{{ $inspection->lampu_kiri ?? '-' }} cd</td></tr>
                                    <tr><td colspan="2" class="fw-bold bg-light mt-1 py-0">2. Penyimpangan Lampu</td></tr>
                                    <tr><td class="text-muted ps-2">Kanan</td><td class="text-center fw-bold">{{ $inspection->deviasi_kanan ?? '-' }} °</td></tr>
                                    <tr><td class="text-muted ps-2">Kiri</td><td class="text-center fw-bold">{{ $inspection->deviasi_kiri ?? '-' }} °</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="border border-secondary-subtle h-100 d-flex flex-column">
                        <div class="bg-dishub text-white fw-bold px-2 py-1 small">Keterangan Hasil Uji</div>
                        <div class="p-3 d-flex flex-column justify-content-center align-items-center flex-grow-1 text-center bg-light">
                            <h4 class="fw-bolder mb-3 {{ $inspection->hasil == 'Lolos Uji Berkala' ? 'text-success' : 'text-danger' }}">
                                {{ strtoupper($inspection->hasil ?? 'BELUM DIUJI') }}
                            </h4>
                            <div class="w-100 border-top border-dark pt-3 mt-1 px-2 d-flex flex-column text-muted fw-bold gap-2">
                                <div class="d-flex justify-content-between"><span>Tanggal Uji:</span> <span class="text-dark">{{ isset($inspection->tgl_uji) ? \Carbon\Carbon::parse($inspection->tgl_uji)->format('d/m/Y') : '-' }}</span></div>
                                <div class="d-flex justify-content-between"><span>Berlaku S/D:</span> <span class="text-dark">{{ isset($inspection->tgl_berlaku) ? \Carbon\Carbon::parse($inspection->tgl_berlaku)->format('d/m/Y') : '-' }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-2 mt-2">
                <div class="col-md-3">
                    <div class="border border-secondary-subtle h-100 text-center p-2">
                        <div class="fw-bold mb-2 text-muted small text-start">Unit Pelaksana</div>
                        <div class="mt-2 text-dishub fw-bolder" style="font-size: 0.8rem;">
                            {{ $inspection->nama_unit ?? 'UPTD PENGUJIAN KENDARAAN' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border border-secondary-subtle h-100 text-center p-2">
                        <div class="fw-bold mb-2 text-muted small text-start">Petugas Penguji</div>
                        <div class="mt-2">
                            <p class="mb-0 fw-bold">{{ $inspection->nama_petugas ?? '-' }}</p>
                            <p class="mb-0 small text-muted">NRP. {{ $inspection->nrp ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border border-secondary-subtle h-100 text-center p-2">
                        <div class="fw-bold mb-2 text-muted small text-start">Kepala Dinas</div>
                        <div class="mt-2">
                            <p class="mb-0 fw-bold">{{ $inspection->kepala_dinas_nama ?? '-' }}</p>
                            <p class="mb-0 small text-muted">NIP. {{ $inspection->kepala_dinas_nip ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border border-secondary-subtle h-100 text-center p-2">
                        <div class="fw-bold mb-2 text-muted small text-start">Direktur Pengujian</div>
                        <div class="mt-2">
                            <p class="mb-0 fw-bold">{{ $inspection->direktur_nama ?? '-' }}</p>
                            <p class="mb-0 small text-muted">NIP. {{ $inspection->direktur_nip ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* UTILITIES WARNA DISHUB UNTUK WEB */
    .bg-dishub { background-color: #002d72 !important; }
    .text-dishub { color: #002d72 !important; }

    /* PENGATURAN TABEL & TEKS TAMPILAN WEB */
    .data-table { font-size: 0.75rem; }
    .data-table td { padding: 0.2rem 0.3rem !important; vertical-align: middle; }
    .visual-grid { font-size: 0.70rem; }
    .visual-grid .badge { font-size: 0.6rem; padding: 0.3em 0.5em; }
</style>
@endsection