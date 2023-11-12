<?php

// Database configuration
include '../config/config.php';

$draw = $_POST['draw'] ?? 1;
$row = $_POST['start'] ?? 0;
$rowperpage = $_POST['length'] ?? 10;
$columnIndex = $_POST['order'][0]['column'] ?? 0;
$columnName = $_POST['columns'][$columnIndex]['data'] ?? '';
$columnSortOrder = $_POST['order'][0]['dir'] ?? 'asc';
$searchValue = $_POST['search']['value'] ?? '';

$searchQuery = "";
$searchQueryVal = array();

if ($searchValue != '') {
    $searchQuery .= " WHERE (
        LOWER(nama_pelanggan) LIKE LOWER($1) OR
        LOWER(CAST(waktu_issued AS TEXT)) LIKE LOWER($2) OR
        LOWER(nama_item) LIKE LOWER($3) OR
        LOWER(jenis_transaksi) LIKE LOWER($4) OR
        LOWER(kasir) LIKE LOWER($5) OR
        CAST(harga AS TEXT) LIKE $6 OR
        CAST(qty AS TEXT) LIKE $7 OR
        CAST(total AS TEXT) LIKE $8 OR
        CAST(diskon AS TEXT) LIKE $9 OR
        CAST(subtotal AS TEXT) LIKE $10 OR
        CAST(ppn AS TEXT) LIKE $11 OR
        CAST(total_after_ppn AS TEXT) LIKE $12
    )";

    for ($i = 1; $i <= 12; $i++) {
        $searchQueryVal[] = '%' . $searchValue . '%';
    }
}

$query = "SELECT * FROM trx_item";
$total_records = pg_num_rows(pg_query($conn, $query));

## Total number of records with filter
$sql = "SELECT count(*) as allcount from trx_item " . $searchQuery;
$result = pg_query_params($conn, $sql, $searchQueryVal);
$records = pg_fetch_assoc($result);
$totalRecordwithFilter = $records['allcount'];

$sql = "SELECT * FROM trx_item" . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT $rowperpage OFFSET $row";
$result_query = pg_query_params($conn, $sql, $searchQueryVal);

if ($result_query === false) {
    die(pg_last_error($conn));
}

$data = array();

$no = $row + 1;

$grandTotalSubtotal = 0;
$grandTotalPpn = 0;
$grandTotalAfterPpn = 0;

while ($row = pg_fetch_assoc($result_query)) {
    $data[] = array(
        'no' => $no,
        'nama_pelanggan' => $row['nama_pelanggan'],
        'waktu_issued' => $row['waktu_issued'],
        'nama_item' => $row['nama_item'],
        'jenis_transaksi' => $row['jenis_transaksi'],
        'kasir' => $row['kasir'],
        'harga' => $row['harga'],
        'qty' => $row['qty'],
        'total' => $row['total'],
        'diskon' => $row['diskon'],
        'subtotal' => $row['subtotal'],
        'ppn' => $row['ppn'],
        'total_after_ppn' => $row['total_after_ppn'],
    );

    $no++;
}

$grandTotalQuery = "SELECT SUM(subtotal) as grand_subtotal, SUM(ppn) as grand_ppn, SUM(total_after_ppn) as grand_total_after_ppn FROM trx_item " . $searchQuery;
$grandTotalResult = pg_query_params($conn, $grandTotalQuery, $searchQueryVal);
$grandTotalRow = pg_fetch_assoc($grandTotalResult);

$grandTotalSubtotal = $grandTotalRow['grand_subtotal'];
$grandTotalPpn = $grandTotalRow['grand_ppn'];
$grandTotalAfterPpn = $grandTotalRow['grand_total_after_ppn'];

$response = array(
    'draw' => intval($draw),
    'iTotalRecords' => intval($total_records),
    'iTotalDisplayRecords' => intval($totalRecordwithFilter),
    'aaData' => $data,
    'grand_totals' => [
        'subtotal' => $grandTotalSubtotal,
        'ppn' => $grandTotalPpn,
        'total_after_ppn' => $grandTotalAfterPpn,
    ],
);

echo json_encode($response);

?>
