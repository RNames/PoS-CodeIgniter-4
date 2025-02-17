<?= $this->include('petugas/templates/header') ?>
<?= $this->include('petugas/templates/sidebar') ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="container p-5 pt-3 mb-3 mr-5 bg-white border rounded">

    <div class="row">
        <div class="col-2">
            <a href="<?= base_url('petugas/pengaturan-member') ?>" class="btn btn-secondary"><i class="fas fa-fw fa-angle-left"></i>Kembali</a>
        </div>
        <div class="col-8">
            <h2 class="text-center">Tambah Member</h2>
        </div>
    </div>

    <form id="memberForm" action="<?= base_url('petugas/pengaturan-member/store') ?>" method="post">

        <div class="form-group">
            <label>Tipe Member</label>
            <select name="tipe_member" id="tipe_member" class="form-control">
                <option value="" selected disabled>--Pilih Tipe--</option>
                <option value="1">Tipe 1</option>
                <option value="2">Tipe 2</option>
                <?php if (!$memberTipe3Exists): ?>
                    <option value="3">Tipe 3</option>
                <?php endif; ?>
            </select>

        </div>

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nm_member" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>No HP</label>
            <input type="text" name="no_hp" id="no_hp" class="form-control" required>
            <small class="text-danger d-none" id="no_hp_error">Nomor HP Tidak Valid</small>
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required></textarea>
        </div>

        <div class="form-group" id="poin_group">
            <label>Poin</label>
            <input type="number" name="poin" id="poin" class="form-control" required>
        </div>


        <button type="button" class="btn btn-block btn-success" id="submitBtn">Simpan</button>
    </form>
</div>

<script>
    document.getElementById('submitBtn').addEventListener('click', function(e) {
        let noHpInput = document.getElementById('no_hp');
        let noHpError = document.getElementById('no_hp_error');
        let noHpValue = noHpInput.value.trim();
        let tipeMember = document.getElementById('tipe_member');
        let tipeMemberValue = tipeMember.value;
        let poinInput = document.getElementById('poin').value.trim();
        let emailInput = document.querySelector('input[name="email"]');
        let alamatInput = document.querySelector('textarea[name="alamat"]');

        let inputs = document.querySelectorAll('#memberForm input, #memberForm textarea');
        let isValid = true;

        // Cek apakah ada input yang kosong (hanya untuk Tipe 1 dan 2)
        if (tipeMemberValue !== "3") {
            inputs.forEach(function(input) {
                if (input.hasAttribute('required') && input.value.trim() === '') {
                    isValid = false;
                    input.classList.add('is-invalid'); // Tambahkan efek error pada input
                } else {
                    input.classList.remove('is-invalid');
                }
            });
        }

        // Cek apakah tipe member belum dipilih
        if (tipeMemberValue === "") {
            isValid = false;
            tipeMember.classList.add('is-invalid');
        } else {
            tipeMember.classList.remove('is-invalid');
        }

        // Jika ada input kosong atau tipe member belum dipilih, tampilkan peringatan
        if (!isValid) {
            Swal.fire({
                title: "Peringatan!",
                text: "Data Harus Diisi!",
                icon: "error",
                confirmButtonColor: "#d33",
                confirmButtonText: "OK"
            });
            return;
        }

        // Validasi No HP (hanya untuk Tipe 1 dan 2)
        let regex = /^08\d{8,11}$/;
        if (tipeMemberValue !== "3" && !regex.test(noHpValue)) {
            noHpError.classList.remove('d-none');
            noHpInput.classList.add('is-invalid');
            return;
        } else {
            noHpError.classList.add('d-none');
            noHpInput.classList.remove('is-invalid');
        }

        // Jika Tipe 3, set nilai default
        if (tipeMemberValue === "3") {
            noHpInput.value = "-";
            emailInput.value = "-";
            alamatInput.value = "-";
            document.getElementById('poin').value = "-";
        }

        Swal.fire({
            title: "Konfirmasi",
            text: "Apakah Anda yakin ingin menyimpan data?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Simpan!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('memberForm').submit();
            }
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        let tipeMember = document.getElementById('tipe_member');
        let poinGroup = document.getElementById('poin_group');
        let poinInput = document.getElementById('poin');
        let formGroups = document.querySelectorAll('.form-group:not(:first-child)'); // Semua kecuali Tipe Member
        let emailInput = document.querySelector('input[name="email"]');
        let noHpInput = document.getElementById('no_hp');
        let alamatInput = document.querySelector('textarea[name="alamat"]');

        function toggleForm() {
            if (tipeMember.value === "") {
                // Sembunyikan semua form kecuali tipe member
                formGroups.forEach(group => group.style.display = "none");
            } else {
                // Tampilkan semua form
                formGroups.forEach(group => group.style.display = "block");

                // Jika Tipe Member = 3, sembunyikan alamat, email, no hp dan set default "-"
                if (tipeMember.value === "3") {
                    emailInput.value = "-";
                    noHpInput.value = "-";
                    alamatInput.value = "-";

                    emailInput.closest('.form-group').style.display = "none";
                    noHpInput.closest('.form-group').style.display = "none";
                    alamatInput.closest('.form-group').style.display = "none";

                    poinGroup.style.display = "none";
                    poinInput.value = "0"; // Set nilai poin ke 0 jika tipe 3 dipilih
                } else {
                    emailInput.closest('.form-group').style.display = "block";
                    noHpInput.closest('.form-group').style.display = "block";
                    alamatInput.closest('.form-group').style.display = "block";

                    poinGroup.style.display = "block";
                }
            }
        }

        // Jalankan saat halaman dimuat
        toggleForm();

        // Event saat tipe member berubah
        tipeMember.addEventListener("change", toggleForm);
    });
</script>

<?= $this->include('petugas/templates/footer') ?>