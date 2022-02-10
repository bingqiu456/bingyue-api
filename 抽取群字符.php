<?php
## 本api仅供学习交流 请勿倒卖
## 作者：bingyue(滨河！)
## 留个作者谢谢！
$skey = $_REQUEST['skey'];
$pskey = $_REQUEST['pskey'];
$uin = $_REQUEST['uin'];
$group = $_REQUEST['group'];
$type = $_REQUEST['type'];
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
function chouka_hexin($skey,$pskey,$uin,$group){
    $bkn = bkn($skey);
    $ua = 'Mozilla/5.0 (Linux; Android 7.0; TRT-AL00A Build/HUAWEITRT-AL00A; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/64.0.3282.137 Mobile Safari/537.36 V1_AND_SQ_8.8.33_2150_YYB_D A_8083300 QQ/8.8.33.6300 NetType/WIFI WebP/0.4.1 Pixel/720 StatusBarHeight/44 SimpleUISwitch/0 QQTheme/1103 InMagicWin/0 StudyMode/0 CurrentMode/0 CurrentFontScale/1.0';
    $host = array(
    "qname-service:976321:131072",
    "qname-space: Production",
    "content-type:application/json"
        );
    $cookie="skey=".$skey.";p_skey=".$pskey.";uin=o".$uin.";p_uin=o".$uin;
    $url = "https://qun.qq.com/v2/luckyword/proxy/domain/qun.qq.com/cgi-bin/group_lucky_word/draw_lottery?bkn=$bkn";
   $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL,$url);
   curl_setopt($curl, CURLOPT_HTTPHEADER,$host);
   curl_setopt($curl, CURLOPT_COOKIE,$cookie);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($curl,CURLOPT_POST,1);
   curl_setopt($curl,CURLOPT_POSTFIELDS,'{"group_code":'.$group.'}');
   curl_setopt($curl, CURLOPT_USERAGENT, $ua);
   $a = curl_exec($curl);
   curl_close($curl);
   return $a;
}

function send($msg){
    echo  $msg;
}
function send_json($msg){
    echo json_encode($msg,JSON_UNESCAPED_UNICODE);
}
function panduan($type){
    if($type=='text'||$type==''){
        return '1';
    }else {
        return '2';
    }
}
$data_json = json_decode(chouka_hexin($skey,$pskey,$uin,$group),true);
if($data_json['retcode']=='11004'&panduan($type)=='1'){
    send($msg="你的抽字符次数已经用完，明天再来吧");
    exit;
}else if($data_json['retcode']=='11004'&panduan($type)=='2'){
    send_json($msg=array('msg'=>'你的字符抽取次数已经用完，明天再来吧'));
    exit;
}else if($data_json['retcode']!='0'&panduan($type)=='1'){
    send($msg='请检查信息是否失效 或者skey和pskey是否过期');
    exit;
}else if($data_json['retcode']!='0'&panduan($type)=='2'){
    send_json($msg=array('msg'=>'请检查信息是否失效 或者skey和pskey是否过期'));
    exit;
}else{
    echo qzf_jiexi($data_json,$type);
}
function qzf_jiexi($data_json,$type){
    switch ($type) {
        case 'text':
            if($data_json['data']['word_info']['word_info']['wording']==''){
                send($msg="很遗憾你没抽到字符,次数-1");
                exit;
            }else{
                send($msg="恭喜你抽到了字符".$data_json['data']['word_info']['word_info']['wording']);
            }
            break;
        case 'json':
            if($data_json['data']['word_info']['word_info']['wording']==''){
                send_json($msg=array('msg'=>'很遗憾你没抽到字符,次数-1'));
                exit;
            }else{
                send_json($msg=array('msg'=>'成功抽到','zf'=>$data_json['data']['word_info']['word_info']['wording']));
            }
            break;
        default:
             if($data_json['data']['word_info']['word_info']['wording']==''){
                send($msg="很遗憾你没抽到字符,次数-1");
                exit;
            }else{
                send($msg="恭喜你抽到了字符".$data_json['data']['word_info']['word_info']['wording']);
            }
            break;
    }
    
}
 
?>