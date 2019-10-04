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
  $_SESSION['monster'] = $monsters[mt_rand(0,7)];
  History::set($_SESSION['monster']->getName().MSG09);

}
function createHuman(){
  global $humans;
  $_SESSION['human'] = $humans[mt_rand(0,1)];
}

function init(){
  History::clear();
  History::set(MSG10);
  $_SESSION['knockDownCount'] = 0;
  createHuman();
  createMonster();
}
function gameOver(){
  $_POST = array();
  $_SESSION = array();
}

//POST送信
if(!empty($_POST)){
  debug('POST送信開始');
  $attackFlg = (!empty($_POST['attack'])) ? true : false;
  $startFlg = (!empty($_POST['start'])) ? true : false;
  $escapeFlg = (!empty($_POST['escape'])) ? true : false;
  debug('ーーーーーーーーーーーーーーーーーーーー');
  debug('atatckFlg:'.$attackFlg);
  debug('startFlg:'.$startFlg);
  debug('escapeFlg:'.$escapeFlg);
  debug('POST送信');
  debug(print_r($_POST,true));
  debug('セッション情報を表示');
  debug(print_r($_SESSION,true));
  debug('ーーーーーーーーーーーーーーーーーーーー');


if($startFlg){
    //ゲームスタート又はリセット
    History::set(MSG11);
    init();
}else if($attackFlg){
    //攻撃するを選択した場合

    //プレイヤー側の攻撃
    History::set($_SESSION['human']->getName().MSG12);
    $_SESSION['human']->attack($_SESSION['monster']);
    $_SESSION['monster']->sayCry();

    //モンスターの攻撃
    History::set($_SESSION['monster']->getName().MSG12);
    $_SESSION['monster']->attack($_SESSION['human']);
    $_SESSION['human']->sayCry();
    
    //モンスター側のHPが0以下なら、次のモンスターをリスポーン
    if($_SESSION['monster']->getHp() <= 0) {
        History::set($_SESSION['monster']->getName().MSG13);
        createMonster();
        ++$_SESSION['knockDownCount'];
    }
    //プレイヤー側のHPが0以下でゲームオーバー
    if($_SESSION['human']->getHp() <= 0) gameOver();

  }else if($escape){
      //逃げた場合
      History::set(MSG14);
      createMonster();
  }

  $_POST = array();
}

?>　