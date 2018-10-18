<?php
include('user/functions.php');

if(
  !isset($_POST["book_name"]) || $_POST["book_name"]=="" ||
  !isset($_POST["book_url"]) || $_POST["book_url"]==""
){
  exit('<p>必須項目が入力されていません</p><br><a href="index.php">入力フォームに戻る</a>');
}

//1. POSTデータ取得
//$name = filter_input( INPUT_GET, "name" ); //こういうのもあるよ
//$url = filter_input( INPUT_POST, "email" ); //こういうのもあるよ
$name = $_POST["book_name"];
$url = $_POST["book_url"];
$comment = $_POST["book_comment"];
$comment = nl2br($comment);


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


//2. DB接続します
$pdo = db_conn();

//３．データ登録SQL作成
$sql ="INSERT INTO gs_bm_table(id, book_name, book_url, book_comment, create_time, image)
VALUES(NULL, :a1, :a2, :a3, sysdate(), :image)";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':a1', $name, PDO::PARAM_STR);    //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a2', $url, PDO::PARAM_STR);   //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a3', $comment, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':image', $file_name, PDO::PARAM_STR);
$status = $stmt->execute();                        //実行！！

//４．データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  // $error[2]は２番目だけ人間にわかる言葉が返ってくる
  exit("sqlError:".$error[2]);
}else{
  //５．index.phpへリダイレクト
  header("location: add.php");
}
?>
