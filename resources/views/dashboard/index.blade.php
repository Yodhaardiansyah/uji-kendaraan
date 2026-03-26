@extends('layouts.app')

@section('content')

<h2>Dashboard</h2>

<div>
    <p>Total Kendaraan: {{ $total_kendaraan ?? '-' }}</p>
    <p>Total Uji: {{ $total_uji }}</p>
    <p>Total User: {{ $total_user ?? '-' }}</p>
</div>

<div>
    <p style="color:green;">Lolos: {{ $lolos }}</p>
    <p style="color:red;">Tidak Lolos: {{ $tidak_lolos }}</p>
</div>

<canvas id="chart"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const data = @json($grafik);
const labels = data.map(d => 'Bulan ' + d.bulan);
const values = data.map(d => d.total);

new Chart(document.getElementById('chart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{ data: values }]
    }
});
</script>

@endsection