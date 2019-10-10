<?php 

ini_set('log_errors','on'); // ログを取るか
ini_set('error_log','php_error.log'); //ログファイルの出力先
session_start();

$debug_flg = true;
function debug($str){
  global $debug_flg;
  if($debug_flg) error_log($str);
}

function createMonster(){
  global $monsters;
  // debug('$monsters変数：');
  // debug(print_r($monsters,true));
  $_SESSION['monster'] = $monsters[mt_rand(0,count($monsters)-1)];
  // debug(print_r($_SESSION,true));
  History::set($_SESSION['monster']->getName().MSG09);

}
function createHuman(){
  global $humans;
  // $_SESSION['human'] = $humans[mt_rand(0,count($humans)-1)];
  $_SESSION['human'] = $humans[1];  
}

function init(){
  $battleFlg = true;
  History::clear();
  History::set(MSG10);
  $_SESSION['knockDownCount'] = 0;
  createHuman();
  createMonster();
}
function gameOver(){
  $battleFlg = false;
  $_POST = array();
  $_SESSION = array();
}



?>　