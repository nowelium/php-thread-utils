<?php
// 呼び出されるとその時間を記録する関数
function profile ($dump = FALSE)
{
   static $profile;

   // 格納されたプロファイルを返し、その値を削除する
   if ($dump) {
       $temp = $profile;
       unset($profile);
       return $temp;
   }

   $profile[] = microtime();
}

// tickハンドラの設定
register_tick_function("profile");

// declareブロックの前で初期化しておく
profile ();

// 2命令ごとにtickを投げるように設定しブロックを実行する
declare (ticks=2) {
   for ($x = 1; $x < 50; ++$x) {
       echo similar_text (md5($x), md5($x*$x)), PHP_EOL;
   }
}

// プロファイラに格納されたデータを表示する
print_r (profile(TRUE));
?>
