<?php

require './config/config.php';

if (isset($_GET['tanggal_awal'])) {
    $tanggal_awal = $_GET['tanggal_awal'];
    $tanggal_akhir = $_GET['tanggal_akhir'];
    $jenis = $_GET['jenis_transaksi'];
    $kasir = $_GET['kasir'];
} else {
    $tanggal_awal = date('d-m-Y', strtotime('-1 days'));
    $tanggal_akhir = date('d-m-Y');
    $jenis = '';
    $kasir = '';
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css"
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css"
          integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.standalone.min.css"
          integrity="sha512-D5/oUZrMTZE/y4ldsD6UOeuPR4lwjLnfNMWkjC0pffPTCVlqzcHTNvkn3dhL7C0gYifHQJAIrRTASbMvLmpEug=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

</head>
<body>
<div class="container-fluid mt-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header mb-2">
                        <h2 class="fw-bold text-center mb-3">Datatables PHP PG</h2>
                        <form action="./data/serverside.php" method="GET" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Tanggal Awal -->
                                    <div class="mb-3 row">
                                        <label for="tanggal_awal" class="col-sm-3 col-form-label">Tanggal Awal</label>
                                        <div class="col-sm">
                                            <div class="input-group date">
                                                <input type="text" class="form-control" value="<?= $tanggal_awal; ?>"
                                                       name="tanggal_awal" id="tanggal_awal">
                                                <div class="input-group-addon">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End -->

                                    <!-- Tanggal Akhir -->
                                    <div class="mb-3 row">
                                        <label for="tanggal_akhir" class="col-sm-3 col-form-label">Tanggal Akhir</label>
                                        <div class="col-sm">
                                            <div class="input-group date">
                                                <input type="text" class="form-control" value="<?= $tanggal_akhir; ?>"
                                                       name="tanggal_akhir" id="tanggal_akhir">
                                                <div class="input-group-addon">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End -->

                                </div>
                                <div class="col-md-6">
                                    <!-- Jenis Transaksi -->
                                    <div class="mb-3 row">
                                        <label for="jenis_transaksi" class="col-sm-3 col-form-label">Jenis
                                            Transaksi</label>
                                        <div class="col-sm">
                                            <select class="form-select" aria-label="Default select example"
                                                    name="jenis_transaksi" id="jenis_transaksi">
                                                <option value="" selected>Semua</option>
                                                <option value="T">Single Treatment</option>
                                                <option value="P">Paket Treatment</option>
                                                <option value="O">Obat</option>
                                                <option value="K">Konsultasi</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- End -->

                                    <!-- Kasir -->
                                    <div class="mb-3 row">
                                        <label for="kasir" class="col-sm-3 col-form-label">Kasir</label>
                                        <div class="col-sm">
                                            <select class="form-select" aria-label="Default select example" name="kasir"
                                                    id="kasir">
                                                <option value="" selected>Semua</option>
                                                <option value="1">Ade Maya</option>
                                                <option value="2">Pratiwi</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- End -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Button Cari -->
                                    <button type="submit" class="btn btn-success btn-md" id="btnCari">Cari</button>
                                    <!-- End -->

                                    <!-- Button Cetak -->
                                    <a href="" class="btn btn-warning btn-md" id="btnCetak">Cetak</a>
                                    <!-- End -->

                                    <!-- Button Export -->
                                    <a href="" class="btn btn-danger btn-md" id="btnExport">Download</a>
                                    <!-- End -->
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <table id="example" class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pelanggan</th>
                                <th>Waktu Issued</th>
                                <th>Nama Item</th>
                                <th>Jenis Transaksi</th>
                                <th>Kasir</th>
                                <th>Harga</th>
                                <th>QTY</th>
                                <th>Total</th>
                                <th>Diskon</th>
                                <th>Subtotal</th>
                                <th>PPN</th>
                                <th>Total After PPN</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="10" class="fw-bolder">Grand Total</td>
                                <td id="totalSubtotal">0</td>
                                <td id="totalPpn">0</td>
                                <td id="totalAfterPpn">0</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
        integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"
        integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script !src="">
    function formatRupiah(value) {
        // Cek jika nilai null atau 0
        if (value === null || value === 0) {
            return '';
        }

        var number_string = value.toString();
        var split = number_string.split(',');
        var sisa = split[0].length % 3;
        var rupiah = split[0].substr(0, sisa);
        var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }

    $(document).ready(function () {
        $('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": "./data/serverside.php",
                "type": "POST"
            },
            "columns": [
                {"data": "no"},
                {"data": "nama_pelanggan"},
                {"data": "waktu_issued"},
                {"data": "nama_item"},
                {"data": "jenis_transaksi"},
                {"data": "kasir"},
                {
                    "data": "harga",
                    "render": function (data, type, row) {
                        return formatRupiah(data);
                    }
                },
                {"data": "qty"},
                {
                    "data": "total",
                    "render": function (data, type, row) {
                        return formatRupiah(data);
                    }
                },
                {
                    "data": "diskon",
                    "render": function (data, type, row) {
                        return formatRupiah(data);
                    }
                },
                {
                    "data": "subtotal",
                    "render": function (data, type, row) {
                        return formatRupiah(data);
                    }
                },
                {
                    "data": "ppn",
                    "render": function (data, type, row) {
                        return formatRupiah(data);
                    }
                },
                {
                    "data": "total_after_ppn",
                    "render": function (data, type, row) {
                        return formatRupiah(data);
                    }
                },
            ],
            "order": [[1, 'asc']],
            "responsive": true,
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api();

                // Access grand totals directly from the response
                var grandTotalSubtotal = api.ajax.json().grand_totals.subtotal;
                var grandTotalPpn = api.ajax.json().grand_totals.ppn;
                var grandTotalAfterPpn = api.ajax.json().grand_totals.total_after_ppn;

                // Display grand totals with formatting
                $('#totalSubtotal').html(formatRupiah(grandTotalSubtotal));
                $('#totalPpn').html(formatRupiah(grandTotalPpn));
                $('#totalAfterPpn').html(formatRupiah(grandTotalAfterPpn));
            },
            "pagingType": "full_numbers"
        });

        $('#tanggal_awal').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });

        $('#tanggal_akhir').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    })
</script>
</body>
</html>