<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .container {
            width: 90%;
            margin: 0 auto;
        }

        h1,
        h2,
        h3 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header-table td {
            border: none;
            padding: 2px;
        }

        .signature-section {
            margin-top: 50px;
            width: 100%;
            text-align: right;
        }

        .signature-block {
            margin-top: 60px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <button class="no-print" onclick="window.print()">Cetak Halaman Ini</button>
        <h3>LOGBOOK KEGIATAN PRAKTEK KERJA LAPANG (PKL)</h3>
        <h4>MINGGU KE-<?= $week_number ?></h4>

        <table class="header-table">
            <tr>
                <td width="150">Nama</td>
                <td width="10">:</td>
                <td><?= html_escape($student->name) ?></td>
            </tr>
            <tr>
                <td>NIM</td>
                <td>:</td>
                <td><?= html_escape($student->id) ?></td>
            </tr>
            <tr>
                <td>Program Studi</td>
                <td>:</td>
                <td><?= html_escape($student->study_program) ?></td>
            </tr>
            <tr>
                <td>Instansi/Perusahaan</td>
                <td>:</td>
                <td><?= html_escape($application->place_name) ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><?= html_escape($application->place_address) ?></td>
            </tr>
            <tr>
                <td>Periode Kegiatan</td>
                <td>:</td>
                <td><?= $start_date_period ?> s.d. <?= $end_date_period ?></td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th width="120">Tanggal</th>
                    <th width="120">Jam</th>
                    <th>Uraian Kegiatan</th>
                    <th>Relevansi Keilmuan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)) : ?>
                    <tr>
                        <td colspan="4" style="text-align:center;">Tidak ada data kegiatan untuk minggu ini.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($logs as $log) : ?>
                        <tr>
                            <td><?= date('d M Y', strtotime($log->date)) ?></td>
                            <td><?= html_escape($log->start_time) ?> - <?= html_escape($log->end_time) ?></td>
                            <td>
                                <strong><?= html_escape($log->activity_title) ?></strong><br>
                                <?= nl2br(html_escape($log->activity_description)) ?>
                            </td>
                            <td><?= $log->is_relevant ? 'Ya' : 'Tidak' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="signature-section">
            <p>Mengetahui,</p>
            <p>Pembimbing Lapangan</p>
            <div class="signature-block">
                (...................................................)
            </div>
        </div>
    </div>
</body>

</html>