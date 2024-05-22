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
  <header>
    <div class="header-inner">
      <h1>プロフィール</h1>
    </div>
  </header>

  <main class="profile-container">
    <section class="profile-header">
      <img src="path/to/icon.png" alt="アイコン" class="profile-icon">
      <h2 class="profile-name">ユーザー名</h2>
    </section>

    <!-- フォームを追加 -->
    <form action="profilekosin.php" method="post">
      <section class="profile-details">
      <h3>メールアドレス</h3>
        <textarea name="introduction" rows="2" cols="30" placeholder=""></textarea>
        <h3>パスワード</h3>
        <textarea name="introduction" rows="2" cols="30" placeholder=""></textarea>
        <h3>ハンドルネーム</h3>
        <textarea name="introduction" rows="2" cols="30" placeholder=""></textarea>
        <h3>自己紹介</h3>
        <textarea name="introduction" rows="4" cols="50" placeholder="自己紹介を入力してください"></textarea>

        <h3>性別</h3>
        <select name="gender">
          <option value="male">男性</option>
          <option value="female">女性</option>
          <option value="other">その他</option>
        </select>

        <h3>年齢</h3>
        <select name="age">
          <option value="">選択してください</option>
          <!-- 15歳から80歳までの選択肢を追加 -->
          <option value="15">15歳</option>
          <option value="16">16歳</option>
          <option value="17">17歳</option>
          <option value="18">18歳</option>
          <option value="19">19歳</option>
          <option value="20">20歳</option>
          <option value="21">21歳</option>
          <option value="22">22歳</option>
          <option value="23">23歳</option>
          <option value="24">24歳</option>
          <option value="25">25歳</option>
          <option value="26">26歳</option>
          <option value="27">27歳</option>
          <option value="28">28歳</option>
          <option value="29">29歳</option>
          <option value="30">30歳</option>
          <option value="31">31歳</option>
          <option value="32">32歳</option>
          <option value="33">33歳</option>
          <option value="34">34歳</option>
          <option value="35">35歳</option>
          <option value="36">36歳</option>
          <option value="37">37歳</option>
          <option value="38">38歳</option>
          <option value="39">39歳</option>
          <option value="40">40歳</option>
          <option value="41">41歳</option>
          <option value="42">42歳</option>
          <option value="43">43歳</option>
          <option value="44">44歳</option>
          <option value="45">45歳</option>
          <option value="46">46歳</option>
          <option value="47">47歳</option>
          <option value="48">48歳</option>
          <option value="49">49歳</option>
          <option value="50">50歳</option>
          <option value="51">51歳</option>
          <option value="52">52歳</option>
          <option value="53">53歳</option>
          <option value="54">54歳</option>
          <option value="55">55歳</option>
          <option value="56">56歳</option>
          <option value="57">57歳</option>
          <option value="58">58歳</option>
          <option value="59">59歳</option>
          <option value="60">60歳</option>
          <option value="61">61歳</option>
          <option value="62">62歳</option>
          <option value="63">63歳</option>
          <option value="64">64歳</option>
          <option value="65">65歳</option>
          <option value="66">66歳</option>
          <option value="67">67歳</option>
          <option value="68">68歳</option>
          <option value="69">69歳</option>
          <option value="70">70歳</option>
          <option value="71">71歳</option>
          <option value="72">72歳</option>
          <option value="73">73歳</option>
          <option value="74">74歳</option>
          <option value="75">75歳</option>
          <option value="76">76歳</option>
          <option value="77">77歳</option>
          <option value="78">78歳</option>
          <option value="79">79歳</option>
          <option value="80">80歳</option>
        </select>

        <br>

        <p><button type="submit">更新</button></p>
      </section>
    </form>

    <section class="youtube-posts">
      <h3>YouTubeの投稿</h3>
      <div class="youtube-video">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/動画ID" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        <p class="video-description">動画の説明文がここに入ります。</p>
      </div>
      <!-- 追加のYouTube動画をここに追加 -->
    </section>
  </main>
</body>

</html>