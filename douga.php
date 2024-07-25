<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>動画の表示</title>
    <link rel="stylesheet" href="a.css">
</head>
<body>
    <h1>動画の表示</h1>
    
    <?php
    // 動画ファイルが含まれるディレクトリのパス
    $directory = "img/";

    // ディレクトリ内の動画ファイルを取得
    $videos = glob($directory . "*.mov");

    // 動画を表示するためのループ
    foreach($videos as $video) {
        echo '<video width="640" height="360" controls>';
        echo '<source src="' . $video . '" type="video/mp4">';
        echo 'Your browser does not support the video tag.';
        echo '</video>';
    }
    
    ?>
    <a href="form.php">動画アップロード</a>

</body>
</html>
