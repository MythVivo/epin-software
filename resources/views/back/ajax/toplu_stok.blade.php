<?php
$columns = array(
    array('db' => 'oyun_ismi', 'dt' => 0),
    array('db' => 'baslik_ismi', 'dt' => 1),
    array('db' => 'paket_ismi', 'dt' => 2),
    array('db' => 'kalan_stok', 'dt' => 3),
    array('db' => 'stok_alis', 'dt' => 4),
    array('db' => 'stok_satis', 'dt' => 5),
    array('db' => 'stok_degeri', 'dt' => 6),
    array('db' => 'eklenme_tarihi', 'dt' => 7),
    array('db' => 'aksiyon', 'dt' => 8)
);
$data = [
    'draw' => 9,
    'recordsTotal' => 1,
    'recordsFiltered' => 1,
    'data' => [
        ['a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',],
    ],
];
echo json_encode($data);
?>
