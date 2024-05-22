<?php require "header.php" ?>
<body>
    <center>
<form enctype="multipart/form-data"  action="toukou-image-output.php" method="POST">
    <input type="hidden" name="name" value="value" />

    <p>
    <label>写真</label>
    <input name="user_file_name" type="file" />
    </p>

    <p>
    <label>タイトル</label>
    <input type="text" name="title">
    </p>

    <p>
    <label>コメント</label>
    <input type="password" name="comment">
    </p>

    <p>
    <label>タグ</label>
    <input type="text" name="tag">
    </p>




<input type="submit" value="投稿" />
</form>
</center>
</body>
</html>



