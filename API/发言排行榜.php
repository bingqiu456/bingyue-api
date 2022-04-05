<?php
##作者:滨河
##冰月api by1106.bingyue.xyz
## 严禁贩卖本api 仅供学习交流
## 严禁贩卖本api 仅供学习交流
## 严禁贩卖本api 仅供学习交流
$skey = $_GET['skey'];
$pskey = $_GET['pskey'];
$uin = $_GET['uin'];
$group = $_GET['group'];
$type = $_GET['type'];
$time = $_GET['time'];
$p = $_GET['p'];
## time 1是七天内 0是昨日
class fayan
{   
    public function __time($time){
        if($time!='1'&$time!='0'){
            $this->tim=1;
            $this->time_name='七天内';
        }else{
            $this->tim=$time;
            $a = str_replace('1','七天内',$time);
            $this->time_name=str_replace('0','昨日',$a);
        }
    }
    
    public function __cuowu($type){
        if($type==''||$type=='text'){
            echo "信息请补充完整";
            exit;
        }else{
            echo json_encode(array('code'=>'104','msg'=>'信息请补充完整'),JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    
    public function __p($p){
        if($p==''||!is_numeric($p)){
            $this->p=1;
        }else{
            $this->p=$p;
        }
    }

    public function __canshu($skey,$pskey,$uin,$group,$type){
        $this->skey=$skey;
        $this->pskey=$pskey;
        $this->uin=$uin;
        $this->group=$group;
        $this->type=$type;
    }

    public function __curl(){
        $curl = curl_init();
        $ch = array(
            CURLOPT_URL=>'https://qun.qq.com/m/qun/activedata/speaking.html?gc='.$this->group.'&time='.$this->tim.'&_wv=3&&_wwv=128',
            CURLOPT_HTTPHEADER=>array(
                'content-type: text/html; charset=UTF-8',
                ),
            CURLOPT_COOKIE=>"skey=".$this->skey.";p_skey=".$this->pskey.";uin=o".$this->uin.";p_uin=o".$this->uin,
            CURLOPT_RETURNTRANSFER=>1,
            CURLOPT_USERAGENT=>'Mozilla/5.0 (Linux; Android 11; M10_by Build/RQ3A.211001.001; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/89.0.4389.72 MQQBrowser/6.2 TBS/045947 Mobile Safari/537.36 V1_AND_SQ_8.8.50_2324_YYB_D A_8085000 QQ/8.8.50.6735 NetType/WIFI WebP/0.3.0 Pixel/1080 StatusBarHeight/58 SimpleUISwitch/0 QQTheme/999 InMagicWin/0 StudyMode/0 CurrentMode/0 CurrentFontScale/1.0',

        );
    curl_setopt_array($curl, $ch);
    $result = curl_exec($curl);
    $this->re=$result;
    }

    public function __jiexi(){
        preg_match_all("/window.__INITIAL_STATE__=(.*?)<\/script>/",$this->re,$data);
        $a =  json_encode($data[1],JSON_UNESCAPED_UNICODE);
        $b = json_decode($a,true);
        $c = json_decode($b[0],true);
        switch ($this->type) {
            case 'text':
                $abc = count($c["speakingList"],0);
                $p_end = shu($abc,$this->p);
                $p_strat = $this->p*10-9-1;
                $cc = floor($abc/10) +1;
                if($c["speakingList"][$p_strat]["nickname"]==''){
                    echo "找不到数据";
                    exit;
                }else{
                echo "查询时间:".$this->time_name,"\n","群号:".$this->group,"\n","————————————"."\n";
                for ($i=$p_strat; $i <$p_end ; $i++) { 
                    $a = $i+1;
                    echo $a.",".$c["speakingList"][$i]["nickname"]."\n"."活跃天数:".$c["speakingList"][$i]["active"]."\n"."发言条数:".$c["speakingList"][$i]["msgCount"]."\n"."————————————"."\n";
                }
                echo "————冰月api\n你目前在".$this->p."页\n"."一共有".$cc."页";
                }
                break;
            case 'json':
                $abc = count($c["speakingList"],0);
                $p_end = shu($abc,$this->p);
                $p_strat = $this->p*10-9-1;
                $cc = floor($abc/10) +1;
                if($c["speakingList"][$p_strat]["nickname"]==''){
                    echo json_encode(array('code'=>'104','msg'=>'找不到'),JSON_UNESCAPED_UNICODE);
                    exit;
                }else{
                for ($i = $p_strat; $i < $p_end; $i++) {
                     $aaa[] = $c["speakingList"][$i]["nickname"];
                     $bbb[] = $c["speakingList"][$i]["active"];
                     $ccc[] = $c["speakingList"][$i]['msgCount'];
                }
                echo json_encode(array(
                    'code'=>'200',
                    'name'=>$aaa,
                    'huoyue'=>$bbb,
                    'msg'=>$ccc,
                    '?'=>'冰月api',
                    'zong'=>'这里一共有'.$cc."页",
                    'p'=>'你目前在'.$this->p.'页',
                    ),JSON_UNESCAPED_UNICODE);
                }
                    break;
            default:
                $abc = count($c["speakingList"],0);
                $p_end = shu($abc,$this->p);
                $p_strat = $this->p*10-9-1;
                $cc = floor($abc/10) +1;
                if($c["speakingList"][$p_strat]["nickname"]==''){
                    echo "找不到数据";
                    exit;
                }else{
                echo "查询时间:".$this->time_name,"\n","群号:".$this->group,"\n","————————————"."\n";
                for ($i=$p_strat; $i <$p_end ; $i++) { 
                    $a = $i+1;
                    echo $a.",".$c["speakingList"][$i]["nickname"]."\n"."活跃天数:".$c["speakingList"][$i]["active"]."\n"."发言条数:".$c["speakingList"][$i]["msgCount"]."\n"."————————————"."\n";
                }
                echo "————冰月api\n你目前在".$this->p."页\n"."一共有".$cc."页";
                }
                break;
        }
        
        
    }
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
$a = new fayan;
if($skey==''||$pskey==''||$uin==''||$group==''){
    $a->__cuowu($type);
    exit;
}else{
$a->__canshu($skey,$pskey,$uin,$group,$type);
$a->__time($time);
$a->__p($p);
$a->__curl();
$a->__jiexi();
}
?>