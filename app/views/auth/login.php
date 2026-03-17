<div class="wrapper auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Login</h1>
            <p style="color: var(--so-gray-500); font-size: 14px;">Masuk ke portal diskusi materi</p>
        </div>

        <form action="<?= BASEURL; ?>/index.php?url=Auth/prosesLogin" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-input" placeholder="Masukkan email..." required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-input" placeholder="********" required>
            </div>

            <button type="submit" class="btn-auth btn-signup">Masuk Sekarang</button>
        </form>

        <div class="auth-footer" style="position: relative; z-index: 999; margin-top: 20px;">
            Belum punya akun?
            <a href="<?= BASEURL; ?>/index.php?url=Auth/signup" 
               style="color: #007bff; font-weight: bold; text-decoration: underline;">Daftar di sini</a>
        </div>
    </div>
</div>