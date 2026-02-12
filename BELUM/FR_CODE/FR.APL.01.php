<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FR.APL.01 - Formulir Permohonan Sertifikasi Kompetensi</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
        }
        h2 {
            margin: 0;
        }
        .form-box {
            background: #fff;
            margin: 32px auto;
            border-radius: 10px;
            max-width: 930px;
            box-shadow: 0 2px 16px rgb(4 100 160 / 8%), 0 1.5px 7px rgb(80 100 150 / 8%);
            padding: 32px;
        }
        .section-title {
            background: #e0e0e0;
            padding: 12px 22px;
            font-weight: bold;
            font-size: 1.1em;
            border-radius: 7px 7px 0 0;
            margin-bottom: 0px;
        }
        .info-table, .competency-table, .requirement-table, .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
            box-shadow: 0 0.5px 1px rgba(80 100 150 / 8%);
            background: #fff;
            border-radius: 7px;
            overflow: hidden;
        }
        .info-table td, .competency-table td, .competency-table th,
        .requirement-table th, .requirement-table td, .signature-table td {
            border: 1px solid #b6c7de;
            padding: 10px 10px;
            vertical-align: top;
            transition: background 0.2s;
        }
        .info-table .label, .competency-table th, .requirement-table th {
            background-color: aliceblue;
            font-weight: bold;
        }
        .info-table .label {
            width: 27%;
        }
        .form-label {
            font-weight: bold;
        }
        .form-control, select, textarea, input[type="text"], input[type="date"], input[type="number"], input[type="email"] {
            width: 98%;
            font-family: inherit;
            font-size: 1em;
            border: 1px solid #b6c7de;
            border-radius: 5px;
            padding: 6px 10px;
            margin-top: 3px;
            margin-bottom: 3px;
            background: #f8fcff;
            box-sizing: border-box;
        }
        select {
            height: 34px;
        }
        textarea {
            min-height: 45px;
            resize: vertical;
        }

        .competency-table th {
            text-align: center;
            font-weight: bold;
        }
        .requirement-table th {
            text-align: center;
            font-weight: bold;
        }
        .group-caption {
            font-weight: 600;
            background: #f4faff;
            letter-spacing: 1px;
            padding-left: 6px;
            padding-top: 14px;
        }
        .small-text {
            font-size: 0.90em;
            color: #888;
        }

        .signature-table {
            margin-top: 26px;
            border: none;
            background: none;
            box-shadow: none;
        }
        .signature-table-td {
            padding-left: 16px;
            padding-right: 16px;
            vertical-align: top;
            border: none;
            background: none;
        }
        .input-signature {
            width: 200px;
            margin: 4px 0 8px 0;
        }
        .ttd-preview {
            display:none; 
            max-width:200px; 
            max-height:100px; 
            margin-top:10px; 
            border-radius: 5px;
            box-shadow: 0 2px 10px #4c70ff18;
        }
        .form-note {
            color: #555;
            font-size: .9em;
        }
        .mt-2 {
            margin-top: 1em;
        }
        .mb-2 {
            margin-bottom: 1em;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <form action="prosesFR1.php" method="post" autocomplete="off" enctype="multipart/form-data">
            <table class="info-table">
                <tr>
                    <td colspan="4" style="text-align: center; background: #cadbfc;">
                        <h2>FR.APL.01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI</h2>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="section-title">
                        Bagian 1: Rincian Data Pemohon Sertifikasi<br>
                        <span class="small-text">Pada bagian ini, cantumkan data pribadi, data pendidikan formal serta data pekerjaan anda pada saat ini.</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="group-caption">a. Data Pribadi</td>
                </tr>
                <tr>
                    <td class="label">Nama</td>
                    <td>:</td>
                    <td colspan="2"><input type="text" name="nama" class="form-control" placeholder="Nama" required></td>
                </tr>
                <tr>
                    <td class="label">No. KTP/NIK/Paspor</td>
                    <td>:</td>
                    <td colspan="2"><input type="text" name="no_ktp" class="form-control" placeholder="No. KTP/NIK/Paspor" required></td>
                </tr>
                <tr>
                    <td class="label">Tempat Lahir</td>
                    <td>:</td>
                    <td colspan="2"><input type="text" name="tempat_lahir" class="form-control" placeholder="Tempat Lahir" required></td>
                </tr>
                <tr>
                    <td class="label">Tgl Lahir</td>
                    <td>:</td>
                    <td colspan="2"><input type="date" name="tanggal_lahir" class="form-control" required></td>
                </tr>
                <tr>
                    <td class="label">Jenis Kelamin</td>
                    <td>:</td>
                    <td colspan="2">
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="label">Kebangsaan</td>
                    <td>:</td>
                    <td colspan="2">
                        <select name="kebangsaan" class="form-control" required>
                            <option value="">Pilih Kebangsaan</option>
                            <option value="WNI">WNI</option>
                            <option value="WNA">WNA</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="label">Alamat Rumah</td>
                    <td>:</td>
                    <td colspan="2"><textarea name="alamat_rumah" class="form-control" placeholder="Alamat Rumah" required></textarea></td>
                </tr>
                <tr>
                    <td class="label">Kode Pos</td>
                    <td>:</td>
                    <td colspan="2"><input type="number" name="kode_pos" class="form-control" placeholder="Kode Pos" required></td>
                </tr>
                <tr>
                    <td class="label" rowspan="2">Phone/E-mail</td>
                    <td rowspan="2">:</td>
                    <td>
                        Rumah:
                        <input type="text" name="phone_rumah" class="form-control" placeholder="Phone Rumah">
                    </td>
                    <td>
                        Kantor:
                        <input type="text" name="phone_kantor" class="form-control" placeholder="Phone Kantor">
                    </td>
                </tr>
                <tr>
                    <td>
                        HP:
                        <input type="number" name="hp" class="form-control" placeholder="HP" required>
                    </td>
                    <td>
                        E-mail:
                        <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                    </td>
                </tr>
                <tr>
                    <td class="label">Kualifikasi/Pendidikan</td>
                    <td>:</td>
                    <td colspan="2"><input type="text" name="kualifikasi" class="form-control" placeholder="Kualifikasi/Pendidikan" required></td>
                </tr>
                <tr>
                    <td colspan="4" class="form-note">*)Coret yang tidak perlu</td>
                </tr>
                <tr>
                    <td colspan="4" class="group-caption">b. Data Pekerjaan Sekarang</td>
                </tr>
                <tr>
                    <td class="label">Nama Institusi/Perusahaan</td>
                    <td>:</td>
                    <td colspan="2"><input type="text" name="nama_institusi" class="form-control" placeholder="Nama Institusi/Perusahaan" required></td>
                </tr>
                <tr>
                    <td class="label">Jabatan</td>
                    <td>:</td>
                    <td colspan="2"><input type="text" name="jabatan" class="form-control" placeholder="Jabatan" required></td>
                </tr>
                <tr>
                    <td class="label">Alamat Kantor</td>
                    <td>:</td>
                    <td colspan="2"><input type="text" name="alamat_kantor" class="form-control" placeholder="Alamat Kantor" required></td>
                </tr>
                <tr>
                    <td class="label">Kode Pos</td>
                    <td>:</td>
                    <td colspan="2"><input type="number" name="kode_pos_kantor" class="form-control" placeholder="Kode Pos Kantor" required></td>
                </tr>
                <tr>
                    <td class="label" rowspan="2">No. Telp/Fax/E-mail</td>
                    <td rowspan="2">:</td>
                    <td>
                        Telp:
                        <input type="number" name="telp" class="form-control" placeholder="Telp">
                    </td>
                    <td>
                        Fax:
                        <input type="text" name="fax" class="form-control" placeholder="Fax">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        E-mail:
                        <input type="email" name="email_kantor" class="form-control" placeholder="E-mail Kantor">
                    </td>
                </tr>
            </table>
        </form>

        <form action="prosesFR2.php" method="post" enctype="multipart/form-data">
            <table class="competency-table">
                <tr>
                    <td colspan="4" class="section-title">
                        Bagian 2: Data Sertifikasi<br>
                        <span class="small-text">Tuliskan Judul dan Nomor Skema Sertifikasi berikut Daftar Unit Kompetensi sesuai kemasan pada skema sertifikasi untuk mendapatkan pengakuan sesuai dengan latar belakang pendidikan, pelatihan serta pengalaman kerja yang anda miliki.</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="background:#f8fcff;">Skema Sertifikasi:<br><span class="small-text">(KKNI/Okupasi/Klaster)</span></td>
                    <td class="label">Judul</td>
                    <td>
                        <select name="judul_skema" class="form-control" required>
                            <option value="">Pilih Judul Skema</option>
                            <option value="Pemrogram Junior">Pemrogram Junior</option>
                            <option value="Pemrogram Pemula">Pemrogram Pemula</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td class="label">Nomor</td>
                    <td>
                        <input type="number" name="nomor" class="form-control" placeholder="Nomor" min="6">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="label">Tujuan Asesmen</td>
                    <td colspan="2">
                        <div style="display: flex; gap: 18px; align-items: center;">
                            <label><input type="radio" name="tujuan_asesmen" value="Sertifikasi" required> Sertifikasi</label>
                            <label><input type="radio" name="tujuan_asesmen" value="Pengakuan Kompetensi Terkini"> PKT</label>
                            <label><input type="radio" name="tujuan_asesmen" value="Rekognisi Pembelajaran Lampau"> RPL</label>
                            <label><input type="radio" name="tujuan_asesmen" value="Lainnya"> Lainnya</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="group-caption">Daftar Unit Kompetensi sesuai kemasan:</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table class="competency-table">
                            <tr>
                                <th width="5%">No.</th>
                                <th width="20%">Kode Unit</th>
                                <th width="50%">Judul Unit</th>
                                <th width="25%">Standar Kompetensi Kerja</th>
                            </tr>
                            <?php for($i=1;$i<=8;$i++): ?>
                            <tr>
                                <td style="text-align:center;"><?php echo $i; ?>.</td>
                                <td></td>
                                <td></td>
                                <?php if($i==1): ?>
                                <td rowspan="8" valign="top"></td>
                                <?php endif; ?>
                            </tr>
                            <?php endfor; ?>
                        </table>
                    </td>
                </tr>
            </table>
        </form>

        <form action="prosesFR3.php" method="post" enctype="multipart/form-data">
            <table class="requirement-table">
                <tr>
                    <td colspan="4" class="section-title">
                        Bagian 3: Bukti Kelengkapan Pemohon
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="group-caption">3.1 Bukti Persyaratan Dasar Pemohon</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table class="requirement-table">
                            <tr>
                                <th>No.</th>
                                <th>Bukti Persyaratan Dasar</th>
                                <th>Ada</th>
                                <th>Tidak Ada</th>
                                <th>Memenuhi Syarat</th>
                                <th>Tidak Memenuhi Syarat</th>
                            </tr>
                            <tr>
                                <td style="text-align:center;">1.</td>
                                <td>Copy Raport SMK pada Konsentrasi Keahlian Rekayasa Perangkat Lunak semester 1 s.d 5 yang telah menyelesaikan mata pelajaran berisi unit kompetensi yang diajukan</td>
                                <td><input type="radio" name="bukti_adm_1" value="Ada" required> Ada</td>
                                <td><input type="radio" name="bukti_adm_1" value="Tidak Ada"> Tidak Ada</td>
                                <td><input type="radio" name="bukti_adm_1" value="Memenuhi Syarat"> Memenuhi</td>
                                <td><input type="radio" name="bukti_adm_1" value="Tidak Memenuhi Syarat"> Tidak Memenuhi</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">2.</td>
                                <td>Copy sertifikat/surat keterangan Praktik Kerja Lapangan (PKL) pada rekayasa perangkat lunak</td>
                                <td><input type="radio" name="bukti_adm_2" value="Ada" required> Ada</td>
                                <td><input type="radio" name="bukti_adm_2" value="Tidak Ada"> Tidak Ada</td>
                                <td><input type="radio" name="bukti_adm_2" value="Memenuhi Syarat"> Memenuhi</td>
                                <td><input type="radio" name="bukti_adm_2" value="Tidak Memenuhi Syarat"> Tidak Memenuhi</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table class="requirement-table">
                            <tr>
                                <th>No.</th>
                                <th>Bukti Administratif</th>
                                <th>Ada</th>
                                <th>Tidak Ada</th>
                                <th>Memenuhi Syarat</th>
                                <th>Tidak Memenuhi Syarat</th>
                            </tr>
                            <tr>
                                <td style="text-align:center;">1.</td>
                                <td>Foto Kopi KTP/Kartu Pelajar</td>
                                <td><input type="radio" name="bukti_adm_3" value="Ada" required> Ada</td>
                                <td><input type="radio" name="bukti_adm_3" value="Tidak Ada"> Tidak Ada</td>
                                <td><input type="radio" name="bukti_adm_3" value="Memenuhi Syarat"> Memenuhi</td>
                                <td><input type="radio" name="bukti_adm_3" value="Tidak Memenuhi Syarat"> Tidak Memenuhi</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;">2.</td>
                                <td>Pas foto 3x4 2 lembar dengan background merah</td>
                                <td><input type="radio" name="bukti_adm_4" value="Ada" required> Ada</td>
                                <td><input type="radio" name="bukti_adm_4" value="Tidak Ada"> Tidak Ada</td>
                                <td><input type="radio" name="bukti_adm_4" value="Memenuhi Syarat"> Memenuhi</td>
                                <td><input type="radio" name="bukti_adm_4" value="Tidak Memenuhi Syarat"> Tidak Memenuhi</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="mt-2 mb-2">
                        <b>Rekomendasi (diisi oleh LSP):</b><br>
                        <span>Berdasarkan ketentuan persyaratan dasar, maka pemohon:</span>
                        <div style="margin-top:8px;">
                          <span style="font-weight:bold;">Diterima</span> / <span style="font-weight:bold;">Tidak diterima</span> <span class="form-note">*) sebagai peserta sertifikasi</span>
                        </div>
                        <i class="form-note">*) coret yang tidak sesuai</i>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="signature-table-td">
                        <b>Pemohon/Kandidat:</b><br><br>
                        <label for="nama_pemohon" class="form-label">Nama:</label>
                        <input type="text" name="nama_pemohon" class="form-control" placeholder="Nama"><br>
                        <b>Tanda tangan/tanggal</b> <input type="date" name="tanggal_pemohon" class="input-signature"><br>
                        <input type="file" id="ttd_file" name="ttd_file" accept="image/*" onchange="previewImage(event)" class="input-signature">
                        <br>
                        <img id="preview_ttd" class="ttd-preview" src="#" alt="Preview Tanda Tangan">
                        <script>
                        function previewImage(event) {
                            var input = event.target;
                            var preview = document.getElementById('preview_ttd');
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    preview.src = e.target.result;
                                    preview.style.display = 'block';
                                }
                                reader.readAsDataURL(input.files[0]);
                            } else {
                                preview.src = '#';
                                preview.style.display = 'none';
                            }
                        }
                        </script>
                        <br><br>
                        <label for="catatan_pemohon" class="form-label">Catatan:</label>
                        <textarea name="catatan_pemohon" class="form-control" placeholder="Catatan"></textarea>
                    </td>
                    <td colspan="2" class="signature-table-td">
                        <b>Admin LSP:</b><br><br>
                        <span>Nama: <b>AGIL TRI ANGGORO</b></span><br><br>
                        <b>Tanda tangan/Tanggal</b> <input type="date" name="tanggal_admin" class="input-signature"><br>
                        <input type="file" id="foto_admin" name="foto_admin" accept="image/*" onchange="previewAdminPhoto(event)" class="input-signature">
                        <br>
                        <img id="preview_foto_admin" class="ttd-preview" src="#" alt="Preview Foto Admin">
                        <script>
                        function previewAdminPhoto(event) {
                            var input = event.target;
                            var preview = document.getElementById('preview_foto_admin');
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    preview.src = e.target.result;
                                    preview.style.display = 'block';
                                }
                                reader.readAsDataURL(input.files[0]);
                            } else {
                                preview.src = '#';
                                preview.style.display = 'none';
                            }
                        }
                        </script>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>