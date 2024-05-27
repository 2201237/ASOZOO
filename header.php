<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="css/header.css">
  <meta charset="UTF-8">
</head>
<body>
<header>

  <script src="/assets/js/jquery-3.7.1.js"></script>

  <div class="header-inner">
    <img src="logo/logo.png" class="header-logo">
    <div class="search-container">
      <form class="search-form">
        <input type="text" class="search-bar" placeholder="検索">
        <button type="submit" class="search-btn">🔍</button>
      </form>
    </div>
    <div class="auth">
      <!-- ログイン状態に応じて表示を変更 -->
      <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <!-- ログインしている場合、ユーザーアイコンを表示 -->
        <img src="logo/logo.png" alt="ユーザーアイコン" />
      <?php else: ?>
        <!-- ログインしていない場合、ログインと新規登録ボタンを表示 -->
        <button onclick="location.href='login-input.php'">ログイン</button> 
        <button onclick="location.href='touroku.php'">新規登録</button>
      <?php endif; ?>
      <!-- ハンバーガーメニューボタン -->
      <button class="hamburger-btn">≡</button>
    </div>
  </div>
  <nav class="navigation-menu">
    <ul>
      <li><a href="#home">ホーム</a></li>
      <li><a href="#news">すべての投稿</a></li>
      <li><a href="toukou-image-input.php">写真</a></li>
      <li><a href="#about">動画</a></li>
      <li><a href="#contact">YouTube</a></li>
      <li><a href="#blog">コミュニティ</a></li>
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