<?php
require __DIR__ . '/is_admins.php';
require __DIR__ . '/db_connect.php';

$output = [
    'success' => false,
    'code' => 0,
    'error' => '參數不足',
];

if(! isset($_POST['OrderPrice'])){
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "INSERT INTO `surprise_list_detail`(`sid`, `NumPeople`, `NumMeal`, `OrderPrice`, `CreateTime`) VALUES (null, ?, ?, ?, NOW())";
//新增整個表單連同sid null也要放入

$stmt = $pdo ->prepare($sql);

$stmt -> execute([
    intval($_POST['NumPeople']),
    intval($_POST['NumMeal']),
    intval($_POST['OrderPrice']),
]);
//將表單放入執行,對應的是name

$output['rowCount'] = $stmt -> rowCount();
if($stmt -> rowCount()){
    $output['success'] = true;
    unset($output['error']);
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
