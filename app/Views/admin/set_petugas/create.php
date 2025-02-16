<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>

<div class="container p-5 pt-4 pb-4 mb-3 mr-5 bg-white border rounded">
    <div class="row">
        <div class="col-2">
            <a href="<?= base_url('owner/pengaturan-petugas') ?>" class="btn btn-secondary"><i class="fas fa-fw fa-angle-left"></i>Kembali</a>
        </div>
        <div class="col-8">
            <h2 class="text-center">Tambah Petugas</h2>
        </div>
    </div>

    <form action="<?= base_url('owner/pengaturan-petugas/store') ?>" method="post" onsubmit="return validatePasswords()">
        <div class="row">
            <div class="form-group col">
                <label>Nama</label>
                <input type="text" name="nm_petugas" class="form-control" required>
            </div>
            <div class="form-group col">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
        </div>
        <div class="form-group position-relative">
            <label>Password</label>
            <div class="input-group">
                <input type="password" id="password" name="password" class="form-control" required onkeyup="checkPasswordStrength()">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', 'togglePasswordIcon1')">
                    <i id="togglePasswordIcon1" class="fas fa-eye"></i>
                </button>
            </div>
            <ul class="text-muted mt-2" id="passwordRequirements">
                <li id="length" class="invalid">Minimal 8 karakter</li>
                <li id="uppercase" class="invalid">Mengandung huruf besar</li>
                <li id="lowercase" class="invalid">Mengandung huruf kecil</li>
                <li id="number" class="invalid">Mengandung angka</li>
                <li id="specialChar" class="invalid">Mengandung karakter spesial (@$!%*?&)</li>
            </ul>
        </div>
        <div class="form-group position-relative">
            <label>Konfirmasi Password</label>
            <div class="input-group">
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required onkeyup="checkConfirmPassword()">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('confirm_password', 'togglePasswordIcon2')">
                    <i id="togglePasswordIcon2" class="fas fa-eye"></i>
                </button>
            </div>
            <small id="confirmMessage" class="text-muted">Pastikan konfirmasi password cocok.</small>
        </div>
        <div class="form-group">
            <label>Role</label>
            <input type="text" name="roles" class="form-control" value="petugas" readonly>
        </div>
        <button type="submit" class="btn btn-block btn-success">Simpan</button>
    </form>
</div>

<script>
    function validatePasswords() {
        let password = document.getElementById("password").value;
        let confirmPassword = document.getElementById("confirm_password").value;
        let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        if (!passwordRegex.test(password)) {
            alert("Password belum memenuhi semua persyaratan.");
            return false;
        }

        if (password !== confirmPassword) {
            alert("Konfirmasi password tidak cocok.");
            return false;
        }

        return true;
    }

    function togglePassword(fieldId, iconId) {
        let passwordField = document.getElementById(fieldId);
        let icon = document.getElementById(iconId);

        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        } else {
            passwordField.type = "password";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        }
    }

    function checkPasswordStrength() {
        let password = document.getElementById("password").value;

        // Periksa masing-masing kriteria
        document.getElementById("length").className = password.length >= 8 ? "valid" : "invalid";
        document.getElementById("uppercase").className = /[A-Z]/.test(password) ? "valid" : "invalid";
        document.getElementById("lowercase").className = /[a-z]/.test(password) ? "valid" : "invalid";
        document.getElementById("number").className = /\d/.test(password) ? "valid" : "invalid";
        document.getElementById("specialChar").className = /[@$!%*?&]/.test(password) ? "valid" : "invalid";
    }

    function checkConfirmPassword() {
        let password = document.getElementById("password").value;
        let confirmPassword = document.getElementById("confirm_password").value;
        let confirmMessage = document.getElementById("confirmMessage");

        if (password === confirmPassword && confirmPassword !== "") {
            confirmMessage.innerHTML = "✔ Password cocok";
            confirmMessage.style.color = "green";
        } else {
            confirmMessage.innerHTML = "❌ Password tidak cocok";
            confirmMessage.style.color = "red";
        }
    }
</script>

<style>
    .valid {
        color: green;
    }

    .invalid {
        color: red;
        font-weight: bold;
    }
</style>

<?= $this->include('admin/templates/footer') ?>