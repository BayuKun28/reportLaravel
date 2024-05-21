<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAFTAR PENYUSUTAN</title>
    <style>
        body {
            font-family: sans-serif;
        }

        @page {
            margin: 50px 25px;
        }

        .footer {
            position: fixed;
            bottom: -25px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: right;
            font-size: 10px;
            color: #555;
        }

        .footer .page-number:after {
            content: "Page " counter(page);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10px;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: center;
            padding: 4px;
            word-wrap: break-word;
            word-break: break-all;
        }

        th {
            background-color: #f2f2f2;
        }

        .no-border {
            border: none;
        }

        .no-border td {
            border: none;
            text-align: left;
        }

        .no-border td:first-child {
            width: 20%;
        }

        .no-border td:nth-child(2) {
            width: 2%;
        }

        .no-border td:nth-child(3) {
            width: 78%;
        }
    </style>
</head>

<body>
    <center>
        <h3>DAFTAR PENYUSUTAN</h3>
        <h4>TAHUN ANGGARAN {{ $tahun }}</h4>
    </center>
    <table class="no-border">
        <tr>
            <td>SKPD</td>
            <td>:</td>
            <td>{{ $judul[0]->organisasi }}</td>
        </tr>
        <tr>
            <td>Kabupaten</td>
            <td>:</td>
            <td></td>
        </tr>
        <tr>
            <td>Provinsi</td>
            <td>:</td>
            <td>Papua Barat Daya</td>
        </tr>
        <tr>
            <td>Klasifikasi</td>
            <td>:</td>
            <td>{{ $judul[0]->klasifikasi }}</td>
        </tr>
    </table>
    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Jenis Barang</th>
                <th>Tahun Perolehan</th>
                <th>Harga Perolehan (Rp.)</th>
                <th>Harga Perolehan Baru (Rp.)</th>
                <th>Masa Manfaat( Kebijakan Akuntansi No.43 thn 2013)</th>
                <th>Penyusutan Per Tahun (Rp.)</th>
                <th>Tahun Berakhirnya Penyusutan</th>
                <th>Masa Manfaat yang telah dilalui s/d 31 Des [xtahun]</th>
                <th>Akumulasi Penyusutan s/d Tahun [<xtahun>-1] (Rp)</th>
                <th>Beban Penyusutan Tahun [xtahun] (Rp)</th>
                <th>Akumulasi Penyusutan s/d Tahun [xtahun] (Rp)</th>
                <th>Nilai Buku (Rp)</th>
                <th>Transaksi</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        <tfoot>
            <tr>

            </tr>
        </tfoot>
    </table>
</body>

</html>
