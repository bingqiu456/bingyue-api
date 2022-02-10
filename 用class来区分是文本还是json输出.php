<?php
	/**
  * 作者:滨河！
  * 这是是用class来判断json还是文本输出
  * 刚学class 还不是很明白
  * 但是在class里的function（好像？）不能用exit;
  */
$type = $_GET['type'];
$n = $_GET['n'];
class Site {
  function chuli($type){
     if($type=='text'||$type==''){
         echo '文本输出';
     }else{
         echo 'json输出';
     }
  }
}
$b = new Site;
$b->chuli($type);
?>