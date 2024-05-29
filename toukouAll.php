<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* 画像のサイズを調整するためのスタイル */
        .image-gallery img {
            width: 200px; /* 画像の幅を指定 */
            height: auto; /* 高さは自動調整 */
            margin: 5px; /* 画像の間隔を指定 */
        }
    </style>
    <title>Document</title>
</head>
<body>

<?php include('header.php'); ?>

<div class="image-gallery">
    <?php
    // 画像フォルダのパス
    $image_folder = 'img/';
    
    // 画像フォルダ内のファイルを取得
    $images = glob($image_folder . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    
    // 画像を表示
    foreach ($images as $image) {
        echo '<img src="' . $image . '" alt="画像">';
    }
    ?>
</div>

</body>
</html>
