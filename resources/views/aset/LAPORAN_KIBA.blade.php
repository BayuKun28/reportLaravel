<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KARTU INVENTARIS BARANG (KIB) A
    </title>
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
        <h3>KARTU INVENTARIS BARANG (KIB) A
        </h3>
        <h3>TANAH
        </h3>
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
                <th rowspan="3" style="width: 3%">No</th>
                <th rowspan="3">Kode OPD</th>
                <th rowspan="3">Nama OPD</th>
                <th rowspan="3">Jenis Barang/ Nama Barang</th>
                <th colspan="2">Nomor</th>
                <th rowspan="3">Luas (M2)</th>
                <th rowspan="3">Tahun Pengadaan</th>
                <th colspan="3">Status Tanah</th>
                <th rowspan="3">Penggunaan</th>
                <th rowspan="3">Asal Usul</th>
                <th rowspan="3">Klasifikasi</th>
                <th rowspan="3">Harga (Rp)</th>
                <th rowspan="3">Deskripsi Barang</th>
                <th rowspan="3">Keterangan</th>
                <th rowspan="3">Qrcode</th>
            </tr>
            <tr>
                <th rowspan="2">Kode Barang</th>
                <th rowspan="2">Register</th>
                <th rowspan="2">Letak/ Alamat</th>
                <th colspan="2">Sertifikat</th>
            </tr>
            <tr>
                <th>HAK</th>
                <th>TANGGAL SERTIFIKAT</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = $counter;
                $totalNilaiAkumulasiBarang = 0;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $item->kodeopd }}</td>
                    <td style="text-align: left;">{{ $item->uraiorganisasi }}</td>
                    <td>{{ $item->uraibarang }}</td>
                    <td>{{ $item->kodebarang }}</td>
                    <td>{{ $item->koderegister }}</td>
                    <td>{{ $item->luas }}</td>
                    <td>{{ $item->tahunperolehan }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>{{ $item->hak }}</td>
                    <td>{{ $item->tglsertifikat }}</td>
                    <td>{{ $item->penggunaan }}</td>
                    <td>{{ $item->asalusul }}</td>
                    <td>{{ $item->klasifikasi }}</td>
                    <td>{{ number_format($item->nilaiakumulasibarang) }}</td>
                    <td>{{ $item->deskripsibarang }}</td>
                    <td>{{ $item->keterangan }}</td>
                    @if ($type === 'pdf')
                        return <td><img src="data:image/png;base64, {!! base64_encode(QrCode::size(50)->generate($item->qrcode)) !!} "></td>
                    @else
                        return <td>{{ $item->qrcode }}</td>
                    @endif
                </tr>
                @php
                    $totalNilaiAkumulasiBarang += $item->nilaiakumulasibarang;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="14"><strong>Total</strong></td>
                <td><strong>{{ number_format($totalNilaiAkumulasiBarang) }}</strong></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
    <div class="footer">
        <div class="page-number"></div>
    </div>
</body>

</html>
