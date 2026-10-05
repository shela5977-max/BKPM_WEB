<?php
$nama = "Ingka Jivanda Gayshela";
$nim = "E41250657";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Server</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 600px;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
        }

        th {
            background-color: #eeeeee;
            text-align: left;
        }
    </style>
</head>

<body>

    <h1>Informasi Server</h1>

    <table>
        <tr>
            <th>Informasi</th>
            <th>Keterangan</th>
        </tr>

        <tr>
            <td>Nama</td>
            <td><?= $nama ?></td>
        </tr>

        <tr>
            <td>NIM</td>
            <td><?= $nim ?></td>
        </tr>

        <tr>
            <td>Waktu Server</td>
            <td><?= date("Y-m-d H:i:s") ?></td>
        </tr>

        <tr>
            <td>Versi PHP</td>
            <td><?= phpversion() ?></td>
        </tr>

        <tr>
            <td>Sistem Operasi Server</td>
            <td><?= PHP_OS ?></td>
        </tr>
    </table>

</body>
</html>