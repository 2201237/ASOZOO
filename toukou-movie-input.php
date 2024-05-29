<<<<<<< HEAD
    <center>
<form enctype="multipart/form-data"  action="toukou-movie-output.php" method="POST">
=======
<?php require "header.php" ?> 

<center>
<form enctype="multipart/form-data" action="toukou-image-output.php" method="POST" onsubmit="return validateFileSize()">
>>>>>>> 0c2994e1166f2f06920c76f1dbe7998d824f5065
    <input type="hidden" name="name" value="value" />

    <p>
    <label>動画 ※10MB以下</label>
    <input id="videoInput" name="user_file_name" type="file" accept="video/*" required/>
    </p>

    <p>
    <label>タイトル</label>
    <input type="text" name="title" required>
    </p>

    <p>
    <label>コメント</label>
    <input type="text" name="content">
    </p>

    <p>
    <label>タグ</label>
    <input type="text" name="tag" id="tagInput">
    <ul id="tagList"></ul>
    <button type="button" id="append">追加</button>
    </p>

    <input type="submit" value="投稿" />
</form>
</center>

<script src="js/jquery-3.7.1.js"></script>
<script>
    $("#append").click(function(){
        var tagValue = $("#tagInput").val();
        if(tagValue) {
            $("#tagList").append($('<li>').text(tagValue));
            $("#tagInput").val(''); // Clear the input after adding the tag
        }
    });

    function validateFileSize() {
        var videoInput = document.getElementById('videoInput');
        if (videoInput.files.length > 0) {
            var fileSize = videoInput.files[0].size / 1024 / 1024; // Size in MB
            if (fileSize > 10) {
                alert('動画ファイルのサイズは10MBまでです。');
                return false;
            }
        }
        return true;
    }
</script>
</body>
</html>
