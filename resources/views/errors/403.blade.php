@extends('layouts.app')
@section('content')
<div class="text-center mt-5">
    <h1 class="display-1 fw-bold text-danger">403</h1>
    <p class="fs-3"> <span class="text-danger">Opps!</span> Akses Ditolak.</p>
    <p class="lead">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    <a href="{{ url()->previous() }}" class="btn btn-primary">Kembali</a>
</div>
@endsection