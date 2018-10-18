<?php
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
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>FileUploadテスト</title>
    <form method="post" action="file_upload.php" enctype="multipart/form-data">
        <input type="file" name="upfile" accept="image/*" capture="camera">
        <input type="submit" name="submit" value="送信">
    </form>
</head>
<body>
    <?=$img?>
</body>
</html>
