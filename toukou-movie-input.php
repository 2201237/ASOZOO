<?php
// 出力バッファリングを開始
ob_start();
// セッションの開始
session_start();
?>
<?php require "header.php" ?>
<link rel="stylesheet" href="css/movie.css">

<?php if (isset($_SESSION['User']) && isset($_SESSION['User']['user_id'])): ?>
    <?php if ($_SESSION['User']['user_icon'] !== 'ban.png'): ?>
        <center>
            <form enctype="multipart/form-data" action="toukou-movie-output.php" method="POST" onsubmit="return validateFileSize()">
                <input type="hidden" name="name" value="value" />

                <p>
                    <label>動画 ※10MB以下※正常に作動しない場合があります</label>
                    <input id="videoInput" name="user_video_file" type="file" accept="video/*" required/>
                </p>

                <p>
                    <label>タイトル</label>
                    <input type="text" name="title" required>
                </p>

                <p>
                    <label>コメント</label>
                    <input type="text" name="content">
                </p>

                <!-- タグ追加部分 -->
                <p id="tagform">
                    <label>タグ</label>
                    <input type="text" name="tag" id="tagInput">
                    <ul id="tagList"></ul>
                    <button name="add" type="button" id="append">タグ追加</button>
                    <input type="submit" value="投稿" />
                </p>

            </form>
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

    function validateFileSize() {
        var fileInput = document.getElementById('videoInput');
        var file = fileInput.files[0];
        if (file.size > 10485760) { // 10MB = 10 * 1024 * 1024 bytes
            alert('ファイルサイズが10MBを超えています。');
            return false;
        }
        return true;
    }
</script>

</body>
</html>

<?php
// 出力バッファリングを終了してバッファの内容を出力
ob_end_flush();
?>
