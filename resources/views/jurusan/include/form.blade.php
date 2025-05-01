<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-group">
            <label for="nama-jurursan">{{ __('Nama Jurursan') }}</label>
            <input type="text" name="nama_jurusan" id="nama-jurursan" class="form-control @error('nama_jurusan') is-invalid @enderror" value="{{ isset($jurusan) ? $jurusan->nama_jurusan : old('nama_jurusan') }}" placeholder="{{ __('Nama Jurursan') }}" required />
            @error('nama_jurusan')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
</div>
