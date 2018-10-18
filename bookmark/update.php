<?php
include('user/functions.php');
//入力チェック(受信確認処理追加)
if(
  !isset($_POST['book_name']) || $_POST['book_name']=='' ||
  !isset($_POST['book_url']) || $_POST['book_url']=='' ||
  !isset($_POST['book_comment']) || $_POST['book_comment']==''
){
  exit('ParamError');
}

//1. POSTデータ取得
$id     = $_POST['id'];
$book_name   = $_POST['book_name'];
$book_url  = $_POST['book_url'];
$book_comment = $_POST['book_comment'];
$file_name = $_POST['image'];

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
            $img = '<img src="'.$file_name.'" width="200px">';
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


//3．データ登録SQL作成
// SETからWHEREの間が変更したい箇所
$stmt = $pdo->prepare('UPDATE gs_bm_table SET book_name = :a1, book_url = :a2, book_comment = :a3, image = :image, WHERE id = :id');
$stmt->bindValue(':a1', $book_name, PDO::PARAM_STR);
$stmt->bindValue(':a2', $book_url, PDO::PARAM_STR);
$stmt->bindValue(':a3', $book_comment, PDO::PARAM_STR);
$stmt->bindValue(':image', $file_name, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

//4．データ登録処理後
if($status==false){
  errorMsg($stmt);
}else{
  header('Location: select.php');
  exit;
}
?>
