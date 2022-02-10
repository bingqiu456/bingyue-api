<?php
require "../function.php"; // 引入函数文件
addApiAccess(6); 
 ?>
<?php
## 由于某些原因 解析接口是第三方的
$msg = $_GET['msg'];
$type = $_GET['type'];
$n = $_GET['n'];
$p = $_REQUEST['p'];
$p_2 = p($p);
function p($p){
    if($p==''||!is_numeric($p)){
        return '1';
    }else {
        return $p;
    }
}
function curl($url){
	$curl = curl_init();
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_URL,$url);
	$header = array("user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0");
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, FALSE);
	$output = curl_exec($curl);
	curl_close($curl); //输出结果
	return  $output;
}
function songid($msg,$n){
    $url = "https://c.y.qq.com/soso/fcgi-bin/client_search_cp?aggr=1&cr=1&flag_qc=0&p=1&n=40&w=$msg";
    $url = curl($url);
    $x = str_replace('callback', '', $url);
    $x = str_replace('({"code":0', '{"code":0', $x);
    $x = str_replace('"tips":""})', '"tips":""}', $x);
    $json = json_decode($x,true);
    return $json["data"]["song"]["list"][$n-1]["songmid"];
}
function jiexi($msg,$n,$type){
    switch ($type) {
        case 'json':
    $songid = songid($msg,$n);
    $url = "https://api.vvhan.com/api/music?type=song&media=tencent&id=".$songid;
    $url = curl($url);
    $json = json_decode($url,true);
    if($json['mp3url']==''){
        send_json($msg=array('code'=>'104','msg'=>'解析失败'));
        exit;
    }else{
    $mp3url = str_replace('/','\/',$json['mp3url']);
        $cover = str_replace('/','\/',$json['cover']);
    $msg = 'json:{"app":"com.tencent.structmsg","desc":"音乐","view":"music","ver":"0.0.0.1","prompt":"'.$json['name'].'","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":"1","appid":"100497308","desc":"'.$json['author'].'","jumpUrl":"'.$mp3url.'","musicUrl":"'.$mp3url.'","preview":"'.$cover.'","sourceMsgId":"0","source_icon":"","source_url":"","tag":"QQ音乐","title":"'.$json['name'].'"}},"config":{"autosize":true,"ctime":"1628400192","forward":true,"token":"64550d43171367ac31c70e3bba1ebcd7","type":"normal"},"text":"","sourceAd":"","extra":"{\"app_type\":1,\"appid\":100497308,\"uin\":424993442}"}';
    send($msg);
}
            break;
        case 'text':
    $songid = songid($msg,$n);
    $url = "https://api.vvhan.com/api/music?type=song&media=tencent&id=".$songid;
    $url = curl($url);
    $json = json_decode($url,true);
    if($json['mp3url']==''){
        send($msg='解析失败');
        exit;
    }else{
    $msg = "封面：±img=".$json['cover'].'±'."\n"."歌曲：".$json['name']."\n"."作者：".$json['author']."\n"."歌曲链接：".$json['mp3url'];
    send($msg); 
    }
    break;
        default:
    $songid = songid($msg,$n);
    $url = "https://api.vvhan.com/api/music?type=song&media=tencent&id=".$songid;
    $url = curl($url);
    $json = json_decode($url,true);
    if($json['mp3url']==''){
        send_json($msg=array('code'=>'104','msg'=>'解析失败'));
        exit;
    }else{
        $mp3url = str_replace('/','\/',$json['mp3url']);
        $cover = str_replace('/','\/',$json['cover']);
    $msg = 'json:{"app":"com.tencent.structmsg","desc":"音乐","view":"music","ver":"0.0.0.1","prompt":"'.$json['name'].'","appID":"","sourceName":"","actionData":"","actionData_A":"","sourceUrl":"","meta":{"music":{"action":"","android_pkg_name":"","app_type":"1","appid":"100497308","desc":"'.$json['author'].'","jumpUrl":"'.$mp3url.'","musicUrl":"'.$mp3url.'","preview":"'.$cover.'","sourceMsgId":"0","source_icon":"","source_url":"","tag":"QQ音乐","title":"'.$json['name'].'"}},"config":{"autosize":true,"ctime":"1628400192","forward":true,"token":"64550d43171367ac31c70e3bba1ebcd7","type":"normal"},"text":"","sourceAd":"","extra":"{\"app_type\":1,\"appid\":100497308,\"uin\":424993442}"}';
    send($msg);
}
            break;
    }
   
}
function qqyy_sous($msg,$type,$p_2){
    $url = "https://c.y.qq.com/soso/fcgi-bin/client_search_cp?aggr=1&cr=1&flag_qc=0&p=1&n=40&w=$msg";
    $url = curl($url);
    $x = str_replace('callback', '', $url);
    $x = str_replace('({"code":0', '{"code":0', $x);
    $x = str_replace('"tips":""})', '"tips":""}', $x);
    $p_2_end = $p_2*10;
    $p_2_strat = $p_2*10-9-1;
    $json = json_decode($x,true);
    switch ($type) {
        case 'text':
        if($json['data']['song']['list'][$p_2_strat]['songname']==''){
            send($msg='搜索失败！');
            exit;
        } else{
        $p = $p_2;
        send($msg = "以下是搜索结果:"."\n");
        for ($i = $p_2_strat; $i < $p_2_end; $i++) {
             $a = $i+1;
             send($msg=$a.','.$json['data']['song']['list'][$i]['songname'].' ----- '.$json['data']['song']['list'][$i]['singer'][0]['name'].PHP_EOL);
        }
        send($msg = "提示:当前在第".$p."页");
}
            break;
        case 'json':
        if($json['data']['song']['list'][$p_2_strat]['songname']==''){
            $msg = array('code'=>'104','msg'=>'搜索失败');
            send_json($msg);
            exit;
        }else{
        $p = $p_2;
        for ($i = $p_2_strat; $i < $p_2_end; $i++) {
             $b[] = $json['data']['song']['list'][$i]['songname'].' ----- '.$json['data']['song']['list'][$i]['singer'][0]['name'];
        }
        $msg = array(
            'code'=>'200',
            'msg'=>'搜索成功',
            'data'=>$b,
            '?'=>'bingyue'
            );
        send_json($msg);
        break;
}
        default:
        if($json['data']['song']['list'][$p_2_strat]['songname']==''){
            send($msg='搜索失败！');
            exit;
        } else{
        $p = $p_2;
        send($msg = "以下是搜索结果:"."\n");
        for ($i = $p_2_strat; $i < $p_2_end; $i++) {
             $a = $i+1;
             send($msg=$a.','.$json['data']['song']['list'][$i]['songname'].' ----- '.$json['data']['song']['list'][$i]['singer'][0]['name'].PHP_EOL);
        }
        send($msg = "提示:当前在第".$p."页");
}
            break;
    }
}
function send($msg){
    echo $msg;
}
function send_json($msg){
    echo json_encode($msg,JSON_UNESCAPED_UNICODE);
}
function panduan($type){
    if($type==''||$type=='text'){
        return '1';
    }else{
        return '2';
    }
}
if($msg==''&panduan($type)=='1'){
    echo "请输入歌曲名字";
    exit;
}else if($msg==''&panduan($type)=='2'){
    echo json_encode(array('code'=>'104','msg'=>'请输入歌曲名字'),JSON_UNESCAPED_UNICODE);
    exit;
}else if(!is_numeric($n)){
    echo qqyy_sous($msg,$type,$p_2);
    exit;
}else{
    echo jiexi($msg,$n,$type);
    exit;
}
?>