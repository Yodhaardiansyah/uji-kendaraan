<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string|null $nrp
 * @property string $pangkat
 * @property int $dishub_id
 * @property string $email
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $role
 * @property-read \App\Models\Dishub $dishub
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inspection> $inspections
 * @property-read int|null $inspections_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereDishubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereNrp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin wherePangkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUpdatedAt($value)
 */
	class Admin extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string $provinsi
 * @property string $kota
 * @property string|null $kecamatan
 * @property string|null $singkatan
 * @property string $kepala_dinas_nama
 * @property string $kepala_dinas_nip
 * @property string $direktur_nama
 * @property string $direktur_nip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Admin> $admins
 * @property-read int|null $admins_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dishub newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dishub newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dishub query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dishub whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dishub whereDirekturNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dishub whereDirekturNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dishub whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dishub whereKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dishub whereKepalaDinasNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dishub whereKepalaDinasNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dishub whereKota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dishub whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dishub whereProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dishub whereSingkatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dishub whereUpdatedAt($value)
 */
	class Dishub extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $rfid_id
 * @property int $admin_id
 * @property string|null $foto_depan
 * @property string|null $foto_belakang
 * @property string|null $foto_kanan
 * @property string|null $foto_kiri
 * @property bool $rangka
 * @property bool $mesin
 * @property bool $tangki
 * @property bool $pembuangan
 * @property bool $ban
 * @property bool $suspensi
 * @property bool $rem_utama
 * @property bool $lampu
 * @property bool $dashboard
 * @property bool $spion
 * @property bool $spakbor
 * @property bool $bumper
 * @property bool $perlengkapan
 * @property bool $teknis
 * @property bool $darurat
 * @property bool $badan
 * @property bool $converter
 * @property bool $penerus_daya
 * @property bool $kemudi
 * @property bool $rem_parkir
 * @property bool $lampu_manual
 * @property bool $wiper
 * @property bool $kaca
 * @property bool $klakson
 * @property bool $sabuk
 * @property bool $ukuran
 * @property bool $kursi
 * @property float|null $emisi_solar
 * @property float|null $emisi_co
 * @property int|null $emisi_hc
 * @property float|null $rem_utama_total
 * @property float|null $rem_utama_selisih_1
 * @property float|null $rem_utama_selisih_2
 * @property float|null $rem_utama_selisih_3
 * @property float|null $rem_utama_selisih_4
 * @property float|null $rem_parkir_tangan
 * @property float|null $rem_parkir_kaki
 * @property float|null $kincup_roda_depan
 * @property int|null $kebisingan
 * @property int|null $lampu_kanan
 * @property int|null $lampu_kiri
 * @property float|null $deviasi_kanan
 * @property float|null $deviasi_kiri
 * @property float|null $speed_deviasi
 * @property float|null $alur_ban
 * @property string $hasil
 * @property \Illuminate\Support\Carbon $tgl_uji
 * @property \Illuminate\Support\Carbon|null $tgl_berlaku
 * @property string|null $nama_unit
 * @property string $nama_petugas
 * @property string $nrp
 * @property string|null $pangkat_petugas
 * @property string|null $kepala_dinas_nama
 * @property string|null $kepala_dinas_nip
 * @property string|null $kepala_dinas_pangkat
 * @property string|null $direktur_nama
 * @property string|null $direktur_nip
 * @property string|null $direktur_pangkat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $admin
 * @property-read \App\Models\Rfid $rfid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereAlurBan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereBadan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereBan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereBumper($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereConverter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereDarurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereDashboard($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereDeviasiKanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereDeviasiKiri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereDirekturNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereDirekturNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereDirekturPangkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereEmisiCo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereEmisiHc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereEmisiSolar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereFotoBelakang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereFotoDepan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereFotoKanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereFotoKiri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereHasil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereKaca($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereKebisingan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereKemudi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereKepalaDinasNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereKepalaDinasNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereKepalaDinasPangkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereKincupRodaDepan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereKlakson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereKursi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereLampu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereLampuKanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereLampuKiri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereLampuManual($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereMesin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereNamaPetugas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereNamaUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereNrp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection wherePangkatPetugas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection wherePembuangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection wherePenerusDaya($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection wherePerlengkapan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereRangka($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereRemParkir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereRemParkirKaki($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereRemParkirTangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereRemUtama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereRemUtamaSelisih1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereRemUtamaSelisih2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereRemUtamaSelisih3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereRemUtamaSelisih4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereRemUtamaTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereRfidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereSabuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereSpakbor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereSpeedDeviasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereSpion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereSuspensi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereTangki($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereTeknis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereTglBerlaku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereTglUji($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereUkuran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inspection whereWiper($value)
 */
	class Inspection extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $kode_rfid
 * @property int $vehicle_id
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inspection> $inspections
 * @property-read int|null $inspections_count
 * @property-read \App\Models\Vehicle $vehicle
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rfid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rfid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rfid query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rfid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rfid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rfid whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rfid whereKodeRfid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rfid whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rfid whereVehicleId($value)
 */
	class Rfid extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property string|null $alamat
 * @property string|null $nomor_identitas
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vehicle> $vehicles
 * @property-read int|null $vehicles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNomorIdentitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $no_uji
 * @property string|null $no_srut
 * @property \Illuminate\Support\Carbon|null $tgl_srut
 * @property string $no_kendaraan
 * @property string $no_mesin
 * @property string $no_rangka
 * @property string $merk
 * @property string $tipe
 * @property string $jenis
 * @property string $tahun
 * @property string $bahan_bakar
 * @property int|null $cc
 * @property int|null $daya_hp
 * @property int|null $jbb
 * @property int|null $jbkb
 * @property int|null $jbi
 * @property int|null $jbki
 * @property int|null $mst
 * @property int|null $berat_kosong
 * @property string|null $konfigurasi_sumbu
 * @property string|null $ban_depan
 * @property string|null $ban_belakang
 * @property string|null $ban_ring
 * @property int|null $panjang
 * @property int|null $lebar
 * @property int|null $tinggi
 * @property int|null $panjang_bak
 * @property int|null $lebar_bak
 * @property int|null $tinggi_bak
 * @property int|null $jalur_depan
 * @property int|null $jalur_belakang
 * @property int|null $sumbu_1_2
 * @property int|null $sumbu_2_3
 * @property int|null $sumbu_3_4
 * @property int|null $daya_orang
 * @property int|null $daya_barang
 * @property string|null $kelas_jalan
 * @property string|null $wilayah
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rfid> $rfids
 * @property-read int|null $rfids_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereBahanBakar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereBanBelakang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereBanDepan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereBanRing($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereBeratKosong($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereCc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereDayaBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereDayaHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereDayaOrang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereJalurBelakang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereJalurDepan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereJbb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereJbi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereJbkb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereJbki($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereKelasJalan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereKonfigurasiSumbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereLebar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereLebarBak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereMerk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereMst($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereNoKendaraan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereNoMesin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereNoRangka($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereNoSrut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereNoUji($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle wherePanjang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle wherePanjangBak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereSumbu12($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereSumbu23($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereSumbu34($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereTglSrut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereTinggi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereTinggiBak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereWilayah($value)
 */
	class Vehicle extends \Eloquent {}
}

