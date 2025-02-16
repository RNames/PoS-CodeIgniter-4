<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<div class="container p-5 pt-3 mb-3 mr-5 bg-white border rounded">
    <!-- Filter Form -->
    <form method="get" action="<?= base_url('owner/logs') ?>" class="mb-3" id="filter-form">
        <select class="form-select" id="filter-select" name="filter[]" multiple data-placeholder="Filter Logs">
            <?php
            $actions = ['login' => 'Login', 'logout' => 'Logout', 'tambah' => 'Tambah', 'edit' => 'Edit', 'hapus' => 'Hapus', 'restore' => 'Restore'];
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

    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center" id="pagination">
            <!-- Pagination Buttons -->
        </ul>
    </nav>

    <!-- Select2 & AJAX -->
    <script>
        $(document).ready(function() {
            $('#filter-select').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: "Filter Logs",
            });

            let currentPage = 1;
            let perPage = 10;

            function loadLogs(filters, page = 1) {
                $.ajax({
                    url: "<?= base_url('owner/logs/getFilteredLogs') ?>",
                    type: "GET",
                    data: {
                        filter: filters,
                        page: page,
                        perPage: perPage
                    },
                    dataType: "json",
                    success: function(response) {
                        let tableBody = "";
                        response.logs.forEach((log, index) => {
                            let actionColors = {
                                'login': 'bg-primary text-white',
                                'logout': 'bg-secondary text-white',
                                'tambah': 'bg-success text-white',
                                'edit': 'bg-warning text-dark',
                                'hapus': 'bg-danger text-white',
                                'restore': 'bg-info text-white',
                            };
                            let bgClass = actionColors[log.action] || 'bg-light';
                            let shortMsg = log.msg.split('<br>')[0];

                            tableBody += `
                            <tr>
                                <td>${(page - 1) * perPage + index + 1}</td>
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
                        renderPagination(response.totalPages, page);
                    }
                });
            }

            function renderPagination(totalPages, currentPage) {
                let paginationHtml = "";

                for (let i = 1; i <= totalPages; i++) {
                    let activeClass = i === currentPage ? "active" : "";
                    paginationHtml += `
                        <li class="page-item ${activeClass}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `;
                }

                $("#pagination").html(paginationHtml);
            }

            // Load logs pertama kali
            loadLogs($('#filter-select').val(), currentPage);

            // Auto update saat filter diubah
            $('#filter-select').on('change', function() {
                currentPage = 1;
                loadLogs($(this).val(), currentPage);
            });

            // Pagination click event
            $(document).on("click", ".page-link", function(e) {
                e.preventDefault();
                let page = $(this).data("page");
                if (page) {
                    currentPage = page;
                    loadLogs($('#filter-select').val(), currentPage);
                }
            });
        });
    </script>

</div>

<?= $this->include('admin/templates/footer') ?>