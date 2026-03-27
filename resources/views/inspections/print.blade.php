<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Sertifikat - {{ $vehicle->no_kendaraan }}</title>
    
    {{-- ================= PRINT-SPECIFIC CSS ================= --}}
    <style>
        /* 1. PAPER & BROWSER SETUP 
          Configures the browser's print dialog to default to F4 paper size (215mm x 330mm).
          'margin: 0' removes default browser headers/footers (like URL or Date) when printing.
        */
        @page { size: 215mm 330mm; margin: 0; }
        
        body { 
            background: white; 
            margin: 0; 
            /* Inner padding to act as the document's actual margin */
            padding: 8mm 10mm; 
            font-size: 8.5pt; 
            /* Using a standard system font ensures consistency across different computers/printers */
            font-family: "Times New Roman", Times, serif;
            color: black;
            line-height: 1.1; 
            box-sizing: border-box;
        }

        /* 2. TABLE LAYOUT STYLES 
          Since Bootstrap is not used, traditional HTML tables are utilized 
          to create the rigid grid structure required for formal certificates.
        */
        .print-layout-container { width: 100%; max-width: 100%; position: relative; }
        
        /* Main structural table */
        .print-table { border-collapse: collapse; width: 100%; margin-bottom: -1px; table-layout: fixed; }
        .print-table > tbody > tr > td { border: 1px solid black; vertical-align: top; padding: 0; }
        
        /* Inner tables for specific data points (Identity, Specs, etc.) */
        .print-inner-table { border-collapse: collapse; width: 100%; }
        .print-inner-table td { padding: 1px 3px; vertical-align: top; }

        /* 3. TYPOGRAPHY CLASSES 
          Pre-defined styles for headers, subheaders, and section dividers.
        */
        .header-title { font-size: 13px; font-weight: bold; margin: 0; padding: 0; }
        .header-subtitle { font-size: 9px; margin: 0; padding: 0; font-weight: normal; }
        .header-dept { font-size: 11px; margin: 0; padding: 0; font-weight: bold; }
        
        .section-title { 
            padding: 2px 4px; 
            font-size: 10px;
            background-color: transparent; 
            border-bottom: 1px solid #000;
            font-weight: bold;
        }

        /* 4. PRINT OPTIMIZATION HACKS 
          Forces browsers (especially Chrome/Edge) to print background colors and images 
          (like the warning box color or the KAN logo).
        */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            box-sizing: border-box;
        }
        
        /* 5. MEDIA PRINT QUERY 
          Hides UI elements (like the "Print Again" button) when the document is actively being printed.
        */
        @media print {
            .btn-print { display: none !important; }
        }
    </style>
