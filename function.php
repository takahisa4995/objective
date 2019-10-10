<?php 
function createMonster(){
  global $monsters;
  debug('$monsters変数：');
  debug(print_r($monsters,true));
  $_SESSION['monster'] = $monsters[mt_rand(0,count($monsters)-1)];
  debug(print_r($_SESSION,true));
  History::set($_SESSION['monster']->getName().MSG09);

}
function createHuman(){
  global $humans;
  // $_SESSION['human'] = $humans[mt_rand(0,count($humans)-1)];
  $_SESSION['human'] = $humans[1];  
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



?>　