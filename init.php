<?php

ini_set('log_errors','on'); // ログを取るか
ini_set('error_log','php_error.log'); //ログファイルの出力先
session_start();

$debug_flg = true;
function debug($str){
  global $debug_flg;
  if($debug_flg) error_log($str);
}

?>