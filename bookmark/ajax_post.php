<?php
include('user/functions.php');
//入力チェック(受信確認処理追加)
if(
  !isset($_POST["book_name"]) || $_POST["book_name"]=="" ||
  !isset($_POST["book_url"]) || $_POST["book_url"]==""
){
    exit('ParamError');
}

//1. POSTデータ取得
$name = $_POST["book_name"];
$url = $_POST["book_url"];
$comment = $_POST["book_comment"];
$comment = nl2br($comment);

$sql ="INSERT INTO gs_bm_table(id, book_name, book_url, book_comment, create_time, image)
VALUES(NULL, :a1, :a2, :a3, sysdate(), :image)";

//2. DB接続します(エラー処理追加)
$pdo = db_conn();

//３．データ登録SQL作成
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':a1', $name, PDO::PARAM_STR);
$stmt->bindValue(':a2', $email, PDO::PARAM_STR);
$stmt->bindValue(':a3', $detail, PDO::PARAM_STR);
$stmt->bindValue(':image', $file_name, PDO::PARAM_STR);
$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
    queryError($stmt);
}else{
    $array = [$name, $url, $comment, $file_name];
    echo json_encode($array);
    exit;
}



?>