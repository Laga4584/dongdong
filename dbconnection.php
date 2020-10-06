<?php
$link = mysqli_connect('unigls-db.cluster-ckav56uokaxp.ap-northeast-2.rds.amazonaws.com', 'admin', 'a56095609');
if (!$link) {
die('Could not connect: ' . mysqli_error());
}
echo 'Connected successfully';
mysqli_close($link);
?>