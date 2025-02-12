<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container">
    <h2>Log Aktivitas</h2>

    <!-- Filter Form -->
    <form method="get" action="<?= base_url('owner/logs') ?>" class="mb-3" id="filter-form">
        <select class="form-select" id="filter-select" name="filter[]" multiple data-placeholder="Filter Logs">
            <?php
            $actions = ['login' => 'Login', 'logout' => 'Logout', 'tambah' => 'Tambah', 'edit' => 'Edit', 'hapus' => 'Hapus'];
            $selectedFilters = isset($_GET['filter']) ? $_GET['filter'] : [];

            foreach ($actions as $key => $label) {
                $selected = in_array($key, $selectedFilters) ? 'selected' : '';
                echo "<option value='$key' $selected>$label</option>";
            }
            ?>
        </select>
    </form>

    <!-- Log Table -->
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Petugas</th>
                <th class="text-center">Aksi</th>
                <th>Pesan</th>
                <th class="text-center">Waktu</th>
                <th class="text-center">Opsi</th>
            </tr>
        </thead>
        <tbody id="log-table-body">
            <!-- Data akan diisi oleh AJAX -->
        </tbody>
    </table>

    <!-- Select2 & AJAX -->
    <script>
        $(document).ready(function() {
            $('#filter-select').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: "Filter Logs",
            });

            function loadLogs(filters) {
                $.ajax({
                    url: "<?= base_url('owner/logs/getFilteredLogs') ?>",
                    type: "GET",
                    data: {
                        filter: filters
                    },
                    dataType: "json",
                    success: function(response) {
                        let tableBody = "";
                        response.forEach((log, index) => {
                            let actionColors = {
                                'login': 'bg-primary text-white',
                                'logout': 'bg-secondary text-white',
                                'tambah': 'bg-success text-white',
                                'edit': 'bg-warning text-dark',
                                'hapus': 'bg-danger text-white'
                            };
                            let bgClass = actionColors[log.action] || 'bg-light';
                            let shortMsg = log.msg.split('<br>')[0];

                            tableBody += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${log.nm_petugas}</td>
                                <td class="text-center">
                                    <span class="badge ${bgClass}" style="font-size:14px">${log.action.charAt(0).toUpperCase() + log.action.slice(1)}</span>
                                </td>
                                <td>${shortMsg}</td>
                                <td class="text-center">${log.time}</td>
                                <td class="text-center">
                                    <a href="<?= base_url('owner/logs/detail/') ?>${log.id}" class="btn btn-info">
                                        <i class="fas fa-eye" style="font-size:20px"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        `;
                        });

                        $("#log-table-body").html(tableBody);
                    }
                });
            }

            // Load logs pertama kali
            loadLogs($('#filter-select').val());

            // Auto update saat filter diubah
            $('#filter-select').on('change', function() {
                loadLogs($(this).val());
            });
        });
    </script>

</div>

<?= $this->include('admin/templates/footer') ?>