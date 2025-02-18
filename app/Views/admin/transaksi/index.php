<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>


<div class="container p-5 pt-4 mb-3 mr-5 bg-white border rounded">
    <form id="transaksiForm" action="<?= base_url('owner/transaksi/proses') ?>" method="post">
        <input type="hidden" name="tipe_member" id="tipe_member">

        <div class="form-group container mb-4 row row-cols-2 g-3">
            <div class="form-group col">
                <label>Pilih Member</label>
                <select name="id_member" id="id_member" class="form-select" required>
                    <option value="" disabled selected>-- Pilih Member --</option>
                    <optgroup label="Non Member">
                        <?php foreach ($members as $member) : ?>
                            <?php if ($member['tipe_member'] == 3) : ?>
                                <option value="<?= $member['id'] ?>" data-type="<?= $member['tipe_member'] ?>">
                                    Tipe <?= $member['tipe_member'] ?> | <?= $member['nm_member'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </optgroup>
                    <optgroup label="Member 1 & 2">
                        <?php foreach ($members as $member) : ?>
                            <?php if ($member['tipe_member'] != 3) : ?>
                                <option value="<?= $member['id'] ?>" data-type="<?= $member['tipe_member'] ?>" data-poin="<?= $member['poin'] ?>">
                                    Tipe <?= $member['tipe_member'] ?> | <?= $member['nm_member'] ?> (Poin: <?= $member['poin'] ?>)
                                </option>

                            <?php endif; ?>
                        <?php endforeach; ?>
                    </optgroup>
                </select>
            </div>

            <div class="form-group">
                <label>Pilih Barang</label>
                <select id="pilihBarang" class="form-select">
                    <option value="" disabled selected>-- Pilih Barang --</option>
                    <?php foreach ($barangs as $barang) : ?>
                        <option value="<?= $barang['id'] ?>"
                            data-nama="<?= $barang['nama_barang'] ?>"
                            data-satuan="<?= $barang['satuan'] ?>"
                            data-stok="<?= $barang['total_stok'] ?>"
                            data-harga1="<?= $barang['harga_jual_1'] ?>"
                            data-harga2="<?= $barang['harga_jual_2'] ?>"
                            data-harga3="<?= $barang['harga_jual_3'] ?>">
                            <?= $barang['nama_barang'] ?> (Stok: <?= $barang['total_stok'] ?>) (<?= $barang['satuan'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="container row">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="barangList">
                    <!-- Barang yang dipilih akan ditambahkan di sini -->
                </tbody>
            </table>
        </div>

        <div class="container mt-4 mb-4 row row-cols-2 g-3">
            <div class="form-group col">
                <label>Diskon (%)</label>
                <input type="number" name="diskon" id="diskon" class="form-control" min="0" max="100" value="0">
            </div>

            <div class="form-group col">
                <label>Total Harga Barang</label>
                <input type="text" id="totalHargaBarang" class="form-control" readonly>
            </div>

            <div class="form-group col">
                <label>Diskon (Rp)</label>
                <input type="text" id="diskonRupiah" class="form-control" readonly>
            </div>

            <div class="form-group col">
                <label>Total Setelah Diskon</label>
                <input type="text" id="totalSetelahDiskon" class="form-control" readonly>
            </div>

            <div class="form-group col">
                <label>PPN (12%)</label>
                <input type="text" id="ppn" class="form-control" readonly>
            </div>

            <div class="form-group col">
                <label>Total Akhir</label>
                <input type="text" id="totalAkhir" class="form-control" readonly>
            </div>

            <div class="form-group col">
                <label>Gunakan Poin</label>
                <input type="number" name="poin_digunakan" id="poinDigunakan" class="form-control" min="0" value="0">
            </div>

            <div class="form-group col">
                <label>Total Poin</label>
                <input type="text" id="totalPoin" class="form-control" readonly>
            </div>

            <div class="form-group col">
                <label>Bayar</label>
                <input type="number" name="total_bayar" id="totalBayar" class="form-control" required>
                <div id="warningText" style="color: red; font-weight: bold; display: none;">
                    Uang masih kurang!
                </div>
            </div>

            <div class="form-group col">
                <label>Kembalian</label>
                <input type="text" id="kembalian" class="form-control" readonly>
            </div>

        </div>

        <button type="button" id="btnSimpanTransaksi" class="btn btn-success btn-block">Simpan Transaksi</button>

    </form>
</div>

<script>
    $(document).ready(function() {
        $('#id_member, #pilihBarang').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        $('#id_member').on('select2:select', function(e) {
            let selectedOption = e.params.data.element;
            let tipeMember = $(selectedOption).attr("data-type");
            $("#tipe_member").val(tipeMember);

            // Update harga barang dalam tabel
            $(".harga-barang").each(function() {
                let hargaBaru = $(this).attr("data-harga" + tipeMember);
                $(this).text(formatRupiah(hargaBaru));
                $(this).data("harga", hargaBaru);

                // Update total harga per barang
                let row = $(this).closest("tr");
                let jumlah = row.find(".jumlah").val();
                let totalBaru = jumlah * hargaBaru;
                row.find(".total-harga").text(formatRupiah(totalBaru));
            });

            hitungTotal();
        });

        $(document).ready(function() {
            $("#btnSimpanTransaksi").on("click", function(e) {
                let totalBayar = parseInt($("#totalBayar").val()) || 0;
                let totalAkhir = parseInt($("#totalAkhir").val().replace(/\D/g, "")) || 0;
                let jumlahBarang = $("#barangList tr").length;

                if (jumlahBarang === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Harap pilih minimal satu barang sebelum menyimpan transaksi!',
                    });
                    return;
                }

                if (totalBayar < totalAkhir) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Uang yang dibayarkan kurang!',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Transaksi',
                    text: "Apakah Anda yakin ingin menyelesaikan transaksi ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Selesaikan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#transaksiForm").submit();
                    }
                });
            });
        });

        $('#id_member').on('select2:select', function(e) {
            let selectedOption = e.params.data.element;
            let tipeMember = $(selectedOption).attr("data-type");
            let totalPoin = $(selectedOption).attr("data-poin") || 0; // Ambil poin dari data atribut
            $("#tipe_member").val(tipeMember);
            $("#totalPoin").val(totalPoin); // Tampilkan total poin

            // Update harga barang dalam tabel jika tipe member berubah
            $(".harga-barang").each(function() {
                let hargaBaru = $(this).attr("data-harga" + tipeMember);
                $(this).text(formatRupiah(hargaBaru));
                $(this).data("harga", hargaBaru);

                // Update total harga per barang
                let row = $(this).closest("tr");
                let jumlah = row.find(".jumlah").val();
                let totalBaru = jumlah * hargaBaru;
                row.find(".total-harga").text(formatRupiah(totalBaru));
            });

            hitungTotal();
        });

        $("#poinDigunakan").on("input", function() {
            let totalPoin = parseInt($("#totalPoin").val()) || 0;
            let poinDigunakan = parseInt($(this).val()) || 0;
            let totalAkhir = parseInt($("#totalAkhir").val().replace(/\D/g, "")) || 0;

            if (poinDigunakan > totalPoin) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Poin Tidak Cukup!',
                    text: 'Anda tidak memiliki cukup poin untuk digunakan!',
                });
                $(this).val(totalPoin);
                poinDigunakan = totalPoin;
            }

            if (poinDigunakan > totalAkhir) {
                $(this).val(totalAkhir);
                poinDigunakan = totalAkhir;
            }

            hitungTotal();
        });

        $("#pilihBarang").on("change", function() {
            let selected = $(this).find(":selected");
            let barangID = selected.val();
            let namaBarang = selected.attr("data-nama");
            let satuan = selected.attr("data-satuan");
            let stok = selected.attr("data-stok");
            let tipeMember = $("#tipe_member").val();
            let harga = selected.attr("data-harga" + tipeMember);

            if ($("#barang-" + barangID).length == 0) {
                $("#barangList").append(`
            <tr id="barang-${barangID}">
                <td>${namaBarang}</td>
                <td class="stok-barang">${stok} ${satuan}</td>
                <td class="harga-barang"
                    data-harga1="${selected.attr("data-harga1")}"
                    data-harga2="${selected.attr("data-harga2")}"
                    data-harga3="${selected.attr("data-harga3")}"
                    data-harga="${harga}">
                    ${formatRupiah(harga)}
                </td>
                <td>
                    <input type="number" name="jumlah[${barangID}]" class="form-control jumlah" min="0" max="${stok}" value="1">
                </td>
                <td class="total-harga">${formatRupiah(harga)}</td>
                <td><button type="button" class="btn btn-danger btn-sm hapus-barang" data-id="${barangID}">Hapus</button></td>
            </tr>
        `);
            }

            hitungTotal();
        });

        $(document).on("input", ".jumlah", function() {
            let row = $(this).closest("tr");
            let stok = parseInt(row.find(".stok-barang").text()) || 0;
            let jumlah = parseInt($(this).val()) || 0;

            if (jumlah < 0) {
                $(this).val(0);
                jumlah = 0;
            } else if (jumlah > stok) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Tidak Cukup!',
                    text: 'Jumlah yang dimasukkan melebihi stok tersedia!',
                });
                $(this).val(stok);
                jumlah = stok;
            }

            let harga = parseInt(row.find(".harga-barang").data("harga")) || 0;
            row.find(".total-harga").text(formatRupiah(harga * jumlah));

            hitungTotal();
        });


        $(document).on("click", ".hapus-barang", function() {
            let barangID = $(this).data("id");
            $("#barang-" + barangID).remove();
            hitungTotal();
        });


        $("#diskon").on("input", function() {
            hitungTotal();
        });


        $("#totalBayar").on("input", function() {
            hitungKembalian();
        });



        function hitungTotal() {
            let total = 0;
            $(".total-harga").each(function() {
                let harga = parseInt($(this).text().replace(/\D/g, "")) || 0;
                total += harga;
            });

            $("#totalHargaBarang").val(formatRupiah(total));

            let diskonPersen = parseInt($("#diskon").val()) || 0;
            let diskonRupiah = Math.floor((total * diskonPersen) / 100); // Menghindari desimal
            let totalSetelahDiskon = total - diskonRupiah;
            let ppn = Math.floor((totalSetelahDiskon * 12) / 100); // Menghindari desimal
            let totalAkhir = totalSetelahDiskon + ppn;

            let poinDigunakan = parseInt($("#poinDigunakan").val()) || 0;
            totalAkhir -= poinDigunakan; // Kurangi total akhir dengan poin

            if (totalAkhir < 0) totalAkhir = 0;

            $("#diskonRupiah").val(formatRupiah(diskonRupiah));
            $("#totalSetelahDiskon").val(formatRupiah(totalSetelahDiskon));
            $("#ppn").val(formatRupiah(ppn));
            $("#totalAkhir").val(formatRupiah(totalAkhir)); // Pastikan tetap menggunakan format Rupiah

            hitungKembalian();
        }



        function hitungKembalian() {
            let totalAkhir = parseInt($("#totalAkhir").val().replace(/\D/g, "")) || 0;
            let bayar = parseInt($("#totalBayar").val()) || 0;
            let kembalian = bayar - totalAkhir;

            if (kembalian < 0) {
                $("#kembalian").val("Rp. 0");
                $("#warningText").show();
                $(".btn-success").prop("disabled", true);
            } else {
                $("#kembalian").val(formatRupiah(kembalian));
                $("#warningText").hide();
                $(".btn-success").prop("disabled", false);
            }
        }


        function formatRupiah(angka) {
            return "Rp. " + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    });
</script>

<script>
    $(document).ready(function() {
        $("#btnSimpanTransaksi").on("click", function(e) {
            let totalBayar = parseInt($("#totalBayar").val()) || 0;
            let totalAkhir = parseInt($("#totalAkhir").val().replace(/\D/g, "")) || 0;

            if (totalBayar < totalAkhir) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Uang yang dibayarkan kurang!',
                });
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Transaksi',
                text: "Apakah Anda yakin ingin menyelesaikan transaksi ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Selesaikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#transaksiForm").submit();
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        <?php if (session()->getFlashdata('transaksi_berhasil')) : ?>
            Swal.fire({
                title: 'Transaksi Berhasil!',
                text: "<?= session()->getFlashdata('transaksi_berhasil')['message'] ?>",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#007bff',
                confirmButtonText: 'Cetak Nota',
                cancelButtonText: 'Tutup'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open('<?= base_url('owner/transaksi/cetak_nota/') . session()->getFlashdata('transaksi_berhasil')['id_transaksi'] ?>', '_blank');
                }
            });
        <?php endif; ?>
    });
</script>



<?= $this->include('admin/templates/footer') ?>