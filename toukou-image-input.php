<?php
ob_start();
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
require "db-connect.php";
$pdo = new PDO($connect, USER, PASS);

if (isset($_POST['email'])) {
    $sql = $pdo->prepare('SELECT * FROM user WHERE mail_address = ?');
    $sql->execute([$_POST['email']]);
    foreach ($sql as $row) {
        $_SESSION['User']['user_icon'] = $row['icon'];
    }
}

require "header.php";
?>
<link rel="stylesheet" href="css/image.css">
<link rel="stylesheet" href="css/header.css">
<body>
<?php if (isset($_SESSION['User']) && isset($_SESSION['User']['user_id'])): ?>
    <?php if ($_SESSION['User']['user_icon'] !== 'ban.png'): ?>
        <center>
        <form enctype="multipart/form-data" action="toukou-image-output.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="name" value="value" />

            <p>
            <label>写真</label>
            <input name="user_file_name" type="file" required/>
            </p>

            <p>
            <label>タイトル</label>
            <input type="text" name="title" required>
            </p>

            <p>
            <label>コメント</label>
            <input type="text" name="content">
            </p>

            <!-- タグ追加部分-->
            <p id="tagform">
            <label>タグ</label>
            <input type="text" name="tag" id="tagInput">
            <ul id="tagList"></ul>
            <button name="add" type="button" id="append">タグ追加</button>
            <input type="submit" value="投稿">
            </p>
        </form>

        </center>
        <script src="js/jquery-3.7.1.js"></script>
        <!-- タグ追加部分-->
        <script>
            $("#append").click(function(){
                var tagValue = $("#tagInput").val();
                var tagCount = $("#tagList li").length;
                var isDuplicate = false;
                
                $("#tagList li").each(function() {
                    if ($(this).text() === tagValue) {
                        isDuplicate = true;
                    }
                });

                if (tagValue && tagCount < 10 && !isDuplicate) {
                    $("#tagList").append($("<li>").text(tagValue));
                    $("#tagform").append($("<input type=\"hidden\" name=\"tags[]\">").val(tagValue));
                    $("#tagInput").val("");
                } else if (tagCount >= 10) {
                    alert("タグは10個まで追加可能です。");
                } else if (isDuplicate) {
                    alert("同じ名前のタグは追加できません。");
                }
            });
        </script>
    <?php else: ?>
        <p>アカウントが停止されています。</p>
    <?php endif; ?>
<?php else: ?>
    <p>ログインされていません</p>
    <hr>

    <a href="login-input.php">ログインページへ</a>
    
<?php endif; ?>
</body>
</html>

<?php
// 出力バッファリングを終了してバッファの内容を出力
ob_end_flush();
?>