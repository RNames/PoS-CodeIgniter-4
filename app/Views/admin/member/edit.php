<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="container p-5 pt-3 mb-3 mr-5 bg-white border rounded">

    <div class="row">
        <div class="col-2">
            <a href="<?= base_url('owner/pengaturan-member') ?>" class="btn btn-secondary"><i class="fas fa-fw fa-angle-left"></i>Kembali</a>
        </div>
        <div class="col-8">
            <h2 class="text-center">Edit Member</h2>
        </div>
    </div>

    <form id="memberForm" action="<?= base_url('owner/pengaturan-member/update/' . $member['id']) ?>" method="post">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nm_member" class="form-control" value="<?= $member['nm_member'] ?>" required>
        </div>
        <div class="form-group hidden-fields">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= $member['email'] ?>" required>
        </div>
        <div class="form-group hidden-fields">
            <label>No HP</label>
            <input type="text" name="no_hp" id="no_hp" class="form-control" value="<?= $member['no_hp'] ?>" required>
            <small class="text-danger d-none" id="no_hp_error">Nomor HP Tidak Valid.</small>
        </div>
        <div class="form-group hidden-fields">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required><?= $member['alamat'] ?></textarea>
        </div>
        <div class="form-group hidden-fields">
            <label>Poin</label>
            <input type="number" name="poin" class="form-control" value="<?= $member['poin'] ?>" required>
        </div>

        <div class="form-group">
            <label>Tipe Member</label>
            <select name="tipe_member" id="tipe_member" class="form-control" required>
                <option value="1" <?= ($member['tipe_member'] == 1) ? 'selected' : '' ?>>Tipe 1</option>
                <option value="2" <?= ($member['tipe_member'] == 2) ? 'selected' : '' ?>>Tipe 2</option>
                <?php if (!$disableTipe3): ?>
                    <option value="3" <?= ($member['tipe_member'] == 3) ? 'selected' : '' ?>>Tipe 3</option>
                <?php endif; ?>
            </select>

        </div>

        <button type="button" id="submitBtn" class="btn btn-block btn-success">Update</button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let tipeMemberSelect = document.getElementById("tipe_member");
        let hiddenFields = document.querySelectorAll(".hidden-fields");

        function toggleFields() {
            if (tipeMemberSelect.value === "3") {
                hiddenFields.forEach(field => field.style.display = "none");
            } else {
                hiddenFields.forEach(field => field.style.display = "block");
            }
        }

        tipeMemberSelect.addEventListener("change", toggleFields);
        toggleFields(); // Jalankan saat halaman pertama kali dimuat
    });

    document.getElementById('submitBtn').addEventListener('click', function() {
        let noHpInput = document.getElementById('no_hp');
        let noHpError = document.getElementById('no_hp_error');
        let noHpValue = noHpInput ? noHpInput.value.trim() : "";
        let tipeMemberValue = document.getElementById("tipe_member").value;
        let regex = /^08\d{8,11}$/;

        // Validasi No HP hanya jika tipe member bukan 3
        if (tipeMemberValue !== "3") {
            if (!regex.test(noHpValue)) {
                noHpError.classList.remove('d-none');
                noHpInput.classList.add('is-invalid');
                return;
            } else {
                noHpError.classList.add('d-none');
                noHpInput.classList.remove('is-invalid');
            }
        }

        Swal.fire({
            title: "Konfirmasi",
            text: "Apakah Anda yakin ingin menyimpan perubahan?",
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
</script>


<?= $this->include('admin/templates/footer') ?>