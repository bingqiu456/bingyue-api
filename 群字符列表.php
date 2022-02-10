<?php
$skey = $_GET['skey'];
$pskey = $_GET['pskey'];
$uin = $_GET['uin'];
$group = $_GET['group'];
$p = $_GET['p'];
$type = $_GET['type'];
function bkn($skey){
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
    $bkn = bkn($skey);
    $ua = 'Mozilla/5.0 (Linux; Android 7.0; TRT-AL00A Build/HUAWEITRT-AL00A; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/64.0.3282.137 Mobile Safari/537.36 V1_AND_SQ_8.8.33_2150_YYB_D A_8083300 QQ/8.8.33.6300 NetType/WIFI WebP/0.4.1 Pixel/720 StatusBarHeight/44 SimpleUISwitch/0 QQTheme/1103 InMagicWin/0 StudyMode/0 CurrentMode/0 CurrentFontScale/1.0';
    $url = "https://qun.qq.com/v2/luckyword/proxy/domain/qun.qq.com/cgi-bin/group_lucky_word/word_list?bkn=$bkn";
    $postdata ='{"group_code":'.$group.',"start":1,"limit":30,"need_equip_info":true}';
    $cookie="skey=".$skey.";p_skey=".$pskey.";uin=o".$uin.";p_uin=o".$uin;
    $host = array(
    "qname-service:976321:131072",
    "qname-space: Production",
    "content-type:application/json"
    );
    $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL,$url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, $host);
   curl_setopt($curl, CURLOPT_COOKIE,$cookie);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($curl,CURLOPT_POST,1);
   curl_setopt($curl,CURLOPT_POSTFIELDS,$postdata);
   curl_setopt($curl, CURLOPT_USERAGENT, $ua);
   $a = curl_exec($curl);
   curl_close($curl);
   return $a;
}
function p($p){
    if($p==''||!is_numeric($p)){
        return '1';
    }else{
        return $p;
    }
}
$data =  curl($skey,$pskey,$uin,$group);
function qzf_jiexi($data,$p,$type){
    $json_data = json_decode($data,true);
    $p_2 = p($p);
    $p_2_end = $p_2*10;
    $p_2_strat = $p_2*10-9-1;
    switch ($type) {
        case 'text':
            if($json_data['data']['word_list'][$p_2_strat]['word_info']['wording']==''){
                echo "本群还没有群字符";
                exit;
            }else{
                 $json_data = json_decode($data,true);
                  $p_2 = p($p);
                  $p_2_end = $p_2*10;
                  $p_2_strat = $p_2*10-9-1;
                  echo "以下是你的群字符列表"."\n";
                for ($i = $p_2_strat; $i < $p_2_end; $i++) {
                    $a = $i+1;
                     echo $a.','.$json_data['data']['word_list'][$i]['word_info']['wording'].PHP_EOL;
                }
                echo "当前为第".$p_2."页";
            }
            break;
        case 'json':
            if($json_data['data']['word_list'][$p_2_strat]['word_info']['wording']==''){
                $msg = array('msg'=>'本群没群字符');
                echo json_encode($msg,JSON_UNESCAPED_UNICODE);
                exit;
            }else{
                  $json_data = json_decode($data,true);
                  $p_2 = p($p);
                  $p_2_end = $p_2*10;
                  $p_2_strat = $p_2*10-9-1;
                  for ($i = $p_2_strat; $i < $p_2_end; $i++) {
                       $b[] =  $json_data['data']['word_list'][$i]['word_info']['wording'];
                  }
                  $msg = array(
                      'msg'=>'群字符列表',
                      'data'=>$b,
                      'tips'=>'当前为第'.$p_2.'页'
                      );
                echo json_encode($msg,JSON_UNESCAPED_UNICODE);
            }
            break;
        default:
               if($json_data['data']['word_list'][$p_2_strat]['word_info']['wording']==''){
                echo "本群还没有群字符";
                exit;
            }else{
                 $json_data = json_decode($data,true);
                  $p_2 = p($p);
                  $p_2_end = $p_2*10;
                  $p_2_strat = $p_2*10-9-1;
                  echo "以下是你的群字符列表"."\n";
                for ($i = $p_2_strat; $i < $p_2_end; $i++) {
                    $a = $i+1;
                     echo $a.','.$json_data['data']['word_list'][$i]['word_info']['wording'].PHP_EOL;
                }
                echo "当前为第".$p_2."页";
            }
            break;
    }
}
$data_json = json_decode($data,true);
if($skey==''||$pskey==''||$uin==''||$group==''){
    echo "请补全参数谢谢！";
    exit;
}else if(!is_numeric($uin)||!is_numeric($group)){
    echo "有些参数不是纯数字";
    exit;
}else if($data_json['retcode']!='0'){
    echo "skey和pskey过期了 或者检查其他参数是否正确！";
    exit;
}else{
    echo qzf_jiexi($data,$p,$type);
}
?>