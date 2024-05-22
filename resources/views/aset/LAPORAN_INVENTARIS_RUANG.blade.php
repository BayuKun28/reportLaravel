<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAPORAN INVENTARIS RUANG</title>
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
        <h3>LAPORAN INVENTARIS RUANG</h3>
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
                <th rowspan="2" style="width: 3%">No</th>
                <th rowspan="2">Jenis Barang/ Nama Barang</th>
                <th rowspan="2">Merek/ Model</th>
                <th rowspan="2">No.
                    Seri
                    Pabrik
                </th>
                <th rowspan="2">No. Rangka
                </th>
                <th rowspan="2">No.
                    Mesin
                </th>
                <th rowspan="2">No. BPKB
                </th>
                <th rowspan="2">No.
                    Polisi
                </th>
                <th rowspan="2">Ukuran
                </th>
                <th rowspan="2">Bahan
                </th>
                <th rowspan="2">Judul
                </th>
                <th rowspan="2">Tahun
                    Pembuatan/
                    Pembelian
                </th>
                <th rowspan="2">No. Kode Barang
                </th>
                <th rowspan="2">Harga Beli/
                    Perolehan
                </th>
                <th colspan="3">Keadaan Barang
                </th>
                <th rowspan="2">Keterangan Mutasi dll
                </th>
                <th rowspan="2">Kodekib
                </th>
            </tr>
            <tr>
                <th>Baik
                    (B)
                </th>
                <th>Kurang Baik (KB)
                </th>
                <th>Rusak Berat (RB)
                </th>
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
                    <td>{{ $item->uraibarang }}</td>
                    <td>{{ $item->merktype }}</td>
                    <td>{{ $item->nopabrik }}</td>
                    <td>{{ $item->norangka }}</td>
                    <td>{{ $item->nomesin }}</td>
                    <td>{{ $item->nobpkb }}</td>
                    <td>{{ $item->nopolisi }}</td>
                    <td>{{ $item->ukuran }}</td>
                    <td>{{ $item->bahan }}</td>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->tahunpembuatan }}</td>
                    <td>{{ $item->brg }}</td>
                    <td>{{ number_format($item->nilaiakumulasibarang) }}</td>
                    <td>{{ $item->kondisib }}</td>
                    <td>{{ $item->kondisikb }}</td>
                    <td>{{ $item->kondisirb }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td>{{ $item->kodekib }}</td>
                </tr>
                @php
                    $totalNilaiAkumulasiBarang += $item->nilaiakumulasibarang;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
            <tr>
                <td colspan="13"><strong>Total</strong></td>
                <td><strong>{{ number_format($totalNilaiAkumulasiBarang) }}</strong></td>
                <td colspan="5"></td>
            </tr>
            </tr>
        </tfoot>
    </table>
    <div class="footer">
        <div class="page-number"></div>
    </div>
</body>

</html>
