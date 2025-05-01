<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-group">
            <label for="nama-kela">{{ __('Nama Kelas') }}</label>
            <input type="text" name="nama_kelas" id="nama-kela" class="form-control @error('nama_kelas') is-invalid @enderror" value="{{ isset($kela) ? $kela->nama_kelas : old('nama_kelas') }}" placeholder="{{ __('Nama Kelas') }}" required />
            @error('nama_kelas')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
</div>