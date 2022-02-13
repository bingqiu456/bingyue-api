<?php
## 作者：滨河 https://apii.bingyue.xyz
$skey = $_GET['skey'];
$pskey = $_GET['pskey'];
$uin = $_GET['uin'];
$p = $_GET['p'];
$type = $_GET['type'];
if($skey==''||$pskey==''||$uin==''){
    echo "请把参数补全！";
    exit;
} else if(!is_numeric($uin)){
    echo "请输入纯数字谢谢！";
    exit;
}
function p($p){
    if($p==''||!is_numeric($p)){
        return '1';
    } else {
        return $p;
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
function curl($uin,$pskey,$skey){
    $ua = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.81 Safari/537.36";
    $bkn = getGTK($skey);
    $reon = "https://qun.qq.com/join.html";
    $cookie="skey=".$skey.";p_skey=".$pskey.";uin=o".$uin.";p_uin=o".$uin;
    $url = "https://qun.qq.com/cgi-bin/qun_mgr/get_group_list?bkn=$bkn";
     $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL,$url);
   curl_setopt($curl, CURLOPT_COOKIE,$cookie);
   curl_setopt($curl, CURLOPT_REFERER, $reon);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($curl, CURLOPT_USERAGENT, $ua);
   $a = curl_exec($curl);
   curl_close($curl);
   return $a;
}
$p_2 = p($p);
$p_2_end = $p_2*10;
$p_2_strat = $p_2*10-9-1;
$data =  curl($uin,$pskey,$skey);
$data_2 = json_decode($data,true);
$data_json = json_encode($data_2,JSON_UNESCAPED_UNICODE);
$data_json_2 = json_decode($data_json,true);
$count = count($data_json_2['join'],0);
switch ($type) {
    case 'text':
    if($data_json_2['ec']!='0'){
    echo "请检查你的skey和pskey";
    exit;
    } if($data_json_2['join'][$p_2_strat]['gn']==''){
        echo "没群列表";
        exit;
        }else{
     echo "-----冰月列表-----"."\n";
        $p = count($data_json_2['join'],0);
        $p_2_end = shu($p,$p_2);
        for ($i = $p_2_strat; $i < $p_2_end; $i++) {
        $a = $i+1;
     echo $a."群名:".$data_json_2['join'][$i]['gn'].' ----- ',"群号:".$data_json_2['join'][$i]['gc']." ------ ","群主q号:".$data_json_2['join'][$i]['owner'].PHP_EOL;
}
    echo "-----------------","以下是你的群列表！","当前为".$p_2."页";
    }
        break;
    case 'json':
        if($data_json_2['ec']!='0'){
            echo json_encode(array('code'=>'104','msg'=>'请检查你的skey和pskey'),JSON_UNESCAPED_UNICODE);
        }if($data_json_2['join'][$p_2_strat]['gn']==''){
            echo json_encode(array('code'=>'104','msg'=>'本群还没群列表'),JSON_UNESCAPED_UNICODE);
            exit;
            }
            else{
            $p = count($data_json_2['join'],0);
            $p_2_end = shu($p,$p_2);
            for ($i = $p_2_strat; $i < $p_2_end; $i++) {
            $abc[] = $data_json_2['join'][$i]['gn'];
            $b[] =  $data_json_2['join'][$i]['gc'];
            $c[] = $data_json_2['join'][$i]['owner'];
}
    $b = array(
        'code'=>'200',
        'msg'=>'获取成功',
        'qun' => $abc,
        'gccode' => $b,
        'gcqq' => $c,
        'tips'=>'当前是第'.$p_2.'页'
        );
        echo json_encode($b,JSON_UNESCAPED_UNICODE);
        }
        break;
    default:
          if($data_json_2['ec']!='0'){
        echo "请检查你的skey和pskey";
        exit;
    } else if($data_json_2['join'][$p_2_strat]['gn']==''){
        echo "没列表";
        exit;
        }else{
        $p = count($data_json_2['join'],0);
        $p_2_end = shu($p,$p_2);
        echo "-----冰月列表-----"."\n";
    for ($i = $p_2_strat; $i < $p_2_end; $i++) {
    $a = $i+1;
     echo $a."群名:".$data_json_2['join'][$i]['gn'].' ----- ',"群号:".$data_json_2['join'][$i]['gc']." ------ ","群主q号:".$data_json_2['join'][$i]['owner'].PHP_EOL;
}
    echo "-----------------","以下是你的群列表！","当前为".$p_2."页";
    }
        break;
}
function shu($p,$p_2){
    if($p<$p_2*10){
        $b = $p_2-1;
        $c = $p_2*10-$p;
        $d = $p_2*10-$c;
        return $d;
        exit;
    }else{
       return $p_2*10;
       exit;
    }
}
?>