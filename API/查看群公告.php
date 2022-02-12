<?php
## 作者：滨河
## https://apii.bingyue.xyz
$msg = $_GET['group'];
$skey = $_GET['skey'];
$pskey = $_GET['pskey'];
$uin = $_GET['uin'];
$type = $_GET['type'];
$p = $_GET['p'];
$p = p($p);
if($msg==''||$skey==''||$pskey==''||$uin==''){
    echo cuowu($type);
    exit;
}
function cuowu($type){
    if($type==''||$type=='text'){
        echo "请检查参数";
        exit;
    }else{
        echo json_encode(array('code'=>'104','msg'=>'请检查参数'),JSON_UNESCAPED_UNICODE);
        exit;
    }
}
function p($p){
    if($p==''||!is_numeric($p)){
        return '1';
        exit;
    }else{
        return '2';
        exit;
    }
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

function curl_http($skey,$pskey,$uin,$msg){
    $bkn = getGTK($skey);
    $url = 'https://web.qun.qq.com/cgi-bin/announce/list_announce?bkn='.$bkn;
    $post_data = 'qid='.$msg.'&bkn='.$bkn.'&ft=23&s=-1&n=10&i=1&ni=1';
    $ua = 'Mozilla/5.0 (Linux; Android 11; 10Pro Build/RQ3A.211001.001; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/89.0.4389.72 MQQBrowser/6.2 TBS/045912 Mobile Safari/537.36 V1_AND_SQ_8.8.50_2324_YYB_D A_8085000 QQ/8.8.50.6735 NetType/WIFI WebP/0.3.0 Pixel/1080 StatusBarHeight/50 SimpleUISwitch/0 QQTheme/999 InMagicWin/0 StudyMode/0 CurrentMode/0 CurrentFontScale/1.0';
     $cookie="skey=".$skey.";p_skey=".$pskey.";uin=o".$uin.";p_uin=o".$uin;
    $re = 'https://web.qun.qq.com/mannounce/index.html?_wv=1031&_bid=148'; $host = array('Content-Type:application/x-www-form-urlencoded');
      $curl = curl_init();
    $ch = array(
        CURLOPT_URL=>$url,
        CURLOPT_HTTPHEADER=>$host,
        CURLOPT_COOKIE => $cookie,
        CURLOPT_RETURNTRANSFER=>1,
        CURLOPT_REFERER=>$re,
        CURLOPT_POST=>1,
        CURLOPT_POSTFIELDS=>$post_data,
        CURLOPT_USERAGENT=>$ua,
        );
    curl_setopt_array($curl, $ch);
    $result = curl_exec($curl);
    return $result;
}
$p_end =  $p*10;
$p_strat  = $p*10-9-1;
$data =  curl_http($skey,$pskey,$uin,$msg);
$json = json_decode($data,true);
switch ($type) {
    case 'text':
        if($json['ec']!='0'){
            echo "请检查参数";
            exit;
        }else if($json['feeds'][$p_strat]['msg']['text']==''){
            echo "本群还没群公告";
            exit;
        }else{
            $a = count($json['feeds'],0);
            $p_end = shu($a,$p);
            echo "————冰月列表————"."\n";
            for ($i = $p_strat; $i < $p_end; $i++) {
                $a = $i+1;
                 echo $a.','.$json['feeds'][$i]['msg']['text'].' ----- '.$json['feeds'][$i]['u'].PHP_EOL;
            }
            echo "提示:当前为第".$p."页";
        }
        break;
    case 'json':
        if($json['ec']!='0'){
            echo json_encode(array('code'=>'104','msg'=>'请检查参数'),JSON_UNESCAPED_UNICODE);
            exit;
        }else if($json['feeds'][$p_strat]['msg']['text']==''){
            echo json_encode(array('code'=>'104','msg'=>'本群还没群公告'),JSON_UNESCAPED_UNICODE);
            exit;
        }else{
            $a = count($json['feeds'],0);
            $p_end = shu($a,$p);
            for ($i = $p_strat; $i < $p_end; $i++) {
                 $abc[] = $json['feeds'][$i]['msg']['text'];
                 $abcd[] = $json['feeds'][$i]['u'];
            }
            echo json_encode(array('code'=>'200','data'=>$abc,'qq'=>$abcd),JSON_UNESCAPED_UNICODE);
        }
        break;
    default:
          if($json['ec']!='0'){
            echo "请检查参数";
            exit;
        }else if($json['feeds'][$p_strat]['msg']['text']==''){
            echo "本群还没群公告";
            exit;
        }else{
            $a = count($json['feeds'],0);
            $p_end = shu($a,$p);
            echo "————冰月列表————"."\n";
            for ($i = $p_strat; $i < $p_end; $i++) {
                $a = $i+1;
                 echo $a.','.$json['feeds'][$i]['msg']['text'].' ----- '.$json['feeds'][$i]['u'].PHP_EOL;
            }
            echo "提示:当前为第".$p."页";
        }
        break;
}
function shu($a,$p){
    if($a<10){
        return $a;
        exit;
    }else{
       return $p*10;
       exit;
    }
}
?>