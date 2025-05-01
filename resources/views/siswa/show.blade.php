@extends('layouts.app')

@section('title', __('Detail of Siswas'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Siswas') }}</h3>
                    <p class="text-subtitle text-muted">
                        {{ __('Detail of siswa.') }}
                    </p>
                </div>

                <x-breadcrumb>
                    <li class="breadcrumb-item">
                        <a href="/">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('siswa.index') }}">{{ __('Siswas') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __('Detail') }}
                    </li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <tr>
                                        <td class="fw-bold">{{ __('Nama Siswa') }}</td>
                                        <td>{{ $siswa->nama_siswa }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">{{ __('Nis') }}</td>
                                        <td>{{ $siswa->nis }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">{{ __('Jurusan') }}</td>
                                        <td>{{ $siswa->nama_jurusan }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">{{ __('Kelas') }}</td>
                                        <td>{{ $siswa->nama_kelas }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">{{ __('Password') }}</td>
                                        <td>{{ $siswa->password }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">{{ __('QR Download') }}</td>
                                        <td> {!! $qrSvg !!}</td>
                                    </tr>
                                </table>
                            </div>

                            <a href="{{ route('siswa.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
