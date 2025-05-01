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
        padding: 2cm;
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
    <div class="card" class="border" style="width: 30rem;float :left; margin:10px; padding:10px">
        <p
            style=" color: white; margin-top: 10px; right:50px; position: absolute;font-family: Cambria;font-size: 19px;">
            <strong>PEMERINTAH PROVINSI MALUKU</strong></p>
        <p
            style=" color: white; margin-top: 30px; right:50px; position: absolute;font-family: Cambria;font-size: 16px;">
            <strong>DINAS PENDIDIKAN DAN KEBUDAYAAN</strong></p>

        <p
            style=" color: white; margin-top: 50px; right:130px; position: absolute;font-family: Cambria;font-size: 20px;text-transform: uppercase;">
            <strong><?= $sett_apps->nama_sekolah ?></strong></p>
        <p style="margin-top: 90px; right:130px; position: absolute;font-family: Cambria;font-size: 20px;"><strong>KARTU
                SISWA</strong></p>
        <table
            style="margin-top: 130px; position: absolute; right:130px; text-align: right; font-family: Cambria;font-size: 12px;">
            <td>NISN</td>
            </tr>
            <tr>
                <td><?= $nisn ?></td>
            </tr>
            <tr>
                <td>Nama</td>
            </tr>
            <tr>
                <td><strong style="font-size: 10px;"><?= $nama_siswa ?>

                    </strong></td>
            </tr>
            <tr>
                <td>Tempat, Tanggal lahir</td>
            </tr>
            <tr>
                <td><?= $tempat_lahir ?>, <?= $tanggal_lahir ?></td>
            </tr>
            <tr>
                <td>Alamat, <?= $alamat ?></td>

            </tr>
        </table>
        <p
            style="font-family:Verdana; right:50px; margin-top: 256px; text-align:right; padding-left: 10px;font-size: 8px;  position: absolute;">
            Alamat Sekolah : <?= $sett_apps->alamat_sekolah ?> </p>
        <img class="card-img-top" src="<?= base_url('assets/assets/img/kartu/birunom.png') ?>" alt="Card image cap">
        <?php if($photo=='' || $photo==null ){ ?>
        <img style="border: 1px solid #ffffff;position: absolute;right: 30px;margin-top: 130px;"
            src="<?= base_url('assets/assets/img/icon/default.png') ?>" width="85px" height="100px">
        <?php }else{ ?>
        <img style="border: 1px solid #ffffff;position: absolute;right: 30px;margin-top: 130px;"
            src="<?= base_url('assets/assets/img/siswa/' . $photo) ?>" width="85px" height="100px">
        <?php } ?>

        <img style="position: absolute;margin-left: 35px;margin-top: 130px;"
            src="<?= base_url('assets/assets/img/qr/siswa/' . $qr_code) ?>" width="120px" height="120px">
        <img style="position: absolute;margin-left: 60px;margin-top: 15px;"
            src="<?= base_url('assets/assets/img/logo/' . $sett_apps->logo_sekolah) ?>" width="55px" height="55px">
    </div>
</body>

</html>
