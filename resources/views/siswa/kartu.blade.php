<!DOCTYPE html>
<html>
<!-- Bagian halaman HTML yang akan konvert -->

<head>
    <meta charset='UTF-8'>
    <title>Cetak Kartu siswa</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<style>
    @media print {
        * {
            -webkit-print-color-adjust: exact;
        }
    }

    @page {
        width: 21cm;
        min-height: 29.7cm;
        /* padding: 2cm; */
        margin: 1cm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    table {
        border-spacing: 0px;
    }

    /* cellspacing */

    th,
    td {
        padding: 0px;
    }
</style>

<body onload='window.print()' style="font-size: 12px;margin-top:0;position:absolute;">
    <div class="card" class="border" style="width: 30rem;float :left; margin:10px; padding:5px">
            <table style="margin-top: 100px; position: absolute; right: 50px; font-family: Cambria; font-size: 12px; border-collapse: collapse; width: 260px;">
                <tbody>
                    <tr>
                        <td style="width: 35%; font-weight: bold; padding: 4px 6px;">Nama</td>
                        <td style="width: 5%;">:</td>
                        <td style="padding: 4px 6px;"><strong>{{ $siswa->nama_siswa }}</strong></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; padding: 4px 6px;">Jurusan</td>
                        <td>:</td>
                        <td style="padding: 4px 6px;"><strong>{{ $siswa->nama_jurusan }}</strong></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; padding: 4px 6px;">Kelas</td>
                        <td>:</td>
                        <td style="padding: 4px 6px;"><strong>{{ $siswa->nama_kelas }}</strong></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; padding: 4px 6px;">Password</td>
                        <td>:</td>
                        <td style="padding: 4px 6px;"><strong>{{ $siswa->password }}</strong></td>
                    </tr>
                </tbody>
            </table>
        <img class="card-img-top" src="{{ asset('kartu/bg2.png') }}" alt="">
        <div style="position: absolute; margin-left: 35px; margin-top: 100px;">
            {!! $qrSvg !!}
        </div>
    </div>
</body>

</html>
