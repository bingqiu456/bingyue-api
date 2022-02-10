<?php
require "../function.php"; // 引入函数文件
addApiAccess(42); 
 ?>
<?php
$skey = $_GET['skey'];
$pskey = $_GET['pskey'];
$group = $_GET['group'];
$uin = $_GET['uin'];
$k = $_GET['k'];
if($skey==''||$pskey==''||$group==''||$uin==''){
    echo "请把参数补全谢谢";
    exit;
} else if(!is_numeric($group)||!is_numeric($uin)){
    echo "请输入纯数字！";
    exit;
}
function k($k){
    if($k=='1'){
        return 'robots_set';
        exit;
    } else if($k=='2'){
         return 'robots_close';
         exit;
    } else {
        return 'robots_set';
        exit;
    }
}
## 1是邀请 2是踢
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
function curl($group,$uin,$skey,$pskey,$k){
    $robot = k($k);
    $bkn = getGTK($skey);
    $url = "https://web.qun.qq.com/qunrobot/proxy/domain/qun.qq.com/cgi-bin/qunapp/$robot?bkn=$bkn";
    $ua = 'Mozilla/5.0 (Linux; Android 7.0; TRT-AL00A Build/HUAWEITRT-AL00A; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/64.0.3282.137 Mobile Safari/537.36 V1_AND_SQ_8.8.33_2150_YYB_D A_8083300 QQ/8.8.33.6300 NetType/WIFI WebP/0.4.1 Pixel/720 StatusBarHeight/44 SimpleUISwitch/0 QQTheme/1103 InMagicWin/0 StudyMode/0 CurrentMode/0 CurrentFontScale/1.0';
    $host = array(
        'qname-space:Production',
        'qname-service:976321:131072',
        );
   $cookie="skey=".$skey.";p_skey=".$pskey.";uin=o".$uin.";p_uin=o".$uin;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL,$url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $host);
    curl_setopt($curl, CURLOPT_COOKIE,$cookie);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl,CURLOPT_POST,1);
    curl_setopt($curl,CURLOPT_POSTFIELDS,"gc=$group&robot_uin=2854196310");
    curl_setopt($curl, CURLOPT_USERAGENT, $ua);
    $a = curl_exec($curl);
    curl_close($curl);
    return $a;
}
$data =  curl($group,$uin,$skey,$pskey,$k);
$data_json = json_decode($data,true);
if($data_json['retcode']=='0'&k($k)=='robots_set'){
    echo "邀请q群管家成功！";
    exit;
} else if($data_json['retcode']=='0'&k($k)=='robots_close'){
    echo "踢出q群管家成功！";
    exit;
}else if($data_json['retcode']=='10025'&k($k)=='robots_set'){
    echo "q群管家已经存在";
    exit;
}else if($data_json['retcode']=='10010'&k($k)=='robots_close'){
        echo "q群管家不存在！";
        exit;
}else {
    echo "参数失效 请检查pskey和skey";
    exit;
    
}
?>