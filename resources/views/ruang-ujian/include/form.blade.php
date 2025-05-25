<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-group">
            <label for="nama-ruang-ujian">{{ __('Nama Ruang Ujian') }}</label>
            <input type="text" name="nama_ruang_ujian" id="nama-ruang-ujian" class="form-control @error('nama_ruang_ujian') is-invalid @enderror" value="{{ isset($ruangUjian) ? $ruangUjian->nama_ruang_ujian : old('nama_ruang_ujian') }}" placeholder="{{ __('Nama Ruang Ujian') }}" required />
            @error('nama_ruang_ujian')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
</div>