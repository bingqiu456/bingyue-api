<?php
$uin = $_GET['uin'];
$pskey = $_GET['pskey'];
$group = $_GET['group'];
$uin = $_GET['uin'];
$msg = $_GET['msg'];
$n = $_GET['n'];
$skey = $_GET['skey'];
$typ = $_GET['type'];
$p = $_GET['p'];
$k = $_GET['k'];
function k($k){
    if($k!='1'&$k!='2'){
        return '1';
        exit;
    }else if(!is_numeric($k)){
        return '1';
        exit;
    }else{
        return $k;
        exit;
    }
}
$k = k($k);
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
function type($typ){
    if($typ==''||$typ=='text'){
        return 'text';
    }else{
        return 'json';
    }
}
function p($p){
    if($p==''||!is_numeric($p)){
        return '1';
    }else{
        return $p;
    }
}
$p = p($p);
$type = type($typ);
function sous($msg,$uin,$group,$pskey,$skey,$type,$p){
    $bkn = getGTK($skey);
    $url = 'https://web.qun.qq.com/cgi-bin/media/search_music?g_tk='.$bkn.'&keyword='.$msg.'&page=1&limit=30&gcode='.$group.'&qua=V1_AND_SQ_8.8.50_2324_YYB_D&uin='.$uin.'&format=json&inCharset=utf-8&outCharset=utf-8';
    $ua = 'Mozilla/5.0 (Linux; Android 7.0; TRT-AL00A Build/HUAWEITRT-AL00A; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/64.0.3282.137 Mobile Safari/537.36 V1_AND_SQ_8.8.33_2150_YYB_D A_8083300 QQ/8.8.33.6300 NetType/WIFI WebP/0.4.1 Pixel/720 StatusBarHeight/44 SimpleUISwitch/0 QQTheme/1103 InMagicWin/0 StudyMode/0 CurrentMode/0 CurrentFontScale/1.0';
    $re = 'https://web.qun.qq.com/qunmusic/index?uin='.$group.'&uinType=1&showlrc=1&_wv=2&_wwv=128&isJoin=0&from=0&isNew=1';
    $host  = array(
        "content-type:application/json",
        );
        $cookie="skey=".$skey.";p_skey=".$pskey.";uin=o".$uin.";p_uin=o".$uin;
    $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL,$url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, $host);
   curl_setopt($curl, CURLOPT_COOKIE,$cookie);
   curl_setopt($curl, CURLOPT_REFERER, $re);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($curl, CURLOPT_USERAGENT, $ua);
   $a = curl_exec($curl);
   curl_close($curl);
   $b =  json_decode($a,true);
   $p_end =  $p*10;
    $p_strat = $p*10-9-1;
   switch ($type) {
       case 'text':
           if($b['result']['song_list'][$p_strat]['name']==''){
               echo '搜索失败';
               exit;
           }else{
               echo "冰月点歌列表"."\n";
               for ($i = $p_strat; $i < $p_end; $i++) {
                    $a = $i+1;
                    echo $a.','.$b['result']['song_list'][$i]['name'].' ----- '.$b['result']['song_list'][$i]['singer_list'][0]['name'].PHP_EOL;
               }
               echo '当前为'.$p.'页';
           }
           break;
       case 'json':
           if($b['result']['song_list'][$p_strat]['name']==''){
               echo json_encode(array('code'=>'104','msg'=>'搜索失败'),JSON_UNESCAPED_UNICODE);
               exit;
           }else{
               for ($i = $p_strat; $i < $p_end; $i++) {
                    $abc[] = $b['result']['song_list'][$i]['name'].' ----- '.$b['result']['song_list'][$i]['singer_list'][0]['name'];
               }
               echo json_encode(array(
                   'code'=>'200',
                   'data' =>$abc,
                   'tips'=>'当前为'.$p.'页'
                   ),JSON_UNESCAPED_UNICODE);
           }
           break;
       default:
            if($b['result']['smart_items'][$p_strat]['hint']==''){
               echo '搜索失败';
               exit;
           }else{
               echo "冰月点歌列表"."\n";
               for ($i = $p_strat; $i < $p_end; $i++) {
                    $a = $i+1;
                    echo $a.','.$b['result']['smart_items'][$i]['hint'].PHP_EOL;
               }
               echo '当前为'.$p.'页';
           }
           break;
   }

}
function songid($uin,$pskey,$skey,$group,$msg,$n){
    $bkn = getGTK($skey);
    $url = 'https://web.qun.qq.com/cgi-bin/media/search_music?g_tk='.$bkn.'&keyword='.$msg.'&page=1&limit=30&gcode='.$group.'&qua=V1_AND_SQ_8.8.50_2324_YYB_D&uin='.$uin.'&format=json&inCharset=utf-8&outCharset=utf-8';
    $ua = 'Mozilla/5.0 (Linux; Android 7.0; TRT-AL00A Build/HUAWEITRT-AL00A; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/64.0.3282.137 Mobile Safari/537.36 V1_AND_SQ_8.8.33_2150_YYB_D A_8083300 QQ/8.8.33.6300 NetType/WIFI WebP/0.4.1 Pixel/720 StatusBarHeight/44 SimpleUISwitch/0 QQTheme/1103 InMagicWin/0 StudyMode/0 CurrentMode/0 CurrentFontScale/1.0';
    $re = 'https://web.qun.qq.com/qunmusic/index?uin='.$group.'&uinType=1&showlrc=1&_wv=2&_wwv=128&isJoin=0&from=0&isNew=1';
    $host  = array(
        "content-type:application/json",
        );
        $cookie="skey=".$skey.";p_skey=".$pskey.";uin=o".$uin.";p_uin=o".$uin;
    $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL,$url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, $host);
   curl_setopt($curl, CURLOPT_COOKIE,$cookie);
   curl_setopt($curl, CURLOPT_REFERER, $re);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($curl, CURLOPT_USERAGENT, $ua);
   $a = curl_exec($curl);
   curl_close($curl);
   $B  = json_decode($a,true);
   return $B['result']['song_list'][$n]['songid'];
}
function jiexi($uin,$pskey,$skey,$group,$msg,$n,$type,$k){
     $bkn = getGTK($skey);
     $songid  = songid($uin,$pskey,$skey,$group,$msg,$n);
    $url = 'https://web.qun.qq.com/cgi-bin/media/oper_music?g_tk='.$bkn;
    $ua = 'Mozilla/5.0 (Linux; Android 7.0; TRT-AL00A Build/HUAWEITRT-AL00A; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/64.0.3282.137 Mobile Safari/537.36 V1_AND_SQ_8.8.33_2150_YYB_D A_8083300 QQ/8.8.33.6300 NetType/WIFI WebP/0.4.1 Pixel/720 StatusBarHeight/44 SimpleUISwitch/0 QQTheme/1103 InMagicWin/0 StudyMode/0 CurrentMode/0 CurrentFontScale/1.0';
    $re = 'https://web.qun.qq.com/qunmusic/index?uin='.$group.'&uinType=1&showlrc=1&_wv=2&_wwv=128&isJoin=0&from=0&isNew=1';
    $host  = array(
        "content-type:application/x-www-form-urlencoded",
        );
        $cookie="skey=".$skey.";p_skey=".$pskey.";uin=o".$uin.";p_uin=o".$uin;
    $post = 'oper_type='.$k.'&song_list=[{"song_id":"'.$songid.'"}]&gcode='.$group.'&qua=V1_AND_SQ_8.8.50_2324_YYB_D&uin='.$uin.'&format=json&inCharset=utf-8&outCharset=utf-8';
    $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL,$url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, $host);
   curl_setopt($curl, CURLOPT_COOKIE,$cookie);
   curl_setopt($curl, CURLOPT_REFERER, $re);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($curl,CURLOPT_POST,1);
   curl_setopt($curl,CURLOPT_POSTFIELDS,$post);
   curl_setopt($curl, CURLOPT_USERAGENT, $ua);
   $a = curl_exec($curl);
   curl_close($curl);
   $B  = json_decode($a,true);
   switch ($type) {
       case 'text':
           if($B['retcode']!='0'){
               echo "失败";
               exit;
           }else{
               echo '成功';
           }
           break;
        case 'json':
            if($B['retcode']!='0'){
                echo json_encode(array('code'=>'104','msg'=>'失败'),JSON_UNESCAPED_UNICODE);
                exit;
            }else{
                echo json_encode(array('code'=>'200','msg'=>'成功'),JSON_UNESCAPED_UNICODE);
            }
            break;
       default:
            if($B['retcode']!='0'){
               echo "失败";
               exit;
           }else{
               echo '成功';
           }
           break;
   }
}
function cuowu($type){
    if($type==''||$type=='text'){
        return '参数不完整';
        exit;
    }else{
     return json_encode(array('code'=>'104','msg'=>'参数不完整'),JSON_UNESCAPED_UNICODE);
        exit;
    }
    
}
if($uin==''||$group==''||$skey==''||$pskey==''){
    echo cuowu($type);
}else if(!is_numeric($n)){
    echo sous($msg,$uin,$group,$pskey,$skey,$type,$p);
}else{
    echo jiexi($uin,$pskey,$skey,$group,$msg,$n,$type,$k);
}
?>