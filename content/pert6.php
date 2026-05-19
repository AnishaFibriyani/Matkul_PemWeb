<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initialscale=1.0">
    <title>Praktikum Form HTML</title>
<style>
* {
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f2f4f8;
    margin: 0;
    padding: 0;
}

.container {
    width: 450px;
    margin: 40px auto;
    background-color: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}
h1 {
    text-align: center;
    margin-bottom: 10px;
    color: #222;
}

.deskripsi {
    text-align: center;
    color: #666;
    font-size: 14px;
    margin-bottom: 25px;
}
.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 6px;
    font-weight: bold;
    color: #333;
}

input,
select,
textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #bbb;
    border-radius: 6px;
    font-size: 14px;
}

input:focus,
select:focus,
textarea:focus {
    outline: none;
    border-color: #007bff;
}

.pilihan {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}

.pilihan input {
    width: auto;
    margin-right: 8px;
}

.pilihan label {
    margin: 0;
    font-weight: normal;
}

button {
    padding: 10px 16px;
    border: none;
    border-radius: 6px;
    background-color: #007bff;
    color: white;
    font-size: 14px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

.reset {
    background-color: #dc3545;
    margin-left: 8px;
}

.reset:hover {
    background-color: #a71d2a;
}
</style>
</head>
<body>

    <div class="container">
        <h1>Form Pendaftaran Mahasiswa</h1>
        <p class="deskripsi">
            Silakan isi data pendaftaran dengan lengkap dan benar.
        </p>

        <form action="#" method="post">

            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    placeholder="Masukkan nama lengkap"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="contoh@email.com"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    required
                >
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal Lahir</label>
                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    required
                >
            </div>

            <div class="form-group">
                <label for="jurusan">Jurusan</label>
                <select id="jurusan" name="jurusan" required>
                    <option value="">Pilih jurusan</option>
                    <option value="informatika">Teknik Informatika</option>
                    <option value="sistem-informasi">Sistem Informasi</option>
                    <option value="manajemen">Manajemen</option>
                    <option value="akuntansi">Akuntansi</option>
                </select>
            </div>

            <div class="form-group">
                <label>Jenis Kelamin</label>

                <div class="pilihan">
                    <input
                        type="radio"
                        id="laki"
                        name="jenis_kelamin"
                        value="Laki-laki"
                        required
                    >
                    <label for="laki">Laki-laki</label>
                </div>

                <div class="pilihan">
                    <input
                        type="radio"
                        id="perempuan"
                        name="jenis_kelamin"
                        value="Perempuan"
                    >
                    <label for="perempuan">Perempuan</label>
                </div>
            </div>

            <div class="form-group">
                <label>Minat Belajar</label>

                <div class="pilihan">
                    <input
                        type="checkbox"
                        id="html"
                        name="minat"
                        value="HTML"
                    >
                    <label for="html">HTML</label>
                </div>

                <div class="pilihan">
                    <input
                        type="checkbox"
                        id="css"
                        name="minat"
                        value="CSS"
                    >
                    <label for="css">CSS</label>
                </div>

                <div class="pilihan">
                    <input
                        type="checkbox"
                        id="javascript"
                        name="minat"
                        value="JavaScript"
                    >
                    <label for="javascript">JavaScript</label>
                </div>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea
                    id="alamat"
                    name="alamat"
                    rows="4"
                    placeholder="Masukkan alamat lengkap"
                    required
                ></textarea>
            </div>

            <div class="form-group">
                <button type="submit">Kirim Data</button>
                <button type="reset" class="reset">Reset</button>
            </div>

        </form>
    </div>
    
</body>
</html>