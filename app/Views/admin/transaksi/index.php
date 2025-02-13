<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container">
    <h2>Transaksi</h2>
    <form id="transaksiForm" action="<?= base_url('owner/transaksi/proses') ?>" method="post">
        <input type="hidden" name="tipe_member" id="tipe_member">

        <div class="form-group">
            <label>Pilih Member</label>
            <select name="id_member" id="id_member" class="form-select single-select" required>
                <option value="">-- Pilih Member --</option>

                <?php foreach ($members as $member) : ?>
                    <?php if ($member['tipe_member'] == 3) : ?>
                        <option value="<?= $member['id'] ?>" data-type="<?= $member['tipe_member'] ?>">
                            Tipe <?= $member['tipe_member'] ?> | <?= $member['nm_member'] ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php foreach ($members as $member) : ?>
                    <?php if ($member['tipe_member'] != 3) : ?>
                        <option value="<?= $member['id'] ?>" data-type="<?= $member['tipe_member'] ?>">
                            Tipe <?= $member['tipe_member'] ?> | <?= $member['nm_member'] ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Cari Barang</label>
            <select id="searchBarang" class="form-select single-select">
                <option value="">-- Cari Barang (Nama/Kode) --</option>
                <?php foreach ($barangs as $barang) : ?>
                    <option value="<?= $barang['id'] ?>"
                        data-nama="<?= $barang['nama_barang'] ?>"
                        data-kode="<?= $barang['kode_barang'] ?>"
                        data-harga1="<?= $barang['harga_jual_1'] ?>"
                        data-harga2="<?= $barang['harga_jual_2'] ?>"
                        data-harga3="<?= $barang['harga_jual_3'] ?>"
                        data-stok="<?= $barang['total_stok'] ?>">
                        <?= $barang['kode_barang'] ?> - <?= $barang['nama_barang'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="barangList">
            </tbody>
        </table>

        <div class="form-group">
            <label>Total Harga (Sebelum Pajak)</label>
            <input type="text" id="totalSebelumPajak" class="form-control" readonly>
        </div>

        <div class="form-group">
            <label>PPN (12%)</label>
            <input type="text" id="ppn" class="form-control" readonly>
        </div>

        <div class="form-group">
            <label>Total Setelah PPN</label>
            <input type="text" id="totalSetelahPajak" class="form-control" readonly>
        </div>

        <div class="form-group">
            <label>Diskon (%)</label>
            <input type="number" name="diskon" id="diskon" class="form-control" min="0" max="100" value="0">
        </div>

        <div class="form-group">
            <label>Total Akhir</label>
            <input type="text" id="totalAkhir" class="form-control" readonly>
        </div>

        <div class="form-group">
            <label>Bayar</label>
            <input type="number" name="total_bayar" id="totalBayar" class="form-control" required>
            <div id="warningText" style="color: red; font-weight: bold; display: none;">
                Uang masih kurang!
            </div>
        </div>

        <div class="form-group">
            <label>Kembalian</label>
            <input type="text" id="kembalian" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-success">Simpan Transaksi</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('.single-select').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: "Pilih Member"
        });

        $('#searchBarang').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: "Cari Barang"
        });

        $('#searchBarang').on('change', function() {
            let selectedOption = $(this).find(':selected');
            let barangId = selectedOption.val();
            let barangNama = selectedOption.data('nama');
            let barangKode = selectedOption.data('kode');
            let stok = selectedOption.data('stok');

            let tipeMember = $("#id_member").find(':selected').data('type');
            let harga = selectedOption.data('harga' + tipeMember);

            if (!barangId) return;

            if ($("#barangList tr[data-id='" + barangId + "']").length) {
                Swal.fire({
                    icon: "warning",
                    title: "Barang sudah dipilih!",
                    text: "Silakan tambahkan jumlahnya langsung di tabel."
                });
                return;
            }

            let newRow = `
                <tr data-id="${barangId}">
                    <td>${barangKode} - ${barangNama}</td>
                    <td>${stok}</td>
                    <td class="harga-barang" data-harga="${harga}">${formatRupiah(harga)}</td>
                    <td>
                        <input type="number" name="jumlah[${barangId}]" class="form-control jumlah" min="1" max="${stok}" value="1">
                    </td>
                    <td class="total-harga">${formatRupiah(harga)}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-barang">Hapus</button>
                    </td>
                </tr>
            `;

            $("#barangList").append(newRow);
            hitungTotal();
        });

        $(document).on("input", ".jumlah", function() {
            hitungTotal();
        });

        $(document).on("click", ".remove-barang", function() {
            $(this).closest("tr").remove();
            hitungTotal();
        });

        function hitungTotal() {
            let total = 0;
            $("#barangList tr").each(function() {
                let harga = parseInt($(this).find(".harga-barang").data("harga")) || 0;
                let jumlah = parseInt($(this).find(".jumlah").val()) || 0;
                let subtotal = harga * jumlah;
                $(this).find(".total-harga").text(formatRupiah(subtotal));
                total += subtotal;
            });

            let ppn = Math.floor(total * 0.12);
            let totalSetelahPajak = total + ppn;
            let diskon = Math.floor((totalSetelahPajak * ($("#diskon").val() || 0)) / 100);
            let totalAkhir = totalSetelahPajak - diskon;

            $("#totalSebelumPajak").val(formatRupiah(total));
            $("#ppn").val(formatRupiah(ppn));
            $("#totalSetelahPajak").val(formatRupiah(totalSetelahPajak));
            $("#totalAkhir").val(formatRupiah(totalAkhir));
        }

        function formatRupiah(angka) {
            return "Rp. " + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    });
</script>

<?= $this->include('admin/templates/footer') ?>
