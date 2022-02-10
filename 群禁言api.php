<?php
## 作者: 冰月/滨河    http://apii.bingyue.xyz
## 制作不容易
## 请勿倒卖 本api仅学习交流
$skey = $_REQUEST['skey'];
$pskey = $_REQUEST['pskey'];
$uin = $_REQUEST['uin'];
$group = $_REQUEST['group'];
$qq = $_REQUEST['qq'];
$time = $_REQUEST['time'];
## 这里禁言时间按秒算的
$type = $_REQUEST['type'];
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
function curl($skey,$pskey,$uin,$group,$qq,$time){
    $ua = 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) QQ/9.4.3.27712 Chrome/43.0.2357.134 Safari/537.36 QBCore/3.43.1298.400 QQBrowser/9.0.2524.400';
    $host = array(
        "content-type:application/json"
        );
    $bkn = getGTK($skey);
    $url = 'https://qinfo.clt.qq.com/cgi-bin/qun_info/set_group_shutup';
     $cookie="skey=".$skey.";p_skey=".$pskey.";uin=o".$uin.";p_uin=o".$uin;
      $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL,$url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $host);
    curl_setopt($curl, CURLOPT_COOKIE,$cookie);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl,CURLOPT_POST,1);
    curl_setopt($curl,CURLOPT_POSTFIELDS,'shutup_list=[{"uin":'.$qq.',"t":'.$time.'}]&gc='.$group.'&bkn='.$bkn.'&src=qinfo_v3');
    curl_setopt($curl, CURLOPT_USERAGENT, $ua);
    $a = curl_exec($curl);
    curl_close($curl);
    return $a;
}
$data =  curl($skey,$pskey,$uin,$group,$qq,$time);
function jiexi($data,$type,$qq,$group,$time){
    $data_json = json_decode($data,true);
    switch ($type) {
        case 'text':
            if($data_json['ec']!='0'){
                echo "请检查你的skey和pskey是否过期";
                exit;
            }else{
                echo "禁言成功","\n","qq:".$qq,"\n","群号:$group","\n",'时间秒数:'.$time;
            }
            break;
        case 'json':
            if($data_json['ec']!='0'){
                echo json_encode(array('msg'=>'请检查你的参数是否过期'),JSON_UNESCAPED_UNICODE);
                exit;
            }else{
                echo json_encode(
                    array(
                        'msg'=>'禁言成功',
                        'qq'=>$qq,
                        'group'=>$group,
                        'time'=>$time,
                        ),JSON_UNESCAPED_UNICODE
                    );
            }
            break;
        default:
            if($data_json['ec']!='0'){
                echo "请检查你的skey和pskey是否过期";
                exit;
            }else{
                  echo "禁言成功","\n","qq:".$qq,"\n","群号:$group","\n",'时间秒数:'.$time;;
            }
            break;
    }
}
function cuowu($type){
    if($type==''||$type=='text'){
        return "参数缺失";
        exit;
    }else{
        return json_encode(array('msg'=>'参数缺失'),JSON_UNESCAPED_UNICODE);
    }
}
function cuowu_2($type){
    if($type==''||$type=='text'){
        return '输入的qq号不是纯数字';
        exit;
    }else{
        return json_encode(array('msg'=>'输入的qq号不是纯数字'),JSON_UNESCAPED_UNICODE);
    }
}
if($time==''||$group==''||$skey==''||$pskey==''||$qq==''||$uin==''){
    echo cuowu($type);
    exit;
}else if(!is_numeric($uin)){
    echo cuowu_2($type);
    exit;
}else{
    echo jiexi($data,$type,$qq,$group,$time);
    exit;
}
?>