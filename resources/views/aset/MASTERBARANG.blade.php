<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MASTER BARANG</title>
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
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: center;
            padding: 4px;
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
        <h3>MASTER BARANG</h3>
    </center>
    <table>
        <thead>
            <tr>
                <th>No Urut</th>
                <th>Akun</th>
                <th>Kelompok</th>
                <th>Jenis</th>
                <th>Objek</th>
                <th>Rincian Objek</th>
                <th>Rincian Sub Objek</th>
                <th>Sub Sub Rincian</th>
                <th>Gabungan Kode</th>
                <th>Barang</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = $counter;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td>{{ number_format($i++) }}</td>
                    <td>{{ $item->akun }}</td>
                    <td>{{ $item->kelompok }}</td>
                    <td>{{ $item->jenis }}</td>
                    <td>{{ $item->kodebidang }}</td>
                    <td>{{ $item->kodekelompok }}</td>
                    <td>{{ $item->kodesub }}</td>
                    <td>{{ $item->kodesubsub }}</td>
                    <td>{{ $item->transformkodebarang }}</td>
                    <td style="text-align: left">{{ $item->urai }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        <div class="page-number"></div>
    </div>
</body>

</html>
