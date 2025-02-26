<!DOCTYPE html>
<html>
<head>
    <title>Data Kategori Barang</title>
</head>
<body>
    <h1>Data Kategori Barang</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Kode Kategori</th>
            <th>Nama Kategori</th>
        </tr>
        @foreach ($data as $d)
        <tr>
            <td>{{ $d->Kategori_id }}</td>
            <td>{{ $d->Kategori_kode }}</td>
            <td>{{ $d->Kategori_nama }}</td> <!-- Sudah diperbaiki -->
        </tr>
        @endforeach
    </table>
</body>
</html>
