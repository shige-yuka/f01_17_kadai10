<?php
include('functions.php');
//入力チェック(受信確認処理追加)
if(
  !isset($_POST['name']) || $_POST['name']=="" ||
  !isset($_POST['email']) || $_POST['email']=="" ||
  !isset($_POST['detail']) || $_POST['detail']==""
){
  exit('ParamError');
}

//1. POSTデータ取得
$name   = $_POST['name'];
$email  = $_POST['email'];
$detail = $_POST['detail'];

// アップロード関連を追加
//Fileアップロードチェック
if (isset($_FILES['upfile'] ) && $_FILES['upfile']['error'] ==0 ) {
    // ファイルをアップロードしたときの処理
    //アップロードしたファイルの情報取得
    $file_name = $_FILES['upfile']['name'];     //アップロードしたファイルのファイル名
    $tmp_path  = $_FILES['upfile']['tmp_name']; //アップロード先のTempフォルダ
    $file_dir_path = 'upload/';                 //画像ファイル保管先のディレクトリ名，自分で設定する
    
    //File名の変更
    $extension = pathinfo($file_name, PATHINFO_EXTENSION);              //拡張子取得
    $uniq_name = date('YmdHis').md5(session_id()) . "." . $extension;   //ユニークファイル名作成
    $file_name = $file_dir_path.$uniq_name;                             //ファイル名とパス

    // FileUpload [--Start--]
    $img='';
    if ( is_uploaded_file( $tmp_path ) ) {
        if ( move_uploaded_file( $tmp_path, $file_name ) ) {
            chmod( $file_name, 0644 );
            // <img>を使って画像を出力しよう
            // $img = '<img src="'.$file_name.'" width="200px">';
        } else {
            exit('Error:アップロードできませんでした．');
        }
    }
    // FileUpload [--End--]
}else{
    // ファイルをアップしていないときの処理
    $img = '画像が送信されていません'; //Error文字
}


//2. DB接続します(エラー処理追加)
$pdo = db_conn();

//３．データ登録SQL作成
// SQLにimageカラムを追記
$stmt = $pdo->prepare('INSERT INTO '. $table .'(id, name, email, detail, indate, image)VALUES(NULL, :a1, :a2, :a3, sysdate(), :image)');
$stmt->bindValue(':a1', $name, PDO::PARAM_STR);
$stmt->bindValue(':a2', $email, PDO::PARAM_STR);
$stmt->bindValue(':a3', $detail, PDO::PARAM_STR);
$stmt->bindValue(':image', $file_name, PDO::PARAM_STR);
$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
  queryError($stmt);
}else{
  //５．index.phpへリダイレクト
  header('Location: add.php');
  exit;
}
?>

