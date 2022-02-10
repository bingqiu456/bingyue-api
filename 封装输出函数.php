<?php
## 封装几个输出函数而已
function send($msg,$nbc){
	if($nbc==''){
		return $msg;
	}else if($nbc=='1'){
		return str_replace('\/','/' , $msg);
	}else if($nbc=='2'){
		return str_replace('/', '\/', $msg)
	}
}
function sned_json($msg){
	return json_encode($msg,JSON_UNESCAPED_UNICODE);
}
?>