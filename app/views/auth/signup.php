<div class="wrapper auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Sign Up</h1>
            <p style="color: var(--so-gray-500); font-size: 14px;">Buat akun siswa untuk bergabung di forum</p>
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

            <input type="hidden" name="role" value="siswa">

            <div class="form-group" id="kelas-group">
                <label>Kelas</label>
                <select name="kelas" id="kelas-input" class="form-input" required style="background: white;">
                    <option value="">-- Pilih Kelas Anda --</option>

                    <optgroup label="--- KELAS X ---">
                        <option value="X TKJ 1">📍 X TKJ 1</option>
                        <option value="X TKJ 2">📍 X TKJ 2</option>
                        <option value="X TKJ 3">📍 X TKJ 3</option>
                        <option value="X AKL 1">📍 X AKL 1</option>
                        <option value="X AKL 2">📍 X AKL 2</option>
                        <option value="X AKL 3">📍 X AKL 3</option>
                        <option value="X BID 1">📍 X BID 1</option>
                        <option value="X BID 2">📍 X BID 2</option>
                        <option value="X BID 3">📍 X BID 3</option>
                    </optgroup>

                    <optgroup label="--- KELAS XI ---">
                        <option value="XI TKJ 1">📍 XI TKJ 1</option>
                        <option value="XI TKJ 2">📍 XI TKJ 2</option>
                        <option value="XI TKJ 3">📍 XI TKJ 3</option>
                        <option value="XI AKL 1">📍 XI AKL 1</option>
                        <option value="XI AKL 2">📍 XI AKL 2</option>
                        <option value="XI AKL 3">📍 XI AKL 3</option>
                        <option value="XI BID 1">📍 XI BID 1</option>
                        <option value="XI BID 2">📍 XI BID 2</option>
                        <option value="XI BID 3">📍 XI BID 3</option>
                    </optgroup>

                    <optgroup label="--- KELAS XII ---">
                        <option value="XII TKJ 1">📍 XII TKJ 1</option>
                        <option value="XII TKJ 2">📍 XII TKJ 2</option>
                        <option value="XII TKJ 3">📍 XII TKJ 3</option>
                        <option value="XII AKL 1">📍 XII AKL 1</option>
                        <option value="XII AKL 2">📍 XII AKL 2</option>
                        <option value="XII AKL 3">📍 XII AKL 3</option>
                        <option value="XII BID 1">📍 XII BID 1</option>
                        <option value="XII BID 2">📍 XII BID 2</option>
                        <option value="XII BID 3">📍 XII BID 3</option>
                    </optgroup>
                </select>
            </div>

            <button type="submit" class="btn-auth btn-signup">Register Akun Siswa</button>
        </form>

        <div class="auth-footer" style="position: relative; z-index: 999; margin-top: 20px;">
            Sudah punya akun?
            <a href="<?= BASEURL; ?>/index.php?url=Auth"
                style="color: #007bff; font-weight: bold; text-decoration: underline;">Login di sini</a>
        </div>
    </div>
</div>