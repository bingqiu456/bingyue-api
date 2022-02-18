<?php
## 作者:滨河！ http://apii.bingyue.xyz
## 严禁贩卖
$msg = $_GET['msg'];
$type = $_GET['type'];
$day = $_GET['day'];
$api_key = '' ##这里填写你的私钥key;
$day = day($day);
function day($day){
    if($day==''||!is_numeric($day)){
        return '1';
        exit;
    }else{
        return $day;
    }
}
function curl($day,$msg,$api_key){
    $url = 'https://api.seniverse.com/v3/weather/daily.json?key='.$api_key.'&location='.$msg.'&start=0&days='.$day;
    $heard  = array(
        'content-type: application/json; charset=utf-8'
    );
    $ua = 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.0 Mobile/14E304 Safari/602.1';
    $curl = curl_init();
    $ch = array(
        CURLOPT_URL=>$url,
        CURLOPT_HTTPHEADER=>$heard,
        CURLOPT_RETURNTRANSFER=>1,
        CURLOPT_REFERER=>'https://seniverse.yuque.com/',
        CURLOPT_USERAGENT=>$ua,
        );
    curl_setopt_array($curl, $ch);
    $result = curl_exec($curl);
    return $result;
}
$data = curl($day,$msg,$api_key);
$data_json = json_decode($data,true); 
function jiexi_shuju($data_json,$type){
    switch ($type) {
        case 'json':
            if($data_json['status_code']!=''){
                echo json_encode(array('code'=>'104','msg'=>'获取失败，请稍后再试'),JSON_UNESCAPED_UNICODE);
                exit;
            }else{
            $b = count($data_json['results'][0]['daily'],0);
            for ($i=0; $i < $b; $i++) { 
                $a[] = $data_json['results'][0]['daily'][$i]['text_day'];
                $e[] = $data_json['results'][0]['daily'][$i]['high'];
                $c[] = $data_json['results'][0]['daily'][$i]['low'];
                $d[] = $data_json['results'][0]['daily'][$i]['wind_direction'];
                $time[] = $data_json['results'][0]['daily'][$i]['date'];
                $img[] = 'https://apii.bingyue.xyz/api/tqcx/'.$data_json['results'][0]['daily'][$i]['text_day'].'.png';
            }
            echo json_encode(array(
                'code'=>'200',
                'weather'=>$a,
                'gao'=>$b,
                'di'=>$c,
                'fengxiang'=>$d,
                'img'=>$img,
                'time'=>$time,
            ),JSON_UNESCAPED_UNICODE);
        }
            break;
        case 'text':
            if($data_json['status_code']!=''){
                echo '请稍后再试';
                exit;
            }else{
                $b = count($data_json['results'][0]['daily'],0);
                echo '—————'.$data_json['results'][0]['location']['name'].'天气'.'—————'."\n";
                for ($i=0; $i < $b; $i++) { 
                    echo '情况:'.$data_json['results'][0]['daily'][$i]['text_day'].PHP_EOL;
                    echo '状态:'.'±img=https://apii.bingyue.xyz/api/tqcx/'.$data_json['results'][0]['daily'][$i]['text_day'].'.png±'.PHP_EOL;
                    echo '风向:'.$data_json['results'][0]['daily'][$i]['wind_direction'].' 高温:'.$data_json['results'][0]['daily'][$i]['high'].'°C'.' 低温:'.$data_json['results'][0]['daily'][$i]['low'].'°C'.PHP_EOL;
                    echo '时间:'.$data_json['results'][0]['daily'][$i]['date'].PHP_EOL;
                    echo '——————————————'.PHP_EOL;

                }
                echo '获取成功';
            }
            break;
        default:
              if($data_json['status_code']!=''){
                echo '请稍后再试';
                exit;
            }else{
                $b = count($data_json['results'][0]['daily'],0);
                echo '—————'.$data_json['results'][0]['location']['name'].'天气'.'—————'."\n";
                for ($i=0; $i < $b; $i++) { 
                    echo '情况:'.$data_json['results'][0]['daily'][$i]['text_day'].PHP_EOL;
                    echo '状态:'.'±img=https://apii.bingyue.xyz/api/tqcx/'.$data_json['results'][0]['daily'][$i]['text_day'].'.png±'.PHP_EOL;
                    echo '风向:'.$data_json['results'][0]['daily'][$i]['wind_direction'].' 高温:'.$data_json['results'][0]['daily'][$i]['high'].'°C'.' 低温:'.$data_json['results'][0]['daily'][$i]['low'].'°C'.PHP_EOL;
                    echo '时间:'.$data_json['results'][0]['daily'][$i]['date'].PHP_EOL;
                    echo '——————————————'.PHP_EOL;

                }
                echo '获取成功';
            }
            break;
    }
}
function cuowu($type){
    if($type==''||$type=='text'){
        return '请输入地区名';
        exit;
    }else{
        return json_encode(array('code'=>'104','msg'=>'请输入地区名'),JSON_UNESCAPED_UNICODE);
        exit;
    }
}
if($msg==''){
    echo cuowu($type);
    exit;
}else{
    echo jiexi_shuju($data_json,$type);
    exit;
}
?>