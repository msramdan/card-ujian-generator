<table>
    <thead>
        <tr>
            <th>nama_siswa</th>
            <th>nis</th>
            <th>jurusan</th> {{-- Akan diisi dengan nama jurusan --}}
            <th>kelas</th> {{-- Akan diisi dengan nama kelas --}}
            <th>password</th>
        </tr>
    </thead>
    <tbody>
        {{-- Contoh Data (Opsional, bisa dikosongkan jika sudah ada dropdown validation) --}}
        <tr>
            <td>Budi Santoso</td>
            <td>1234567890</td>
            <td>{{ $contohJurusan[0] ?? 'IPA 1' }}</td>
            <td>{{ $contohKelas[0] ?? 'X IPA 1' }}</td>
            <td>password123</td>
        </tr>
        <tr>
            <td>Ani Lestari</td>
            <td>0987654321</td>
            <td>{{ $contohJurusan[1] ?? 'IPS 2' }}</td>
            <td>{{ $contohKelas[1] ?? 'XI IPS 2' }}</td>
            <td>rahasiaku</td>
        </tr>
    </tbody>
</table>
