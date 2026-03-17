<div class="wrapper auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Sign Up</h1>
            <p style="color: var(--so-gray-500); font-size: 14px;">Buat akun baru untuk bergabung</p>
        </div>

        <form action="<?= BASEURL; ?>/index.php?url=Auth/prosesRegister" method="post">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-input" placeholder="Masukkan nama..." required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-input" placeholder="email@sekolah.com" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-input" placeholder="********" required>
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" id="role-select" class="form-input" required style="background: white;">
                    <option value="siswa">Siswa</option>
                    <option value="guru">Guru</option>
                </select>
            </div>

            <div class="form-group" id="kelas-group">
                <label>Kelas</label>
                <select name="kelas" id="kelas-input" class="form-input" style="background: white;">
                    <option value="">-- Pilih Kelas --</option>
                    <?php
                    $tingkat = ['10', '11', '12'];
                    $jurusan = ['BID', 'AKL', 'TKJ'];
                    $nomor = ['1', '2'];

                    foreach ($tingkat as $t) {
                        foreach ($jurusan as $j) {
                            foreach ($nomor as $n) {
                                $nama_kelas = "$t $j $n";
                                echo "<option value='$nama_kelas'>$nama_kelas</option>";
                            }
                        }
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn-auth btn-signup">Register</button>
        </form>

        <div class="auth-footer" style="position: relative; z-index: 999; margin-top: 20px;">
            Sudah punya akun?
            <a href="<?= BASEURL; ?>/index.php?url=Auth" 
               style="color: #007bff; font-weight: bold; text-decoration: underline;">Login di sini</a>
        </div>
    </div>
</div>

<script>
    // Script agar input kelas hanya muncul jika memilih role 'siswa'
    const roleSelect = document.getElementById('role-select');
    const kelasGroup = document.getElementById('kelas-group');
    const kelasInput = document.getElementById('kelas-input');

    if(roleSelect) {
        roleSelect.addEventListener('change', function () {
            if (this.value === 'siswa') {
                kelasGroup.style.display = 'block';
                kelasInput.setAttribute('required', 'required');
            } else {
                kelasGroup.style.display = 'none';
                kelasInput.removeAttribute('required');
                kelasInput.value = ''; 
            }
        });
    }
</script>