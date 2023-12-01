<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/style/style.css">
    <title>Document</title>
</head>
<body>
<?php
echo '<div class="list">';
foreach ($comments as $comment) {
    echo '<a class="list__item" href="/?id=' . $comment['id'] . '">';
    echo '<div>' . $comment['title'] . '</div>';
    echo '<div>' . $comment['summary'] . '</div>';
    echo '</a>';
}
echo '</div>';

require './view/form.php';

echo '<div class="paginate">';
for ($i = 0; $i < $totalCountPage; $i++) {
    echo '<a class="paginate__page' . (($i + 1 == $page) ? ' active' : '') . '" href="/?page=' . ($i + 1) . '">' . ($i + 1) . '</a>';
}
echo '</div>'
?>

</body>
</html>