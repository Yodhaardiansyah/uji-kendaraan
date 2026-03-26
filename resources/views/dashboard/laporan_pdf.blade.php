<h2>Laporan Uji Kendaraan</h2>

<table border="1" width="100%">
<tr>
    <th>Plat</th>
    <th>Nama</th>
    <th>Hasil</th>
    <th>Tanggal</th>
</tr>

@foreach($data as $d)
<tr>
    <td>{{ $d->rfid->vehicle->no_kendaraan }}</td>
    <td>{{ $d->rfid->vehicle->user->nama }}</td>
    <td>{{ $d->hasil }}</td>
    <td>{{ $d->tgl_uji }}</td>
</tr>
@endforeach
</table>