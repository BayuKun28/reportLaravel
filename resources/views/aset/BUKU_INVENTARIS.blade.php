<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BUKU INVENTARIS</title>
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
        <h3>BUKU INVENTARIS</h3>
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
                <th colspan="3">Nomor</th>
                <th colspan="3">SPESIFIKASI BARANG</th>
                <th rowspan="2">Bahan</th>
                <th rowspan="2">Asal/Cara Perolehan Barang</th>
                <th rowspan="2">Tahun Perolehan</th>
                <th rowspan="2">Ukuran Barang /Kontruksi (P,S,D)</th>
                <th rowspan="2">Satuan</th>
                <th rowspan="2">Keadaan Barang (B/KB/RB)</th>
                <th colspan="3">JUMLAH</th>
            </tr>
            <tr>
                <th style="width: 3%">No Urut</th>
                <th>Kode Barang</th>
                <th>Register</th>
                <th>Jenis Barang / Nama Barang</th>
                <th>Merk / Type</th>
                <th>No.Sertifikat No.Pabrik No.Chasis No.Mesin</th>
                <th>Jumlah Barang</th>
                <th>Harga (Rp)</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
                $totalJumlahBarang = 0;
                $totalHarga = 0;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $item->kodebarang }}</td>
                    <td>{{ $item->register }}</td>
                    <td>{{ $item->uraibarang }}</td>
                    <td>{{ $item->merktype }}</td>
                    <td>{{ $item->nodokumen }}</td>
                    <td>{{ $item->bahan }}</td>
                    <td>{{ $item->asalusul }}</td>
                    <td>{{ $item->tahunperolehan }}</td>
                    <td>{{ $item->konstruksi }}</td>
                    <td>{{ $item->satuan }}</td>
                    <td>{{ $item->kondisi }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ number_format($item->total, 0, ',', '.') }}</td>
                    <td>{{ '@' . number_format($item->harga, 0, ',', '.') }}</td>
                </tr>
                @php
                    $totalJumlahBarang += $item->jumlah;
                    $totalHarga += $item->total;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="12"><strong>Total</strong></td>
                <td><strong>{{ $totalJumlahBarang }}</strong></td>
                <td><strong>{{ number_format($totalHarga, 0, ',', '.') }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    <div class="footer">
        <div class="page-number"></div>
    </div>
</body>

</html>
