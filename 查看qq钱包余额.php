<?php
	/**
  * 冰月API https://apii.bingyue.xyz
  * 作者:滨河！
  * 祝大家新年快乐!!!
  */
$skey  = $_REQUEST['skey'];
$pskey = $_REQUEST['pskey'];
$uin = $_REQUEST['uin'];
$type = $_REQUEST['type'];
function curl($pskey,$skey,$uin){
    $url = 'https://myun.tenpay.com/cgi-bin/clientv1.0/cancel_query.cgi?uin='.$uin.'&skey='.$skey.'&pskey='.$pskey.'&skey_type=2';
    $ua = 'Mozilla/5.0 (Linux; Android 11; 冰月api 高速 稳定 Build/RQ3A.211001.001; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/89.0.4389.72 MQQBrowser/6.2 TBS/045912 Mobile Safari/537.36 V1_AND_SQ_8.8.50_2324_YYB_D A_8085000 QQ/8.8.50.6735 NetType/WIFI WebP/0.3.0 Pixel/1080 StatusBarHeight/50 SimpleUISwitch/0 QQTheme/999 InMagicWin/0 StudyMode/0 CurrentMode/0 CurrentFontScale/1.0
';
    $host = array(
        'content-type: text/plain;charset=UTF-8'
        );
     $cookie="skey=".$skey.";p_skey=".$pskey.";uin=o".$uin.";p_uin=o".$uin;
     $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL,$url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, $host);
   curl_setopt($curl, CURLOPT_COOKIE,$cookie);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($curl, CURLOPT_USERAGENT, $ua);
   $a = curl_exec($curl);
   curl_close($curl);
   return $a;
}
$data =  curl($pskey,$skey,$uin);
function jiexi_yue($data,$type){
    $data_json = json_decode($data,true);
    $yue = $data_json['balance'];
    $e = $yue/100;
    switch ($type) {
        case 'json':
            if($data_json['retcode']!='0'){
                echo json_encode(array('msg'=>'登录失败，请检查参数'),JSON_UNESCAPED_UNICODE);
                exit;
            }else{
                echo json_encode(array('msg'=>'登陆成功','yue'=>$e),JSON_UNESCAPED_UNICODE);
            }
            break;
        case 'text':
            if($data_json['retcode']!='0'){
                echo '登录失败';
                exit;
            }else{
                echo '你的钱包余额为'.$e.'元';
            }
            break;
        default:
              if($data_json['retcode']!='0'){
                echo '登录失败';
                exit;
            }else{
                echo '你的钱包余额为'.$e.'元';
            }
            break;
    }
}
if($uin==''||$pskey==''||$skey==''){
    echo "参数缺失";
    exit;
}else if(!is_numeric($uin)){
    echo "输入的qq号不是纯数字";
    exit;
}else{
echo jiexi_yue($data,$type);
}
?>