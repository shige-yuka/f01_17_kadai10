<?php
session_start();
include('user/functions.php');
loginCheck();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Book Bookmark</title>
  <link href="assets/css/reset.css" rel="stylesheet">
  <link href="assets/css/sanitize.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include( $_SERVER['DOCUMENT_ROOT'] . '/bookmark/ui/globalnav.php'); ?>
<form method="post" action="insert.php" class="box-wrap" onsubmit="return checkform(this)" enctype="multipart/form-data">
  <div class="box">
    <h1 class="headline">Book Bookmark</h1>
    <label class="input-wrap">本の名前：<input type="text" name="book_name" class="input"></label><br>
    <label class="input-wrap">本のURL：<input type="text" name="book_url" class="input js-valdate-url" id="harf" value="https://"></label><br>
    <label class="input-wrap">コメント：<br><textArea name="book_comment" rows="4" cols="40" class="textarea"></textArea></label><br>
    <div class="img-preview-wrap">
      <img id="img_preview" class="img-preview">
    </div>
    <label class="button-wrap">
      画像アップロード
      <input type="file" name="upfile" accept="image/*" capture="camera" class="btn-img-upload">
    </label><br>
    <!-- <button class="button js-button">プレビュー</button> -->
    <input type="submit" value="登録" class="button">
    <a class="link" href="select.php">データ一覧</a>
  </div>
  <div class="js-visible">

  </div>
  <!-- <a class="button signout" href="user/signout.php">サインアウト</a> -->
</form>
<?php include( $_SERVER['DOCUMENT_ROOT'] . '/bookmark/js/assets.php'); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="js/ajax.js"></script>
<script>
$(function(){
  $('.img-preview-wrap').hide();
  $('.btn-img-upload').change(function(e){
    var file = e.target.files[0];
    var reader = new FileReader();
 
    if(file.type.indexOf("image") < 0){
      alert("画像ファイルを指定してください。");
      return false;
    }
 
    reader.onload = (function(file){
      return function(e){
        $("#img_preview").attr("src", e.target.result);
        $("#img_preview").attr("alt", file.name);
      };
    })(file);
    reader.readAsDataURL(file);
    $('.img-preview-wrap').show(100);
  });
});
</script>
</body>
</html>
