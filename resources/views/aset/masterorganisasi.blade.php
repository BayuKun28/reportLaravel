<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabel Organisasi</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <h2>Tabel Organisasi</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode OPD</th>
                <th>Nama Organisasi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $item->kodeurusan }}</td>
                    <td>{{ $item->organisasi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
