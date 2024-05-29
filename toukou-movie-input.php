    <center>
<form enctype="multipart/form-data"  action="toukou-image-output.php" method="POST">
    <input type="hidden" name="name" value="value" />

    <p>
    <label>動画</label>
    <input name="user_file_name" type="file" />
    </p>

    <p>
    <label>タイトル</label>
    <input type="text" name="title">
    </p>

    <p>
    <label>コメント</label>
    <input type="text" name="content">
    </p>

    <p id="tagform">
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
            $("#tagform").append($('<input type="hidden" name="tags[]">').val(tagValue));
            $("#tagInput").val('');
        }
    });
</script>
</body>
</html>



