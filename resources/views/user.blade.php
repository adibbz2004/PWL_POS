<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data User</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 20px;
            padding: 20px;
            text-align: center;
        }
        table {
            width: 50%;
            margin: 0 auto;
            border-collapse: collapse;
            font-size: 16px;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Data User</h1>
    <table>
        <tr>
            <th>No</th>
            <th>Keterangan</th>
            <th>Jumlah</th>
        </tr>
        <tr>
            <td>1</td>
            <td>Jumlah Pengguna</td>
            <td>{{ $userCount }}</td>
        </tr>
    </table>
</body>
</html>
