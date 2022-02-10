<?php
$type = $_GET['type'];
$p = $_GET['p'];
$type = type($type);
$p = p($p);
function type($type){
    if($type==''||$type=='text'){
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
$url = 'https://api.bilibili.com/x/esports/sports/season/getMedalTable?season_id=1&sort_type=1';
$b = json_decode(curl($url),true);
$p_end = $p*10;
$p_strat = $p*10-9-1;
switch ($type) {
    case 'text':
        if($b['data']['table'][$p_strat]['participant_name']==''){
            echo "找不到数据";
            exit;
        }else{
        echo "———冬奥奖牌列表———"."\n";
        for ($i = $p_strat; $i < $p_end; $i++) {
            $a = $i+1;
             echo $a.','.$b['data']['table'][$i]['participant_name'].' ---- '.'金牌:'.$b['data']['table'][$i]['gold'].' 银牌:'.$b['data']['table'][$i]['silver'].' 铜牌:'.$b['data']['table'][$i]['bronze'].' 总牌数:'.$b['data']['table'][$i]['total'].PHP_EOL;
        }
        echo "———————————————"."\n"."当前为".$p."页";
}
        break;
    case 'json':
        if($b['data']['table'][$p_strat]['participant_name']==''){
            echo json_encode(array('code'=>'104','msg'=>'找不到数据'),JSON_UNESCAPED_UNICODE);
            exit;
        }else{
            for ($i = $p_strat; $i < $p_end; $i++) {
                 $c[] = $b['data']['table'][$i]['participant_name'];
                 $j[] = $b['data']['table'][$i]['gold'];
                 $k[] = $b['data']['table'][$i]['silver'];
                 $z[] = $b['data']['table'][$i]['bronze'];
                 $v[] = $b['data']['table'][$i]['total'];
            }
            $cb = array(
                'code'=>'200',
                'guojia' =>$c,
                    'jin'=>$j,
                    'yin'=>$k,
                    'tong'=>$z,
                    'zong'=>$v,
                'tips'=>'当前是'.$p.'页'
                );
                echo json_encode($cb,JSON_UNESCAPED_UNICODE);
        }
        break;
    default:
         if($b['data']['table'][$p_strat]['participant_name']==''){
            echo "找不到数据";
            exit;
        }else{
        echo "———冬奥奖牌列表———"."\n";
        for ($i = $p_strat; $i < $p_end; $i++) {
            $a = $i+1;
             echo $a.','.$b['data']['table'][$i]['participant_name'].' ---- '.'金牌:'.$b['data']['table'][$i]['gold'].' 银牌:'.$b['data']['table'][$i]['silver'].' 铜牌:'.$b['data']['table'][$i]['bronze'].' 总牌数:'.$b['data']['table'][$i]['total'].PHP_EOL;
        }
        echo "———————————————"."\n"."当前为".$p."页";
}
        break;
}
?>