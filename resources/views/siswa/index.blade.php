@extends('layouts.app')

@section('title', __('Siswa'))

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-8 order-md-1 order-last">
                    <h3>{{ __('Siswa') }}</h3>
                    <p class="text-subtitle text-muted">
                        {{ __('Below is a list of all siswa.') }}
                    </p>
                </div>
                <x-breadcrumb>
                    <li class="breadcrumb-item"><a href="/">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('Siswa') }}</li>
                </x-breadcrumb>
            </div>
        </div>

        <section class="section">
            <x-alert></x-alert>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-between mb-3">
                <div>
                    @can('siswa import')
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                            data-bs-target="#importSiswaModal">
                            <i class="fas fa-upload"></i> {{ __('Impor Data Siswa') }}
                        </button>
                    @endcan
                    @can('siswa export')
                        <a href="{{ route('siswa.export') }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> {{ __('Ekspor Data Siswa') }}
                        </a>
                    @endcan
                </div>
                <div>
                    @can('siswa create')
                        <a href="{{ route('siswa.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            {{ __('Create a new siswa') }}
                        </a>
                    @endcan
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive p-1">
                                <table class="table table-striped" id="data-table" width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Nama Siswa') }}</th>
                                            <th>{{ __('Nis') }}</th>
                                            <th>{{ __('Jurusan') }}</th>
                                            <th>{{ __('Kelas') }}</th>
                                            <th>{{ __('Ruang Ujian') }}</th>
                                            <th>{{ __('Password') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Modal Import Siswa --}}
    @can('siswa import')
        <div class="modal fade" id="importSiswaModal" tabindex="-1" aria-labelledby="importSiswaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importSiswaModalLabel">{{ __('Impor Data Siswa') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="import_file_siswa" class="form-label">{{ __('Pilih File Excel (.xlsx)') }}</label>
                                <input class="form-control" type="file" id="import_file_siswa" name="import_file_siswa"
                                    accept=".xlsx" required>
                                <div class="form-text mt-2">
                                    <a href="{{ route('siswa.format_import') }}">
                                        <i class="fas fa-download"></i> {{ __('Unduh Format Impor') }}
                                    </a>
                                </div>
                            </div>
                            <div class="alert alert-info">
                                <strong>Petunjuk:</strong>
                                <ul>
                                    <li>Pastikan kolom `jurusan` dan `kelas` diisi dengan **nama** yang sudah ada di sistem
                                        (Jurusan dan Kelas).</li>
                                    <li>Jika jurusan atau kelas tidak ditemukan, data siswa tersebut akan dilewati.</li>
                                    <li>Password akan disimpan sesuai dengan yang Anda masukkan di file Excel (plain text).</li>
                                </ul>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Tutup') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Impor') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.12.0/datatables.min.css" />
    {{-- SweetAlert2 CSS (jika belum ada di layout utama) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.12.0/datatables.min.js"></script>
    {{-- SweetAlert2 JS (jika belum ada di layout utama) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('siswa.index') }}",
                columns: [{
                        data: 'nama_siswa',
                        name: 'nama_siswa'
                    },
                    {
                        data: 'nis',
                        name: 'nis'
                    },
                    {
                        data: 'nama_jurusan',
                        name: 'jurusan.nama_jurusan'
                    },
                    {
                        data: 'nama_kelas',
                        name: 'kelas.nama_kelas'
                    },
                                        {
                        data: 'nama_ruang_ujian',
                        name: 'ruang_ujian.nama_ruang_ujian'
                    },
                    {
                        data: 'password',
                        name: 'password'
                    }, // Sebaiknya password tidak ditampilkan langsung
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
            });

            // Menampilkan pesan sukses atau error dari session menggunakan SweetAlert
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: "{{ session('success') }}",
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: "{!! session('error') !!}", // Menggunakan html agar <br> bisa dirender
                });
            @endif

            @if ($errors->any())
                let errorMessages = '';
                @foreach ($errors->all() as $error)
                    errorMessages += "{{ $error }}<br>";
                @endforeach
                Swal.fire({
                    icon: 'error',
                    title: 'Oops, terjadi kesalahan validasi!',
                    html: errorMessages,
                });
            @endif
        });
    </script>
@endpush
