<!DOCTYPE html>
<html lang="ja">
<head>
  <link rel="stylesheet" href="css/header.css">
  <meta charset="UTF-8">
  <title>ヘッダー</title>
  <script src="/assets/js/jquery-3.7.1.js"></script>
  <style>
    .auth img {
      width: 40px; /* ユーザーアイコンの幅を指定 */
      height: 40px; /* ユーザーアイコンの高さを指定 */
      border-radius: 50%; /* アイコンを円形にする */
    }
    .auth button {
      padding: 5px 10px; /* ボタンのパディングを調整 */
    }
  </style>
</head>
<body>
<header>
  <div class="header-inner">
    <img src="logo/logo.png" class="header-logo" alt="ロゴ">
    <div class="search-container">
      <form class="search-form">
        <input type="text" class="search-bar" placeholder="検索">
        <button type="submit" class="search-btn">🔍</button>
      </form>
    </div>
    <div class="auth">

    <?php 
if(isset($_SESSION['User']) && isset($_SESSION['User']['user_id'])){
  echo '<img src="img/user.jpeg" alt="ユーザーアイコン" />';
}else{
  echo '<button onclick="location.href=\'login-input.php\'">ログイン</button> 
  <button onclick="location.href=\'touroku.php\'">新規登録</button>';
}
?>

      <!-- ハンバーガーメニューボタン -->
      <button class="hamburger-btn">≡</button>
    </div>
  </div>
  <nav class="navigation-menu">
    <ul>
      <li><a href="home.php">ホーム</a></li>
      <li><a href="toukouAll.php">すべての投稿</a></li>
      <li><a href="toukou-image-input.php">写真</a></li>
      <li><a href="toukou-movie-input.php">動画</a></li>
      <li><a href="#contact">YouTube</a></li>
      <li><a href="communitys.php">コミュニティ</a></li>
    </ul>
  </nav>
</header>
<!-- ハンバーガーメニューの内容 -->
<div id="hamburger-menu" class="sidebar">
  <a href="#">Link 1</a>
  <a href="#">Link 2</a>
  <a href="#">Link 3</a>
  <!-- その他のメニュー項目 -->
</div>

<script>
$(document).ready(function() {
  $('.hamburger-btn').click(function() {
    $('#hamburger-menu').toggle();
  });
});
</script>
<form action="profilehyouzi2.php" method="get">
        <button type="submit">プロフィール</button>
    </form>
</body>
</html>
