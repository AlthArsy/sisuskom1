<?php
include '../koneksi.php';

$query = "
    SELECT tb_elemen.id_elemen, tb_elemen.nama_elemen, tb_unit_kompetensi.kode_unit, tb_elemen.no_elemen, tb_unit_kompetensi.judul_unit
    FROM tb_elemen
    LEFT JOIN tb_unit_kompetensi ON tb_elemen.id_unit = tb_unit_kompetensi.id_unit
    ORDER BY tb_elemen.id_elemen ASC
";

$result = mysqli_query($koneksi, $query);
?>
    <style>
        .jd {
            color: #14305c;
            margin-bottom: 18px;
            font-size: 1.4em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        th, td {
            border: 1px solid #e7e7eb;
            padding: 10px 14px;
            text-align: left;
        }
        th {
            background: #24365e;
            color: #fff;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background: #f4f7fd;
        }
        tr:nth-child(odd) {
            background: #fff;
        }
    </style>
<div class="elemen-container">
    <h2 class="jd">Daftar Elemen Kompetensi</h2>
    <table>
        <thead>
            <tr>
                <th>ID Elemen</th>
                <th>Unit Kompetensi</th>
                <th>No Elemen</th>
                <th>Nama Elemen</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0):
            while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_elemen']) ?></td>
                    <td>
                        <?= htmlspecialchars($row['kode_unit']) ?> - 
                        <?= htmlspecialchars($row['judul_unit']) ?>
                    </td>
                    <td><?= htmlspecialchars($row['no_elemen']) ?></td>
                    <td><?= htmlspecialchars($row['nama_elemen']) ?></td>
                </tr>
            <?php endwhile;
        else: ?>
            <tr>
                <td colspan="3" style="text-align:center;">Belum ada data elemen.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php
mysqli_close($koneksi);
?>
