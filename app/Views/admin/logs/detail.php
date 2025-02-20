<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container p-5 pt-3 mb-3 mr-5 bg-white border rounded">
    <div class="row mb-3 mt-3">
        <div class="col-2">
            <a href="<?= base_url('owner/logs') ?>" class="btn btn-secondary"><i class="fas fa-fw fa-angle-left"></i>Kembali</a>
        </div>
        <div class="col-8">
            <h2 class="text-center">Detail Riwayat Aktivitas</h2>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="fw-bold">Petugas: <?= $log['nm_petugas'] ?? 'Tidak Diketahui'; ?></h5>
            <p><strong>Aksi:</strong> <?= ucfirst($log['action']); ?> </span></p>
            <p><strong>Pesan:</strong> <?= $log['msg'] ?? 'Tidak Diketahui'; ?></p>
            <p><strong>Waktu:</strong> <?= date('d-m-Y H:i:s', strtotime($log['time'])); ?></p>
        </div>
    </div>

    <h5 class="mt-3">Perubahan Data</h5>

    <?php
    $oldData = json_decode($log['old_data'] ?? '{}', true);
    $newData = json_decode($log['new_data'] ?? 'null', true);

    // Jika newData null, anggap semua field dihapus
    if (is_null($newData)) {
        $changes = [];
        foreach ($oldData as $key => $value) {
            $changes[$key] = 'Dihapus';
        }
    } else {
        $changes = array_diff_assoc($newData, $oldData);
    }

    ?>

    <?php if (!empty($changes)): ?>
        <table class="table mt-3">
            <tr>
                <th>Kolom</th>
                <th>Data Lama</th>
                <th>Data Baru</th>
            </tr>
            <tbody>
                <?php foreach ($changes as $key => $newValue): ?>
                    <tr>
                        <td> <?= ucwords(str_replace('_', ' ', $key)); ?> </td>
                        <td> <?= $oldData[$key] ?? '-'; ?> </td>
                        <td class="text-success"> <?= $newValue ?? '-';; ?> </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted">Tidak ada perubahan data.</p>
    <?php endif; ?>


</div>

<?= $this->include('admin/templates/footer') ?>