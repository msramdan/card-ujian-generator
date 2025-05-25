<!DOCTYPE html>
<html>

<head>
    <meta charset='UTF-8'>
    <title>Cetak Kartu Siswa</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <style>
        @media print {
            * {
                -webkit-print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }
        }

        @page {
            width: 21cm;
            min-height: 29.7cm;
            margin: 1cm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .card-container {
            position: relative;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            overflow: hidden;
        }

        .student-card {
            width: 480px;
            position: relative;
        }

        .student-info {
            position: absolute;
            right: 50px;
            top: 100px;
            font-family: 'Cambria', serif;
            font-size: 14px;
            border-collapse: collapse;
            width: 260px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 5px;
            padding: 5px;
        }

        .student-info tr td {
            padding: 1px 2px;
        }

        .student-info tr td:first-child {
            font-weight: bold;
            width: 35%;
        }

        .student-info tr td:nth-child(2) {
            width: 5%;
        }

        .qr-code {
            position: absolute;
            left: 35px;
            top: 100px;
            background-color: white;
            padding: 5px;
            border-radius: 5px;
        }

        .action-buttons {
            margin-top: 20px;
            text-align: center;
        }

        .btn-download {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn-download:hover {
            background-color: #45a049;
        }

        .btn-print {
            background-color: #2196F3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            margin-left: 10px;
        }

        .btn-print:hover {
            background-color: #0b7dda;
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card-container" id="card-to-capture">
            <div class="student-card">
                <table class="student-info">
                    <tbody>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td><strong>{{ $siswa->nama_siswa }}</strong></td>
                        </tr>
                        <tr>
                            <td>Jurusan</td>
                            <td>:</td>
                            <td><strong>{{ $siswa->nama_jurusan }}</strong></td>
                        </tr>
                        <tr>
                            <td>Kelas</td>
                            <td>:</td>
                            <td><strong>{{ $siswa->nama_kelas }}</strong></td>
                        </tr>
                        <tr>
                            <td>Ruang ujian</td>
                            <td>:</td>
                            <td><strong>{{ $siswa->nama_ruang_ujian }}</strong></td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td>:</td>
                            <td><strong>{{ $siswa->password }}</strong></td>
                        </tr>
                    </tbody>
                </table>
                <div class="qr-code">
                    {!! $qrSvg !!}
                </div>
                <img src="{{ asset('kartu/bg2.png') }}" alt="Student Card Background" style="width: 100%;">
            </div>
        </div>

        <div class="action-buttons no-print">
            <button class="btn-download" onclick="downloadCard()">Download Kartu</button>
            {{-- <button class="btn-print" onclick="window.print()">Cetak Kartu</button> --}}
        </div>
    </div>

    <script>
        function downloadCard() {
            // Use html2canvas to capture the card
            html2canvas(document.getElementById('card-to-capture')).then(canvas => {
                // Create download link
                const link = document.createElement('a');
                link.download = 'KARTU-PSAT-{{ $siswa->nama_siswa }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        }
    </script>
</body>

</html>
