<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAPORAN PENGADAAN BARANG</title>
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

        th {
            border: 1px solid #dddddd;
            text-align: center;
            padding: 4px;
        }

        td {
            border: 1px solid #dddddd;
            text-align: center;
            vertical-align: top;
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
        <h3>LAPORAN PENGADAAN BARANG</h3>
        <h4>TAHUN ANGGARAN {{ $tahun }}</h4>
    </center>
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 3%">No</th>
                <th colspan="6">SPK/PERJANJIAN/KONTRAK</th>
                <th colspan="2">DPA/SPM/KWITANSI</th>
                <th rowspan="2">Transaksi</th>
                <th rowspan="2">Harga (Rp)</th>
            </tr>
            <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Tanggal</th>
                <th>Nomor</th>
                <th>Nama Penyedia</th>
                <th>NPWP</th>
                <th>Tanggal</th>
                <th>Nomor</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
                $totalNilaiAkumulasiBarang = 0;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $item->kodebarang }}</td>
                    <td style="text-align: left;">{{ $item->uraibarang }}</td>
                    <td>{{ $item->tanggalkontrak }}</td>
                    <td>{{ $item->nokontrak }}</td>
                    <td>{{ $item->nama_penyedia }}</td>
                    <td>{{ $item->npwp }}</td>
                    <td>{{ $item->tanggalkuitansi }}</td>
                    <td>{{ $item->nokuitansi }}</td>
                    <td>{{ $item->transaksi }}</td>
                    <td>{{ number_format($item->nilaiakumulasibarang) }}</td>
                </tr>
                @php
                    $totalNilaiAkumulasiBarang += $item->nilaiakumulasibarang;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="10"><strong>Total</strong></td>
                <td><strong>{{ number_format($totalNilaiAkumulasiBarang) }}</strong></td>
            </tr>
        </tfoot>
    </table>
    <div class="footer">
        <div class="page-number"></div>
    </div>
</body>

</html>
