<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/profile.css">
  <title>プロフィール画面</title>
</head>

<body>
<?php include('header.php'); ?>

<main class="profile-container">
  <div class="header-inner">
    <h1>プロフィール</h1>
  </div>
  <section class="profile-header">
    <img src="path/to/icon.png" alt="アイコン" class="profile-icon">
  </section>

  <!-- フォームを追加 -->
  <form action="profilekosin.php" method="post" enctype="multipart/form-data">
    <section class="profile-details">
      <h3>メールアドレス</h3>
      <textarea name="mail_address" rows="1" cols="30" placeholder=""></textarea>
      <h3>パスワード</h3>
      <textarea name="pass" rows="1" cols="30" placeholder=""></textarea>
      <h3>ユーザーネーム</h3>
      <textarea name="user_name" rows="1" cols="30" placeholder=""></textarea>
      <h3>自己紹介</h3>
      <textarea name="introduction" rows="4" cols="50" placeholder="自己紹介を入力してください"></textarea>

      <h3>性別</h3>
      <select name="gender">
        <option value="male">男性</option>
        <option value="female">女性</option>
        <option value="other">その他</option>
      </select>
     
      <br>
      <p><button type="submit">更新</button></p>
    </section>
  </form>
</main>
</body>

</html>