<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container">
    <h2>Transaksi</h2>
    <form id="transaksiForm" action="<?= base_url('owner/transaksi/proses') ?>" method="post">
        <input type="hidden" name="tipe_member" id="tipe_member">


        <div class="form-group">
            <label>Pilih Membesr</label>
            <select name="id_member" id="id_member" class="form-select single-select" required>
                <option value="">-- Pilih Member --</option>
                <?php foreach ($members as $member) : ?>
                    <option value="<?= $member['id'] ?>" data-type="<?= $member['tipe_member'] ?>">
                        <?= $member['nm_member'] ?> (Tipe <?= $member['tipe_member'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody id="barangList">
                <?php foreach ($barangs as $barang) : ?>
                    <tr>
                        <td><?= $barang['nama_barang'] ?></td>
                        <td>
                            <span class="harga-barang"
                                data-harga1="<?= $barang['harga_jual_1'] ?>"
                                data-harga2="<?= $barang['harga_jual_2'] ?>"
                                data-harga3="<?= $barang['harga_jual_3'] ?>">-
                            </span>
                        </td>
                        <td><input type="number" name="jumlah[<?= $barang['id'] ?>]" class="form-control jumlah" min="0" value="0"></td>
                        <td class="total-harga">0</td>
                    </tr>
                <?php endforeach; ?>
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
    document.getElementById("id_member").addEventListener("change", function() {
        let tipeMember = this.options[this.selectedIndex].getAttribute("data-type");
        document.getElementById("tipe_member").value = tipeMember;
        document.querySelectorAll(".harga-barang").forEach(function(el) {
            let harga = el.getAttribute("data-harga" + tipeMember);
            el.innerText = formatRupiah(harga);
        });
        hitungTotal(); // Recalculate total when member changes
    });

    document.querySelectorAll(".jumlah").forEach(function(input) {
        input.addEventListener("input", hitungTotal);
    });

    document.getElementById("diskon").addEventListener("input", function() {
        if (parseInt(this.value) > 100) {
            this.value = 100;
        }
        hitungTotal();
    });

    document.getElementById("totalBayar").addEventListener("input", function() {
        hitungTotal();
    });

    function hitungTotal() {
        let total = 0;
        document.querySelectorAll("#barangList tr").forEach(function(row) {
            let hargaText = row.querySelector(".harga-barang").innerText.replace(/\D/g, ""); // Remove non-numeric characters
            let harga = parseInt(hargaText) || 0;
            let jumlah = parseInt(row.querySelector(".jumlah").value) || 0;
            let subtotal = Math.floor(harga * jumlah);
            row.querySelector(".total-harga").innerText = formatRupiah(subtotal);
            total += subtotal;
        });

        let ppn = Math.floor(total * 0.12);
        let totalSetelahPajak = Math.floor(total + ppn);
        let diskonPersen = parseInt(document.getElementById("diskon").value) || 0;
        let diskon = Math.floor((totalSetelahPajak * diskonPersen) / 100);
        let totalAkhir = Math.floor(totalSetelahPajak - diskon);

        document.getElementById("totalSebelumPajak").value = formatRupiah(total);
        document.getElementById("ppn").value = formatRupiah(ppn);
        document.getElementById("totalSetelahPajak").value = formatRupiah(totalSetelahPajak);
        document.getElementById("totalAkhir").value = formatRupiah(totalAkhir);

        let totalBayar = parseInt(document.getElementById("totalBayar").value) || 0;
        let kembalian = totalBayar - totalAkhir;
        document.getElementById("kembalian").value = formatRupiah(kembalian);

        let warningText = document.getElementById("warningText");
        let submitButton = document.querySelector("button[type='submit']");

        if (totalBayar < totalAkhir) {
            warningText.style.display = "block"; // Show warning
            submitButton.disabled = true; // Disable submit button
        } else {
            warningText.style.display = "none"; // Hide warning
            submitButton.disabled = false; // Enable submit button
        }
    }

    function formatRupiah(angka) {
        let numberString = angka.toString().replace(/\D/g, "");
        let formatted = numberString.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        return "Rp. " + formatted;
    }
</script>

<script>
    $(document).ready(function() {
        $('.single-select').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: "Pilih Member"
        });

        $('.single-select').on('select2:select', function(e) {
            let selectedOption = e.params.data.element;
            let tipeMember = $(selectedOption).attr("data-type");

            console.log("Tipe Member Terpilih:", tipeMember); // Debugging

            if (tipeMember) {
                $("#tipe_member").val(tipeMember);
                $(".harga-barang").each(function() {
                    let harga = $(this).attr("data-harga" + tipeMember);
                    $(this).text(formatRupiah(harga));
                });
                hitungTotal();
            } else {
                console.warn("Tipe Member tidak ditemukan!");
            }
        });
    });
</script>



<?= $this->include('admin/templates/footer') ?>