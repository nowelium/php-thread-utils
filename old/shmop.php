<?php

$shm_id = shmop_open(0xff3, "c", 0644, 100);
if (!$shm_id) {
       echo "共有メモリセグメントを作成できませんでした。\n";
}
var_dump($shm_id);
