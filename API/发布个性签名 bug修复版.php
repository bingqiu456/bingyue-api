<?php
##作者 滨河！
##本api仅学习交流 请勿用于贩卖
$skey = $_GET['skey'];
$pskey = $_GET['pskey'];
$uin = $_GET['uin'];
$msg = $_GET['msg'];
$type= $_GET['type'];
if($skey==''||$pskey==''||$uin==''||$msg==''){
    echo "请检查参数是否完整";
    exit;
}else if(!is_numeric($uin)){
    echo "不是纯数字的qq号";
    exit;
}
function curl($skey,$pskey,$uin,$msg,$ldw){
    $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36';
    $cookie="skey=".$skey.";p_skey=".$pskey.";uin=o".$uin.";p_uin=o".$uin.";ldw=".$ldw;
    $url = "https://id.qq.com/cgi-bin/userinfo_mod";
    $postdata = '&ln='.$msg.'&ldw='.$ldw;
    $host = array(
        'content-type: text/plain;charset=UTF-8'
        );
    $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL,$url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, $host);
   curl_setopt($curl, CURLOPT_COOKIE,$cookie);
   curl_setopt($curl, CURLOPT_REFERER, "https://id.qq.com/myself/myself.html?ver=10045&");
   curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($curl,CURLOPT_POST,1);
   curl_setopt($curl,CURLOPT_POSTFIELDS,$postdata);
   curl_setopt($curl, CURLOPT_USERAGENT, $ua);
   $a = curl_exec($curl);
   curl_close($curl);
   return $a;
}
function get_ldw($skey,$uin,$pskey){
    $url = 'https://id.qq.com/cgi-bin/get_base_key?r=0.18776368348709482';
    $cookie="skey=".$skey.";p_skey=".$pskey.";uin=o".$uin.";p_uin=o".$uin;
     $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36';
     $re = "https://id.qq.com/index.html";
    $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL,$url);
   curl_setopt($curl, CURLOPT_COOKIE,$cookie);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($curl,CURLOPT_REFERER,$re);
   curl_setopt($curl, CURLOPT_USERAGENT, $ua);
   curl_setopt($curl, CURLOPT_COOKIEJAR,'123.txt');
   $a = curl_exec($curl);
   curl_close($curl);
   return $a;
}
get_ldw($skey,$uin,$pskey);
$file = file("123.txt");
$a = $file[4];
$b =  str_replace('.id.qq.com	TRUE	/	FALSE	0	ldw	','',$a);
$ldw  = str_replace(array("\n","\r","\n\r"),'',$b);
$data_json =  json_decode(curl($skey,$pskey,$uin,$msg,$ldw),true);
switch ($type) {
    case 'text':
        if($data_json['ec']!='0'){
            echo "请检查参数是否过期！";
            exit;
        }else{
            echo "更改个性签名成功";
        }
        break;
    case 'json':
        if($data_json['ec']!='0'){
            echo json_encode(array('msg'=>'请检查参数是否过期'),JSON_UNESCAPED_UNICODE);
            exit;
        }else{
            echo json_encode(array('msg'=>'更改个性签名成功'),JSON_UNESCAPED_UNICODE);
        }
        break;
    default:
          if($data_json['ec']!='0'){
            echo "请检查参数是否过期！";
            exit;
        }else{
            echo "更改个性签名成功";
        }
        break;
}
?>