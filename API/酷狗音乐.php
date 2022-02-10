<?php
require "../function.php"; // 引入函数文件
addApiAccess(37); 
 ?>
<?php
$msg = $_GET['msg'];
$n = $_GET['n'];
$type = $_GET['type'];
$p = $_GET['p'];
$p_2 = p($p);
if($msg==''&$type=='text'){
    echo "请输入歌曲名字";
    exit;
}else if($msg==''&$type=='json'){
    $msg = array('code'=>'101','msg'=>'请输入歌曲名字');
    send_json($msg);
    exit;
}
if($msg!=''&!is_numeric($n)){
    echo kugou_sous($msg,$type,$p_2);
    exit;
}else if($msg!=''&is_numeric($n)){
    echo kugou_jiexi($msg,$n,$type);
    exit;
}
function p($p){
    if($p==''||!is_numeric($p)){
        return '1';
    } else {
        return $p;
    }
}
function curl($url){
	$curl = curl_init();
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	$header = array(
	    "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0",
	    );
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	$output = curl_exec($curl);
	curl_close($curl); //输出结果
	return  $output;
}
function kugou_jiexi($msg,$n,$type){
    $url_sous = "http://mobilecdn.kugou.com/api/v3/search/song?format=json&keyword=$msg&page=1&pagesize=40&showtype=1";
    $json = json_decode(curl($url_sous),true);
    $url_jiexi = "https://m.kugou.com/app/i/getSongInfo.php?cmd=playInfo&hash=".$json['data']['info'][$n-1]['320hash'];
    $json_2 = json_decode(curl($url_jiexi),true);
    switch ($type) {
        case 'json':
            if($json_2['url']==''){
            $msg = array('code'=>'104','msg'=>'解析失败！');
            send_json($msg);
            exit;
            } else{
    $gequ_url =  str_replace('/','\/',$json_2['url']);
    $gequ_another = $json_2['author_name'];
    $gequ_name = $json_2['songName'];
    $img = str_replace('/','\/',$json_2['imgUrl']);
    $img_2 = str_replace('\/{size}','',$img);
    $tail = '来自酷狗音乐';
    $url = str_replace('/','\/',"https://www.kugou.com/song/#hash=".$json_2['hash']);
    echo 'json:{"app":"com.tencent.structmsg","config":{"autosize":true,"forward":true,"type":"normal"},"desc":"酷狗音乐","meta":{"music":{"action":"","android_pkg_name":"","app_type":1,"appid":100497308,"desc":"'.$gequ_another.'","jumpUrl":"'.$url.'","musicUrl":"'.$gequ_url.'","preview":"'.$img_2.'","sourceMsgId":0,"source_icon":"","source_url":"","tag":"'.$tail.'","title":"'.$gequ_name.'"}},"prompt":"[分享]'.$gequ_name.'","ver":"0.0.0.1","view":"music"}';
    break;
}
    case 'text':
    if($json_2['url']==''){
            $msg = '解析失败！';
            send($msg);
            exit;
            } else{
 $gequ_url =  $json_2['url'];
    $gequ_another = $json_2['author_name'];
    $gequ_name = $json_2['songName'];
    $img = str_replace('/{size}','',$json_2['imgUrl']);
    echo "mp3链接:".$gequ_url."\n"."歌手:$gequ_another"."\n"."专辑图片:±img=".$img.'±'."\n"."歌曲名字:".$gequ_name.'---'.$gequ_another;
}
    break;
        default:
             if($json_2['url']==''){
            $msg = '解析失败！';
            send($msg);
            exit;
            } else{
 $gequ_url =  $json_2['url'];
    $gequ_another = $json_2['author_name'];
    $gequ_name = $json_2['songName'];
    $img = str_replace('/{size}','',$json_2['imgUrl']);
    echo "mp3链接:".$gequ_url."\n"."歌手:$gequ_another"."\n"."专辑图片:±img=".$img.'±'."\n"."歌曲名字:".$gequ_name.'---'.$gequ_another;
}
            break;
    }
}
function kugou_sous($msg,$type,$p_2){
    $url = "http://mobilecdn.kugou.com/api/v3/search/song?format=json&keyword=$msg&page=1&pagesize=40&showtype=1";
    $p_2_end = $p_2*10;
    $p_2_strat = $p_2*10-9-1;
    $json = json_decode(curl($url),true);
    switch ($type) {
        case 'text':
            if($json['data']['info'][$p_2_strat]['songname']==''){
                $msg = "搜索失败，没有找到";
                send($msg);
                exit;
            }else{
            echo "以下是搜索结果:"."\n";
            $p = $p_2;
            for ($i = $p_2_strat; $i < $p_2_end; $i++) {
                $a = $i+1;
                 echo $a.','.$json['data']['info'][$i]['songname'].'------'.$json['data']['info'][$i]['singername'].PHP_EOL;
                }
            }
            echo "提示:当前为".$p."页 冰月!";
            break;
        case 'json':
            if($json['data']['info'][$p_2_strat]['songname']==''){
                $msg = array('code'=>'104','msg'=>'搜索失败');
                send_json($msg);
                exit;
            } else{
                $p = $p_2;
             for ($i = $p_2_strat; $i < $p_2_end; $i++) {
                $b[] = $json['data']['info'][$i]['songname'].'------'.$json['data']['info'][$i]['singername'];
                 
            }
            $msg = array(
                'msg'=>'搜索成功',
                'data'=>$b,
                'tips:'=>'当前为'.$p.'页'
                );
            send_json($msg);
        }
            break;
        default:
            if($json['data']['info'][$p_2_strat]['songname']==''){
                $msg = "搜索失败，没有找到";
                send($msg);
                exit;
            }else{
            echo "以下是搜索结果:"."\n";
            $p = $p_2;
            for ($i = $p_2_strat; $i < $p_2_end; $i++) {
                $a = $i+1;
                 echo $a.','.$json['data']['info'][$i]['songname'].'------'.$json['data']['info'][$i]['singername'].PHP_EOL;
                }
            }
            echo "提示:当前为".$p."页 冰月!";
            exit;
    }
}
function send($msg){
        echo $msg;
}
function send_json($msg){
    echo json_encode($msg,JSON_UNESCAPED_UNICODE);
}
?>