<?php
// 出力バッファリングを開始
ob_start();
// セッションの開始
session_start();
?>

<?php require 'header.php'; ?>
<?php require 'db-connect.php'; ?>
<link rel="stylesheet" href="css/link.css">

</head>

<body>

<?php if (isset($_SESSION['User']) && isset($_SESSION['User']['user_id'])): ?>
    <?php if ($_SESSION['User']['user_icon'] !== 'ban.png'): ?>
        <div class="title">
            <h2>Youtube共有</h2>
        </div>

        <center>
            <div class="touroku">
                <form action="toukou-link-output.php" method="post">

                    <p>Youtubeリンク
                        <input type="text" name="link" placeholder="YOUTUBEの埋め込みで入力" required>
                    </p>
                    <p>タイトル
                        <input type="text" name="title" required>
                    </p>
                    <p>コメント
                        <input type="text" name="content">
                    </p>

                    <!-- タグ追加部分 -->
                    <p id="tagform">
                        <label>タグ</label>
                        <input type="text" name="tag" id="tagInput">
                        <ul id="tagList"></ul>
                        <button name="add" type="button" id="append">タグ追加</button>
                        
                        <input type="submit" value="投稿">
                    

                    <div class="torokubu">
                        </p>
                    </div>
                </form>
            </div>
        </center>
    <?php else: ?>
        <p>アカウントが停止されています。</p>
    <?php endif; ?>
<?php else: ?>
    <p>ログインされていません</p>
    <hr>
    <a href="login-input.php">ログインページへ</a>
<?php endif; ?>

<script src="js/jquery-3.7.1.js"></script>
<script>
    $("#append").click(function(){
        var tagValue = $("#tagInput").val();
        var tagCount = $("#tagList li").length;
        var duplicateTag = false;

        $("#tagList li").each(function() {
            if ($(this).text() === tagValue) {
                duplicateTag = true;
                return false; // break the loop
            }
        });

        if(tagValue && tagCount < 10 && !duplicateTag) {
            $("#tagList").append($("<li>").text(tagValue));
            $("#tagform").append($("<input type=\"hidden\" name=\"tags[]\">").val(tagValue));
            $("#tagInput").val("");
        } else if (tagCount >= 10) {
            alert("タグは10個まで追加可能です。");
        } else if (duplicateTag) {
            alert("同じ名前のタグは追加できません。");
        }
    });
</script>
</body>
</html>

<?php
// 出力バッファリングを終了してバッファの内容を出力
ob_end_flush();
?>
