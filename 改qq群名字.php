<?php
## 本api作者 滨河！
## 本api仅供学习交流 请勿倒卖谢谢 留个作者名字吧
$skey = $_REQUEST['skey'];
$pskey = $_REQUEST['pskey'];
$uin = $_REQUEST['uin'];
$group = $_REQUEST['group'];
$gcname = $_REQUEST['name'];
$type = $_REQUEST['type'];
if($skey==''||$pskey==''||$uin==''||$group==''||$gcname==''){
    echo "请把参数补全";
    exit;
}else if(!is_numeric($uin)||!is_numeric($group)){
    echo "输入的不是纯数字";
    exit;
}
function getGTK($skey){
    $hash = 5381;
    for($i=0;$i<strlen($skey);++$i){
        $hash += ($hash<<5) + utf8_uni($skey[$i]);
    }
    return $hash & 0x7fffffff;
}
function utf8_uni($u){
    switch (strlen($u)) {
        case 1:
            return ord($u);
        case 2:
            $n = (ord($u[0]) & 0x3f) << 6;
            $n += ord($u[1]) & 0x3f;
        case 3:
            $n = (ord($u[0]) & 0x1f) << 12;
            $n += (ord($u[0]) & 0x3f) << 6;
            $n += ord($u[2]) & 0x3f;
            return $n;
        case 4:
            $n = (ord($u[0]) & 0x0f) << 18;
            $n += (ord($u[1]) & 0x3f) << 12;
            $n += (ord($u[2]) & 0x3f) << 6;
            $n += ord($u[3]) & 0x3f;
            return $n;
    }
}
function curl($skey,$pskey,$uin,$group){
    $bkn = getGTK($skey);
    $ua = 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) QQ/9.4.3.27712 Chrome/43.0.2357.134 Safari/537.36 QBCore/3.43.1298.400 QQBrowser/9.0.2524.400';
    $url = "https://qinfo.clt.qq.com/cgi-bin/qun_info/get_group_info_all?src=qinfo_v3&gc=$group&bkn=$bkn&t=basic&from=1";
    $cookie="skey=".$skey.";p_skey=".$pskey.";uin=o".$uin.";p_uin=o".$uin;
   $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL,$url);
   curl_setopt($curl, CURLOPT_COOKIE,$cookie);
    curl_setopt($curl, CURLOPT_REFERER, "https://qinfo.clt.qq.com/qinfo_v3/profile.html?groupuin=$group");
   curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($curl, CURLOPT_USERAGENT, $ua);
   $a = curl_exec($curl);
   curl_close($curl);
   return $a;
}
function unicodeDecode($name){
$json = '{"str":"'.$name.'"}';
$arr = json_decode($json,true);
if(empty($arr)) return '';
 return $arr['str'];
}
$data_json =  json_decode(curl($skey,$pskey,$uin,$group),true);
$name =  $data_json['gIntro'];
$yuan = unicodeDecode($name);
function gaiming($skey,$pskey,$uin,$group,$yuan,$gcname){
    $bkn = getGTK($skey);
    $url = "https://qinfo.clt.qq.com/cgi-bin/qun_info/set_group_info_new";
    $host = array(
        'Content-Type: application/x-www-form-urlencoded'
        );
    $postdata = "src=qinfo_v3&gc=$group&bkn=$bkn&fOthers=1&gName=$gcname&gIntro=$yuan&gRIntro=$yuan&gRemark=0&nWeb=1";
    $ua = 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) QQ/9.5.0.27852 Chrome/43.0.2357.134 Safari/537.36 QBCore/3.43.1298.400 QQBrowser/9.0.2524.400';
     $cookie="skey=".$skey.";p_skey=".$pskey.";uin=o".$uin.";p_uin=o".$uin;
  $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL,$url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, $host);
   curl_setopt($curl, CURLOPT_COOKIE,$cookie);
   curl_setopt($curl, CURLOPT_REFERER, "https://qinfo.clt.qq.com/qinfo_v3/profile.html?groupuin=$group");
   curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($curl,CURLOPT_POST,1);
   curl_setopt($curl,CURLOPT_POSTFIELDS,$postdata);
   curl_setopt($curl, CURLOPT_USERAGENT, $ua);
   $a = curl_exec($curl);
   curl_close($curl);
   return $a;
}
$data_json_2 = json_decode(gaiming($skey,$pskey,$uin,$group,$yuan,$gcname),true);
switch ($type) {
    case 'text':
        if($data_json_2['ec']!='0'){
            echo "请检查参数是否失效！";
            exit;
        }else{
            echo "更改群名成功";
        }
        break;
    case 'json':
        if($data_json_2['ec']!='0'){
            echo json_encode(array('msg'=>'请检查参数是否失效'),JSON_UNESCAPED_UNICODE);
            exit;
        }else{
            echo json_encode(array('msg'=>'更改群名成功'),JSON_UNESCAPED_UNICODE);
        }
        break;
    default:
          if($data_json_2['ec']!='0'){
            echo "请检查参数是否失效！";
            exit;
        }else{
            echo "更改群名成功";
        }
        break;
} 
?>