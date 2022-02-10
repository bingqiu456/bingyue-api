<?php
##昨天那个 有一些小bug
##此版本为修复版
##作者：滨河 仅供学习交流 严禁贩卖
$skey = $_GET['skey']; $pskey = $_GET['pskey']; $uin = $_GET['uin']; $qq = $_GET['qq']; $money = $_GET['qian']; $name = $_GET['name']; $group = $_GET['group']; $k = $_GET['k']; $type = $_GET['type']; $id = $_GET['id'];
## k是1代表发起 2代表查询 
 function k($k){
        if($k!='1'&$k!='2'){
            return "1";
        }else{
            return $k;
                }
        }
function cuowu($type){
    if($type==''||$type=='text'){
        return '请检查参数补齐了没';
        exit;
    }else{
        return json_encode(array('code'=>'104','msg'=>'请检查参数补齐了没'),JSON_UNESCAPED_UNICODE);
    }
}
if($skey==''||$uin==''||$pskey==''){
    echo cuowu($type);
    exit;
}else if(k($k)=='1'){
    $c = new qsk;
    $c -> __faqi($skey,$pskey,$uin,$group,$qq,$money,$name,$type);
    exit;
}else{
    $c = new qsk;
    $c -> __chaxun($skey,$pskey,$uin,$id,$type);
    exit;
}
/**
 * 群账单
 * 分两个类 一个是查询收款
 * 一个是发起收款
 */
class qsk
{ 
    
    public function __faqi($skey,$pskey,$uin,$group,$qq,$money,$name,$type)
    {
    $name_2 = name($name);
    $qian = $money*100;
    $url = 'https://mqq.tenpay.com/cgi-bin/qcollect/qpay_collect_create.cgi?type=2&memo='.$name_2.'&amount='.$qian.'&payer_list=[{"uin":"'.$qq.'","amount":"'.$qian.'"}]&num=1&recv_type=1&group_id='.$group.'&uin='.$uin.'&pskey='.$pskey.'&skey='.$skey.'&skey_type=2';
       $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL,$url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array('content-type:text/html; charset=UTF-8'));
   curl_setopt($curl, CURLOPT_COOKIE,"skey=".$skey.";p_skey=".$pskey.";uin=o".$uin.";p_uin=o".$uin);
   curl_setopt($curl, CURLOPT_REFERER, "https://mqq.tenpay.com/mqq/groupreceipts/index.shtml?uin='.$group.'&type=4&_wv=1027&_wvx=4&from=appstore_aio");
   curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 7.0; TRT-AL00A Build/HUAWEITRT-AL00A; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/64.0.3282.137 Mobile Safari/537.36 V1_AND_SQ_8.8.33_2150_YYB_D A_8083300 QQ/8.8.33.6300 NetType/WIFI WebP/0.4.1 Pixel/720 StatusBarHeight/44 SimpleUISwitch/0 QQTheme/1103 InMagicWin/0 StudyMode/0 CurrentMode/0 CurrentFontScale/1.0');
   $a = curl_exec($curl);
   curl_close($curl);
   $json =  json_decode($a,true);
   switch ($type) {
       case 'text':
           if($json['retcode']!='0'){
               echo "请检查参数是否正确";
               exit;
           }else{
               echo "ok订单创建成功 你的id为".$json['collection_no'];
           }
           break;
       case 'json':
            if($json['retcode']!='0'){
                echo json_encode(array('code'=>'104','msg'=>'请检查参数是否正确'),JSON_UNESCAPED_UNICODE);
                exit;
            }else{
                echo json_encode(array('code'=>'200','msg'=>'ok','id'=>$json['collection_no'],'tips'=>'id是作为查询的依据'),JSON_UNESCAPED_UNICODE);
            }
            break;
       default:
            if($json['retcode']!='0'){
                echo json_encode(array('code'=>'104','msg'=>'请检查参数是否正确'),JSON_UNESCAPED_UNICODE);
                exit;
            }else{
                echo json_encode(array('code'=>'200','msg'=>'ok','id'=>$json['collection_no'],'tips'=>'id是作为查询的依据'),JSON_UNESCAPED_UNICODE);
            }
           break;
   }
    }
    public function __chaxun($skey,$pskey,$uin,$id,$type){
    $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL,'https://mqq.tenpay.com/cgi-bin/qcollect/qpay_collect_detail.cgi?collection_no='.$id.'&uin='.$uin.'&pskey='.$pskey.'&skey='.$skey.'&skey_type=2');
   curl_setopt($curl, CURLOPT_HTTPHEADER, array('content-type:text/html; charset=UTF-8'));
   curl_setopt($curl, CURLOPT_COOKIE,"skey=".$skey.";p_skey=".$pskey.";uin=o".$uin.";p_uin=o".$uin);
   curl_setopt($curl, CURLOPT_REFERER, 'https://mqq.tenpay.com/mqq/groupreceipts/detail.shtml?_wv=1027&_wvx=4&collectionno='.$id);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 7.0; TRT-AL00A Build/HUAWEITRT-AL00A; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/64.0.3282.137 Mobile Safari/537.36 V1_AND_SQ_8.8.33_2150_YYB_D A_8083300 QQ/8.8.33.6300 NetType/WIFI WebP/0.4.1 Pixel/720 StatusBarHeight/44 SimpleUISwitch/0 QQTheme/1103 InMagicWin/0 StudyMode/0 CurrentMode/0 CurrentFontScale/1.0');
   $a = curl_exec($curl);
   curl_close($curl);
   $json = json_decode($a,true);
   switch ($type) {
       case 'json':
           if($json['retcode']!='0'){
               echo json_encode(array('code'=>'104','msg'=>'请检查参数是否正确'),JSON_UNESCAPED_UNICODE);
               exit;
           }else if ($json['recv_amount']=='0'){
               echo json_encode(array('code'=>'-1','msg'=>'对方还没支付呢'),JSON_UNESCAPED_UNICODE);
               exit;
           }else{
               echo json_encode(array('code'=>'200','msg'=>'对方支付了'),JSON_UNESCAPED_UNICODE);
           }
           break;
       case 'text':
           if($json['retcode']!='0'){
               echo '请检查参数是否正确';
               exit;
           }else if($json['recv_amount']=='0'){
               echo "对方还没支付呢";
               exit;
                }else{
                    echo "对方支付了";
                }
                break;
       default:
            if($json['retcode']!='0'){
               echo json_encode(array('code'=>'104','msg'=>'请检查参数是否正确'),JSON_UNESCAPED_UNICODE);
               exit;
           }else if ($json['recv_amount']=='0'){
               echo json_encode(array('code'=>'-1','msg'=>'对方还没支付呢'),JSON_UNESCAPED_UNICODE);
               exit;
           }else{
               echo json_encode(array('code'=>'200','msg'=>'对方支付了'),JSON_UNESCAPED_UNICODE);
           }
           break;
   }
   
    }
}

    
     function name($name)
    {
        if($name==''){
            return '活动账单';
        }else{
            return $name;
            
        }
    }
    
    
?>