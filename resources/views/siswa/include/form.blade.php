<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-group">
            <label for="nama-siswa">{{ __('Nama Siswa') }}</label>
            <input type="text" name="nama_siswa" id="nama-siswa" class="form-control @error('nama_siswa') is-invalid @enderror" value="{{ isset($siswa) ? $siswa->nama_siswa : old('nama_siswa') }}" placeholder="{{ __('Nama Siswa') }}" required />
            @error('nama_siswa')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="ni">{{ __('NIS') }}</label>
            <input type="text" name="nis" id="ni" class="form-control @error('nis') is-invalid @enderror" value="{{ isset($siswa) ? $siswa->nis : old('nis') }}" placeholder="{{ __('Nis') }}" required />
            @error('nis')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="jurusan-id">{{ __('Jurusan') }}</label>
            <select class="form-select @error('jurusan_id') is-invalid @enderror" name="jurusan_id" id="jurusan-id" class="form-control" required>
                <option value="" selected disabled>-- {{ __('Select jurusan') }} --</option>

                        @foreach ($jurusans as $jurusan)
                            <option value="{{ $jurusan?->id }}" {{ isset($siswa) && $siswa?->jurusan_id == $jurusan?->id ? 'selected' : (old('jurusan_id') == $jurusan?->id ? 'selected' : '') }}>
                                {{ $jurusan?->nama_jurusan }}
                            </option>
                        @endforeach
            </select>
            @error('jurusan_id')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="kelas-id">{{ __('Kelas') }}</label>
            <select class="form-select @error('kelas_id') is-invalid @enderror" name="kelas_id" id="kelas-id" class="form-control" required>
                <option value="" selected disabled>-- {{ __('Select kela') }} --</option>

                        @foreach ($kelas as $kela)
                            <option value="{{ $kela?->id }}" {{ isset($siswa) && $siswa?->kelas_id == $kela?->id ? 'selected' : (old('kelas_id') == $kela?->id ? 'selected' : '') }}>
                                {{ $kela?->nama_kelas }}
                            </option>
                        @endforeach
            </select>
            @error('kelas_id')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="password">{{ __('Password') }}</label>
            <input type="text" name="password" id="password" class="form-control @error('password') is-invalid @enderror" value="{{ isset($siswa) ? $siswa->password : old('password') }}" placeholder="{{ __('Password') }}" required />
            @error('password')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
</div>