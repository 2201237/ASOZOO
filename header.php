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
      margin-left: 10px; /* ボタンの間隔を調整 */
    }

    
  </style>
</head>
<body>
<div class="header">
<header>
  <div class="header-inner">
    <img src="logo/logo.png" class="header-logo" alt="ロゴ">
    <div class="search-container">
      <form action="search_result.php" method="post" class="search-form">
        <input type="text" class="search-bar" name="word" placeholder="投稿検索">
        <button type="submit" class="search-btn">🔍</button>
      </form>
    </div>
    <div class="auth">
      <?php 
      if(isset($_SESSION['User']) && !empty($_SESSION['User']['user_icon'])){
          echo '<img src="icon/' . htmlspecialchars($_SESSION['User']['user_icon'], ENT_QUOTES, 'UTF-8') . '" alt="ユーザーアイコン" />';
      } else {
          echo '<img src="icon/user.jpeg" alt="デフォルトアイコン" />';
      }
      ?>

      <!-- ログイン・新規登録ボタン -->
      <?php if (isset($_SESSION['User']) && isset($_SESSION['User']['user_id'])): ?>
        <!-- ログイン済みの場合の処理 -->
        <form action="logout.php" method="post" style="display: inline;">
          <button type="submit">ログアウト</button>
        </form>
        <?php
          if (isset($_SESSION['User']['user_id']) && $_SESSION['User']['user_id'] == 5) {
              echo '<button onclick="location.href=\'admin.php\'">管理者ページ</button>';
          }

          if (isset($_SESSION['User']['user_id']) && $_SESSION['User']['user_id'] == 17) {
            echo '<button onclick="location.href=\'admin.php\'">管理者ページ</button>';
        }
        ?>



      <?php else: ?>
        <!-- 未ログインの場合の処理 -->
        <button onclick="location.href='login-input.php'">ログイン</button> 
        <button onclick="location.href='touroku.php'">新規登録</button>
      <?php endif; ?>
    </div>
  </div>
  <div name="atag">
  <nav class="navigation-menu">
    <ul>
      <li><a href="home.php">ホーム</a></li>
      <li><a href="toukouAll.php">すべての投稿</a></li>
      <li><a href="toukou-image-input.php">写真</a></li>
      <li><a href="toukou-movie-input.php">動画（ベータ版）</a></li>
      <li><a href="toukou-link-input.php">YouTube</a></li>
      <li><a href="communitys.php">コミュニティ</a></li>
      <li><a href="profilehyouzi2.php">プロフィール</a></li>
    </ul>
  </nav>
  </div>
</header>
      </div>

<script>
$(document).ready(function() {
  $('.hamburger-btn').click(function() {
    $('#hamburger-menu').toggle();
  });
});
</script>
</body>
</html>
