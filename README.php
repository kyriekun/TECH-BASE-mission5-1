##簡易掲示板
<html lang="ja">
 <head>
     <meta charset="UTF-8">
     <title>mission_5-1-K</title>
 </head>
 <body>
 <!-- 編集モードの準備 -->
<?php
// DB接続設定
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//CREATE文でデータ内にテーブルを作成
$sql = "CREATE TABLE IF NOT EXISTS dbm5_1p"
." ("//カラム指定
. "id INT AUTO_INCREMENT PRIMARY KEY,"//ID
. "name char(32),"//名前
. "comment TEXT,"//コメント
. "date TEXT,"//日付
. "pw nchar(6)"//パスワード
.");";
  $stmt = $pdo->query($sql);//queryに$sqlを渡し、配列として$stmtに格納
//編集タイプの取得
if(isset($_POST['name']) && $_POST['name']!=""){
  $type="kakikomi";
}elseif(isset($_POST['hpw']) && $_POST['hpw']!=""){
  $type="edit";
}else{$type="del";}
  // 編集モードから送信された時
  if (isset($type) && $type=="edit"  && isset($_POST['editn']) && isset($_POST['hpw'])){
    $editn=$_POST['editn'];
    $hpw=$_POST['hpw'];
    //編集対象の投稿内容を取得
    $sqlh0 = 'SELECT*FROM dbm5_1p WHERE id=:id and pw=:pw';//一致しているデータを呼び出す
    $stmth0 = $pdo->prepare($sqlh0);
    $stmth0->bindParam(':id', $editn, PDO::PARAM_INT);
    $stmth0->bindParam(':pw', $hpw, PDO::PARAM_INT);
    $stmth0->execute(); 
    $lines = $stmth0->fetchAll();
    foreach ($lines as $line){
      $hid = $line['id'];//編集対象の投稿番号を取得
      $name0 = $line['name'];//フォーム書き込み準備
      $com0 = $line['comment'];//フォーム書き込み準備
      $pw0 = $line['pw'];//フォーム書き込み準備
    }
    //モードを編集モードに設定
    $mode0 = "editm";
  }else{}
?>
  <form action="" method="post">
    <!-- モードの表示 編集モードの場合編集番号を裏表示 -->
    新規投稿
    <input type="hidden" name="henshu" 
        value="<?php if($mode0=="editm"){echo $hid;}else{echo 0;} ?>"><br>
    <input type="text" name="name" placeholder="名前" 
        value="<?php if($mode0=="editm"){echo $name0;}else{} ?>">
    <input type="text" name="com" placeholder="コメント" 
        value="<?php if($mode0=="editm"){echo $com0;}else{} ?>">
    <input type="number" name="pw" placeholder="パスワード" 
        value="<?php if($mode0=="editm"){echo $pw0;}else{} ?>">
    <input type="submit" name="submit0" value="投稿">
      <br>
      <br>
    投稿削除<br>
    <input type="number" name="del" placeholder="削除依頼番号">
    <input type="number" name="dpw" placeholder="パスワード">
    <input type="submit" name="submitd" value="削除">
      <br>
      <br>
    編集依頼<br>
    <input type="number" name="editn" placeholder="編集依頼番号">
    <input type="number" name="hpw" placeholder="パスワード">
    <input type="submit" name="submith" value="編集">
    <br>
    <br>
    ↓↓↓↓以下投稿内容↓↓↓↓
    <br>
     </form>
<?php
//フォームからの変数取得
//$name = $_POST["name"];
if(isset($_POST['name'])) {
  if ( $_POST['name'] == "" ){
  }else{
    $name = $_POST["name"];
  }
}
//$com = $_POST["com"];
if(isset($_POST["com"])) {
  if ( $_POST["com"] == "" ){
  }else{
    $com = $_POST["com"];
  }
}
$date = date ( "Y年m月d日 H時i分s秒" );
//$del = $_POST["del"];
if(isset($_POST["del"])) {
  if ( $_POST['del'] == "" ){
  }else{
    $del = $_POST["del"];
  }
}

//$pw = $_POST["pw"];//パスワード
if(isset($_POST['pw'])) {
  if ( $_POST['pw'] == "" ){
  }else{
    $pw = $_POST["pw"];
  }
}
//$dpw = $_POST["dpw"];
if(isset($_POST['dpw'])) {
  if ( $_POST['dpw'] == "" ){
  }else{
    $dpw = $_POST["dpw"];
  }
}

//INSERT文でデータを登録
if($type=="kakikomi"){
  $henshu = $_POST['henshu'];//編集番号を取得
  if($henshu==0){//通常投稿の設定
    $sql = $pdo -> prepare(
      "INSERT INTO dbm5_1p (name, comment,date,pw) VALUES (:name, :comment,:date,:pw)"
    );
    if(isset($name) && isset($com) && isset($pw)){
      $sql -> bindParam(':name', $name, PDO::PARAM_STR);
      $sql -> bindParam(':comment', $com, PDO::PARAM_STR);
      $sql -> bindParam(':date', $date, PDO::PARAM_STR);
      $sql -> bindParam(':pw', $pw, PDO::PARAM_INT);
      $sql -> execute();
    }else{}
  }elseif($henshu!=0){//編集設定
    $sqlh = 'UPDATE dbm5_1p SET name=:name,comment=:comment,pw=:pw WHERE id=:id';
    $stmth = $pdo->prepare($sqlh);
    $stmth->bindParam(':name', $name, PDO::PARAM_STR);
    $stmth->bindParam(':comment', $com, PDO::PARAM_STR);
    $stmth->bindParam(':pw', $pw, PDO::PARAM_INT);
    $stmth->bindParam(':id', $henshu, PDO::PARAM_INT);
    $stmth->execute();
  }else{}
}else{}


//削除設定
if(isset($del) && isset($dpw)){
  $sqld = 'delete from dbm5_1p where id=:del and pw=:dpw';
 	$stmtd = $pdo->prepare($sqld);
    $stmtd->bindParam(':del', $del, PDO::PARAM_INT);
    $stmtd->bindParam(':dpw', $dpw, PDO::PARAM_INT);
 	$stmtd->execute();
}else{}

//SELECT文でテーブルに登録されたデータを取得し、ブラウザに表示
$sql0 = 'SELECT*FROM dbm5_1p';
$stmt0 = $pdo->query($sql0);
$results = $stmt0->fetchAll();
foreach ($results as $row){
    echo $row['id'].',';
    echo $row['name'].',';
    echo $row['comment'].',';
    echo $row['date'].',';
    echo $row['pw'].','.'<br>';
}

?>
     
 </body>
 </html>