</head>
<body>
    
    <div class="print-layout-container">
        
        {{-- ================= HEADER SECTION ================= --}}
        {{-- Contains Logos, Department Names, and the QR Code --}}
        <div style="position: relative; text-align: center; margin-bottom: 5px; width: 100%;">
            
            {{-- Official Dishub Logo --}}
            <img src="{{ asset('images/logo-dishub.png') }}" style="height: 45px; margin-bottom: 2px;" alt="Logo Dishub">
            
            <p class="header-title">KARTU UJI BERKALA KENDARAAN BERMOTOR</p>
            <p class="header-subtitle">VEHICLE PERIODICAL INSPECTION CARD</p>
            
            <p class="header-dept" style="margin-top: 2px;">a.n. DIREKTUR JENDERAL PERHUBUNGAN DARAT</p>
            <p class="header-dept">DIREKTUR SARANA TRANSPORTASI JALAN</p>
            
            {{-- English Translation of Department --}}
            <p class="header-subtitle" style="font-style: italic;">DIRECTOR GENERAL OF LAND TRANSPORTATION</p>
            <p class="header-subtitle" style="font-style: italic;">DIRECTOR OF ROAD TRANSPORT FACILITIES</p>
            
            {{-- Director's Name & NIP (Fallback data provided via ?? operator) --}}
            <div style="margin-top: 5px;">
                <p style="margin:0; font-weight:bold; font-size:11px; text-decoration: underline;">{{ $inspection->direktur_nama ?? 'AMIRULLOH' }}</p>
                <p style="margin:0; font-size:9px;">Pembina Utama Muda - IV/c</p>
                <p style="margin:0; font-size:9px;">NIP {{ $inspection->direktur_nip ?? '19740730 199703 1 001' }}</p>
            </div>
            
            {{-- 
              QR CODE GENERATION 
              Utilizes simple-qrcode package. Positioned absolute to lock it to the bottom right of the header.
              The URL points to the public verification route using the RFID code.
            --}}
            <div style="position: absolute; bottom: 0px; right: 0;">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(70)->margin(0)->generate("https://ekirdishub.arunovasi.com/rfid/check/" . $rfid->kode_rfid) !!}
            </div>
        </div>

        {{-- ================= ROW 1: IDENTITY ================= --}}
        {{-- Splits the row into 50% / 50% for Owner and Vehicle IDs --}}
        <table class="print-table">
            <tr>
                {{-- Left Column: Owner Identity --}}
                <td style="width: 50%;">
                    <div class="section-title">
                        IDENTITAS PEMILIK KENDARAAN BERMOTOR<br>
                        <span style="font-weight:normal; font-size:7px; font-style:italic;">VEHICLE OWNER IDENTIFICATION</span>
                    </div>
                    <table class="print-inner-table" style="margin: 0;">
                        <tr>
                            <td style="width: 35%; padding-top:2px;">Nama pemilik<br><span style="font-size:7px; font-style:italic;">Owner's name</span></td>
                            <td style="width: 2%; padding-top:2px;">:</td>
                            <td style="font-weight:bold; padding-top:2px;">{{ $user->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="padding-bottom:2px;">Alamat pemilik<br><span style="font-size:7px; font-style:italic;">Owner's address</span></td>
                            <td style="padding-bottom:2px;">:</td>
                            <td style="font-weight:bold; padding-bottom:2px;">{{ $user->alamat ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
                
                {{-- Right Column: Vehicle Identity --}}
                <td style="width: 50%;">
                    <div class="section-title">
                        IDENTITAS KENDARAAN BERMOTOR<br>
                        <span style="font-weight:normal; font-size:7px; font-style:italic;">VEHICLE IDENTIFICATION</span>
                    </div>
                    <table class="print-inner-table" style="margin: 0;">
                        <tr>
                            <td style="width: 45%; padding-top:2px;">Nomor dan tanggal Sertifikat<br>registrasi uji tipe<br><span style="font-size:7px; font-style:italic;">Number and date of vehicle type approval...</span></td>
                            <td style="width: 2%; padding-top:2px;">:</td>
                            <td style="font-weight:bold; padding-top:2px;">{{ $vehicle->no_srut ?? '-' }}<br>{{ $vehicle->tgl_srut ? \Carbon\Carbon::parse($vehicle->tgl_srut)->format('d M Y') : '' }}</td>
                        </tr>
                        <tr>
                            <td>Nomor registrasi kendaraan<br><span style="font-size:7px; font-style:italic;">Vehicle registration number</span></td>
                            <td>:</td>
                            <td style="font-weight: bold; font-size:11px;">{{ $vehicle->no_kendaraan }}</td>
                        </tr>
                        <tr>
                            <td>Nomor rangka kendaraan<br><span style="font-size:7px; font-style:italic;">Chassis number</span></td>
                            <td>:</td>
                            <td style="font-weight:bold;">{{ $vehicle->no_rangka ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Nomor motor penggerak<br><span style="font-size:7px; font-style:italic;">Engine number</span></td>
                            <td>:</td>
                            <td style="font-weight:bold;">{{ $vehicle->no_mesin ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="padding-bottom:2px;">Nomor uji kendaraan<br><span style="font-size:7px; font-style:italic;">Vehicle inspection number</span></td>
                            <td style="padding-bottom:2px;">:</td>
                            <td style="font-weight:bold; padding-bottom:2px;">{{ $vehicle->no_uji }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- ================= ROW 2: VEHICLE PHOTOS ================= --}}
        <table class="print-table">
            <tr>
                <td style="text-align: center; font-weight: bold; padding: 2px; font-size: 9px; border-bottom: 1px solid black;">
                    Foto Berwarna kendaraan :
                </td>
            </tr>
            <tr>
                <td style="padding: 1px;">
                    <table class="print-inner-table" style="text-align: center;">
                        <tr>
                            {{-- Each photo takes up 25% of the row --}}
                            <td style="width: 25%;">
                                <div style="font-size: 8px; font-weight: bold;">Foto Depan<br><span style="font-size:6px; font-weight:normal; font-style:italic;">Image Front</span></div>
                                <div style="height: 55px; display: flex; align-items: center; justify-content: center; margin-top: 1px;">
                                    @if(isset($inspection->foto_depan) && $inspection->foto_depan) <img src="{{ asset('storage/' . $inspection->foto_depan) }}" style="max-height: 50px;"> @endif
                                </div>
                            </td>
                            <td style="width: 25%;">
                                <div style="font-size: 8px; font-weight: bold;">Foto Belakang<br><span style="font-size:6px; font-weight:normal; font-style:italic;">Image Rear</span></div>
                                <div style="height: 55px; display: flex; align-items: center; justify-content: center; margin-top: 1px;">
                                    @if(isset($inspection->foto_belakang) && $inspection->foto_belakang) <img src="{{ asset('storage/' . $inspection->foto_belakang) }}" style="max-height: 50px;"> @endif
                                </div>
                            </td>
                            <td style="width: 25%;">
                                <div style="font-size: 8px; font-weight: bold;">Foto Kanan<br><span style="font-size:6px; font-weight:normal; font-style:italic;">Image Right</span></div>
                                <div style="height: 55px; display: flex; align-items: center; justify-content: center; margin-top: 1px;">
                                    @if(isset($inspection->foto_kanan) && $inspection->foto_kanan) <img src="{{ asset('storage/' . $inspection->foto_kanan) }}" style="max-height: 50px;"> @endif
                                </div>
                            </td>
                            <td style="width: 25%;">
                                <div style="font-size: 8px; font-weight: bold;">Foto Kiri<br><span style="font-size:6px; font-weight:normal; font-style:italic;">Image Left</span></div>
                                <div style="height: 55px; display: flex; align-items: center; justify-content: center; margin-top: 1px;">
                                    @if(isset($inspection->foto_kiri) && $inspection->foto_kiri) <img src="{{ asset('storage/' . $inspection->foto_kiri) }}" style="max-height: 50px;"> @endif
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- ================= ROW 3: SPECS & TEST RESULTS ================= --}}
        <table class="print-table">
            <tr>
                
                {{-- Left Column: Technical Specifications --}}
                <td style="width: 50%;">
                    <div class="section-title">
                        SPESIFIKASI TEKNIS KENDARAAN<br>
                        <span style="font-weight:normal; font-size:7px; font-style:italic;">VEHICLE TECHNICAL SPECIFICATIONS</span>
                    </div>
                    <table class="print-inner-table" style="margin: 0; font-size: 8px;">
                        <tr>
                            <td style="width: 45%; padding-top:2px;">Jenis<br><span style="font-size:6px; font-style:italic;">Purpose of vehicle</span></td>
                            <td style="width: 2%; padding-top:2px;">:</td>
                            <td style="padding-top:2px;"><b>{{ $vehicle->jenis ?? '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>Merek /tipe<br><span style="font-size:6px; font-style:italic;">Brand type</span></td>
                            <td>:</td>
                            <td><b>{{ $vehicle->merk ?? '-' }} / {{ $vehicle->tipe ?? '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>Tahun pembuatan/perakitan<br><span style="font-size:6px; font-style:italic;">Year manufactured/assembled</span></td>
                            <td>:</td>
                            <td><b>{{ $vehicle->tahun ?? '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>Bahan bakar/sumber energi<br><span style="font-size:6px; font-style:italic;">Fuel/energy source</span></td>
                            <td>:</td>
                            <td><b>{{ $vehicle->bahan_bakar ?? '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>Isi silinder<br><span style="font-size:6px; font-style:italic;">Engine capacity</span></td>
                            <td>:</td>
                            <td><b>{{ $vehicle->cc ?? '-' }} cc</b></td>
                        </tr>
                        <tr>
                            <td>Daya motor<br><span style="font-size:6px; font-style:italic;">Engine power</span></td>
                            <td>:</td>
                            <td><b>{{ $vehicle->daya_hp ?? '-' }} KW/PS/HP</b></td>
                        </tr>
                        <tr>
                            <td>Ukuran ban<br><span style="font-size:6px; font-style:italic;">Tyre size</span></td>
                            <td>:</td>
                            <td><b>{{ $vehicle->ban_depan ?? '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>Konfigurasi sumbu<br><span style="font-size:6px; font-style:italic;">Axle configuration</span></td>
                            <td>:</td>
                            <td><b>{{ $vehicle->konfigurasi_sumbu ?? '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>Berat kosong kendaraan<br><span style="font-size:6px; font-style:italic;">Curb weight</span></td>
                            <td>:</td>
                            <td><b>{{ $vehicle->berat_kosong ?? '-' }} kg</b></td>
                        </tr>
                        
                        {{-- Sub-section: Dimensions --}}
                        <tr><td colspan="3" style="font-weight: bold; padding-top: 2px;">Dimensi utama kendaraan bermotor (Vehicle main dimension)</td></tr>
                        <tr>
                            <td colspan="3" style="padding:0;">
                                <table class="print-inner-table" style="font-size: 8px;">
                                    <tr>
                                        <td style="width: 20%">Panjang<br><span style="font-size:6px; font-style:italic;">Length</span></td><td style="width: 2%">:</td><td style="width: 28%"><b>{{ $vehicle->panjang ?? '-' }} mm</b></td>
                                        <td style="width: 20%">Julur depan<br><span style="font-size:6px; font-style:italic;">Front overhang</span></td><td style="width: 2%">:</td><td><b>{{ $vehicle->jalur_depan ?? '-' }} mm</b></td>
                                    </tr>
                                    <tr>
                                        <td>Lebar<br><span style="font-size:6px; font-style:italic;">Width</span></td><td>:</td><td><b>{{ $vehicle->lebar ?? '-' }} mm</b></td>
                                        <td>Julur belakang<br><span style="font-size:6px; font-style:italic;">Rear overhang</span></td><td>:</td><td><b>{{ $vehicle->jalur_belakang ?? '-' }} mm</b></td>
                                    </tr>
                                    <tr>
                                        <td>Tinggi<br><span style="font-size:6px; font-style:italic;">Height</span></td><td>:</td><td><b>{{ $vehicle->tinggi ?? '-' }} mm</b></td>
                                        <td></td><td></td><td></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        
                        {{-- Sub-section: Wheel Base --}}
                        <tr><td colspan="3" style="font-weight: bold; padding-top: 2px;">Jarak sumbu <span style="font-weight:normal; font-style:italic; font-size:7px;">Wheel base</span></td></tr>
                        <tr>
                            <td colspan="3" style="padding:0;">
                                <table class="print-inner-table" style="font-size:8px;">
                                    <tr><td style="width:40%">Sumbu I-II</td><td style="width:2%">:</td><td><b>{{ $vehicle->sumbu_1_2 ?? '-' }} mm</b></td></tr>
                                    <tr><td>Sumbu II-III</td><td>:</td><td><b>{{ $vehicle->sumbu_2_3 ?? '-' }} mm</b></td></tr>
                                    <tr><td>Sumbu III-IV</td><td>:</td><td><b>{{ $vehicle->sumbu_3_4 ?? '-' }} mm</b></td></tr>
                                </table>
                            </td>
                        </tr>
                        
                        {{-- Sub-section: Tub/Tank Dimensions and Capacities --}}
                        <tr>
                            <td>Dimensi bak muatan / tangki</td>
                            <td>:</td>
                            <td><b>( {{ $vehicle->panjang_bak ?? '-' }} x {{ $vehicle->lebar_bak ?? '-' }} x {{ $vehicle->tinggi_bak ?? '-' }} ) mm</b></td>
                        </tr>
                        <tr><td colspan="3" style="font-style:italic; font-size:6px; padding-top:0;">Dimension of cargo tub (length x width x height)</td></tr>
                        <tr>
                            <td colspan="3" style="padding:0;">
                                <table class="print-inner-table" style="font-size: 8px; margin-top: 1px;">
                                    <tr>
                                        <td style="width: 20%"><b>JBB/JBKB</b><br><span style="font-size:6px; font-style:italic; font-weight:normal;">GVW/GVCW</span></td>
                                        <td style="width: 2%">:</td>
                                        <td style="width: 28%"><b>{{ $vehicle->jbb ?? '-' }} kg</b></td>
                                        
                                        <td style="width: 20%"><b>JBI/JBKI</b><br><span style="font-size:6px; font-style:italic; font-weight:normal;">PVW/PVCW</span></td>
                                        <td style="width: 2%">:</td>
                                        <td><b>{{ $vehicle->jbi ?? '-' }} kg</b></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>Daya angkut (orang/kg)<br><span style="font-size:6px; font-style:italic;">Payload (person(s)/kg(s))</span></td>
                            <td>:</td>
                            <td><b>{{ $vehicle->daya_orang ?? '-' }} / {{ $vehicle->daya_barang ?? '-' }} kg</b></td>
                        </tr>
                        <tr>
                            <td style="padding-bottom:2px;">Kelas jalan terendah<br><span style="font-size:6px; font-style:italic;">Lowest road class permitted</span></td>
                            <td style="padding-bottom:2px;">:</td>
                            <td style="padding-bottom:2px;"><b>{{ $vehicle->kelas_jalan ?? '-' }}</b></td>
                        </tr>
                    </table>
                </td>
                
                {{-- Right Column: Test Results & Final Authorization --}}
                <td style="width: 50%;">
                    
                    {{-- Nested table for the Results Matrix --}}
                    <table class="print-inner-table" style="text-align: center; border-bottom: 1px solid #000;">
                        <tr>
                            <td style="width: 25%; font-weight: bold; font-size: 9px; border-right: 1px solid #000; padding: 2px;">Item Uji<br><span style="font-size:6px; font-weight:normal; font-style:italic;">Testing</span></td>
                            <td style="width: 50%; font-weight: bold; font-size: 9px; border-right: 1px solid #000; padding: 2px;">Ambang batas<br><span style="font-size:6px; font-weight:normal; font-style:italic;">Threshold</span></td>
                            <td style="width: 25%; font-weight: bold; font-size: 9px; padding: 2px;">Hasil Uji<br><span style="font-size:6px; font-weight:normal; font-style:italic;">Test result</span></td>
                        </tr>
                    </table>
                    
                    <table class="print-inner-table" style="font-size: 8px;">
                        
                        {{-- Brakes Result --}}
                        <tr>
                            <td style="width: 25%; border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 2px; font-weight: bold;">
                                Rem Utama<br><span style="font-size:6px; font-weight:normal; font-style:italic;">Brake</span>
                            </td>
                            <td style="width: 50%; border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 2px;">
                                Total gaya pengereman >= 50% X<br>total berat sumbu (kg)<br><br>
                                Selisih gaya pengereman roda kiri<br>dan roda kanan dalam satu sumbu<br>maksimum 8%
                            </td>
                            <td style="width: 25%; border-bottom: 1px solid #000; padding: 2px; font-weight: bold;">
                                : {{ $inspection->rem_utama_total ?? '-' }} kg<br><br>
                                <table style="width:100%; font-size:8px; font-weight:bold; border-collapse:collapse;">
                                    <tr><td style="width:20%">I</td><td>{{ $inspection->rem_utama_selisih_1 ?? '-' }} %</td></tr>
                                    <tr><td>II</td><td>{{ $inspection->rem_utama_selisih_2 ?? '-' }} %</td></tr>
                                    <tr><td>III</td><td>0 %</td></tr>
                                    <tr><td>IV</td><td>0 %</td></tr>
                                </table>
                            </td>
                        </tr>
                        
                        {{-- Headlamp Result --}}
                        <tr>
                            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 2px; font-weight: bold;">
                                Lampu Utama<br><span style="font-size:6px; font-weight:normal; font-style:italic;">Head lamp</span>
                            </td>
                            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 2px;">
                                Kekuatan pancar lampu utama<br>kanan 12000 cd (lampu jauh)<br><br>
                                Kekuatan pancar lampu utama kiri<br>12000 cd (lampu jauh)<br><br>
                                Penyimpangan ke kanan 0° 34'<br>(lampu jauh)<br><br>
                                Penyimpangan ke kiri 1° 09'<br>(lampu jauh)
                            </td>
                            <td style="border-bottom: 1px solid #000; padding: 2px; font-weight: bold;">
                                <br>: {{ $inspection->lampu_kanan ?? '-' }} cd<br><br>
                                : {{ $inspection->lampu_kiri ?? '-' }} cd<br><br>
                                : {{ $inspection->deviasi_kanan ?? '-' }}<br><br>
                                : {{ $inspection->deviasi_kiri ?? '-' }}
                            </td>
                        </tr>
                        
                        {{-- Emission Result --}}
                        <tr>
                            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 2px; font-weight: bold;">
                                Emisi<br><span style="font-size:6px; font-weight:normal; font-style:italic;">Emission</span>
                            </td>
                            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 2px;">
                                Bahan bakar solar<br>tahun pembuatan <= 2010<br><br>
                                Opasitas 33% HSU
                            </td>
                            <td style="border-bottom: 1px solid #000; padding: 2px; font-weight: bold;">
                                <br><br>
                                : {{ $inspection->emisi_solar ?? '-' }} %
                            </td>
                        </tr>
                    </table>

                    {{-- Final Result & Inspector Signature Block --}}
                    <table class="print-inner-table" style="font-size: 8px; margin: 2px;">
                        <tr>
                            <td style="width: 40%; font-weight: bold;">Keterangan<br><span style="font-size:6px; font-weight:normal; font-style:italic;">Inspection result</span></td>
                            <td style="width: 2%">:</td>
                            <td style="font-weight: bold; font-size: 10px;">{{ strtoupper($inspection->hasil ?? 'BELUM DIUJI') }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Masa berlaku uji<br>berkala<br><span style="font-size:6px; font-weight:normal; font-style:italic;">Periodical inspection expiry<br>date</span></td>
                            <td>:</td>
                            <td style="font-weight: bold;">{{ isset($inspection->tgl_berlaku) ? \Carbon\Carbon::parse($inspection->tgl_berlaku)->format('d M Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Nama petugas<br>penguji<br><span style="font-size:6px; font-weight:normal; font-style:italic;">Name of inspector grade</span></td>
                            <td>:</td>
                            
                            {{-- Attempts to get the inspector's name from a relationship (admin), falling back to a static string if needed --}}
                            <td><b>{{ $inspection->admin->nama ?? ($inspection->nama_petugas ?? '-') }}</b></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Tanda tangan<br>petugas penguji<br><span style="font-size:6px; font-weight:normal; font-style:italic;">Inspector authorization</span></td>
                            <td>:</td>
                            <td style="text-align: center; padding-top: 15px;">
                                <b><u>{{ $inspection->admin->nama ?? ($inspection->nama_petugas ?? '-') }}</u></b><br>
                                
                                {{ $inspection->admin->pangkat ?? 'Penguji Tingkat Lima' }}<br>
                                <span style="font-size:7px;">NRP {{ $inspection->admin->nrp ?? ($inspection->nrp ?? '-') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="font-weight: bold; padding-top: 5px;">
                                Nama unit pelaksana uji berkala kendaraan bermotor<br>
                                <span style="font-size:6px; font-weight:normal; font-style:italic;">Name of vehicle periodical inspection agency</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-transform: uppercase; font-size: 7px;">
                                {{ $inspection->nama_unit ?? 'UNIT PELAKSANA TEKNIS DAERAH PENGUJIAN DINAS PERHUBUNGAN PROVINSI DKI JAKARTA' }}
                            </td>
                        </tr>
                        
                        {{-- Head of Department Signature --}}
                        <tr>
                            <td colspan="3" style="text-align: right; padding-top: 15px; padding-right: 15px;">
                                <p style="margin: 0;">KEPALA UP / KEPALA DINAS</p>
                                <div style="height: 35px;"></div> {{-- Spacer for physical signature --}}
                                <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $inspection->kepala_dinas_nama ?? '-' }}</p>
                                <p style="margin: 0; font-size:7px;">Pembina Tingkat I - IV/b</p>
                                <p style="margin: 0; font-size:7px;">NIP {{ $inspection->kepala_dinas_nip ?? '-' }}</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        {{-- ================= ROW 4: FOOTER (LOGO & WARNING) ================= --}}
        <table width="100%" style="margin-top: 5px; border:none;">
            <tr>
                {{-- KAN Accreditation Logo --}}
                <td width="30%" align="center" valign="middle" style="border:none; padding: 0;">
                    <img src="{{ asset('images/kan-logo-rls.jpeg') }}" style="height: 35px; mix-blend-mode: multiply;" alt="Logo KAN">
                </td>
                
                {{-- Warning/Advisory Box --}}
                <td width="70%" valign="top" style="border:none; padding: 0;">
                    <div style="padding: 4px; border: 1px solid #d99a9a; background-color: #fbecec;">
                        <table width="100%" style="border:none; margin: 0;">
                            <tr>
                                <td width="25%" align="center" style="color: #b52b2b; font-weight: bold; font-size: 11px; border:none; padding: 0;">
                                    HIMBAUAN
                                </td>
                                <td width="75%" style="color: #b52b2b; font-size: 9px; padding-left: 8px; border:none; border-left: 1px solid #d99a9a;">
                                    Agar melakukan Pemeriksaan dan Perawatan kondisi<br>
                                    Kendaraan secara berkala serta mengganti<br>
                                    komponen yang sudah dalam keadaan tidak<br>
                                    berfungsi/rusak/aus/bocor.
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
        
        {{-- ================= MANUAL PRINT BUTTON ================= --}}
        {{-- 
          Hidden automatically during actual printing via CSS @media print.
          Provides a fallback if the auto-print script fails or the user cancels and wants to try again.
        --}}
        <div style="text-align: center; margin-top: 15px;" class="btn-print">
            <button onclick="window.print()" style="padding: 8px 16px; font-weight: bold; cursor: pointer; background-color:#ffc107; border:none; border-radius:5px;">Print / Simpan PDF</button>
        </div>

    </div>

    {{-- ================= AUTO-PRINT SCRIPT ================= --}}
    <script>
        // Executes when the entire page (including images) has fully loaded.
        window.onload = function() {
            // A slight 600ms delay ensures rendering is complete before triggering the browser's print dialog.
            setTimeout(function() {
                window.print();
            }, 600);
        }
    </script>
</body>
</html>