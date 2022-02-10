<?php
	/**
  * 冰月API http://apii.bingyue.xyz
  * 作者:滨河！
  * 这可能是我在考场外做数学题了
  */
$p = $_GET['p'];
$type = $_GET['type'];
$p_2 = p($p);
$p_2_end = $p_2*10;
$p_2_strat = $p_2*10-9-1;
$a = json_encode(array('b','v','c','d','google','baidu','bt','a','b','c','w','k','q','u','p','q','bingyue','binhe','s','b'),JSON_UNESCAPED_UNICODE);
$b = json_decode($a,true);
switch ($type) {
    case 'text':
        if($b[$p_2_strat]==''){
            echo "找不到数据";
            exit;
        }else{
            echo "冰月翻页示例"."\n";
        for ($i = $p_2_strat; $i < $p_2_end; $i++) {
             $a = $i+1;
             echo $a.','.$b[$i].PHP_EOL;
        }
        echo "当前为".$p_2.'页';
    }
        break;
    case 'json':
        if($b[$p_2_strat]==''){
            echo json_encode(array('code'=>'104','msg'=>'找不到数据'),JSON_UNESCAPED_UNICODE);
            exit;
        }else{
            for ($i = $p_2_strat; $i < $p_2_end; $i++) {
                 $h[] = $b[$i];
            }
             echo json_encode(array(
                'code'=>'200',
                'data'=>$h,
                'tips'=>'当前是'.$p_2.'页',
                ),JSON_UNESCAPED_UNICODE);
    }
    break;
    default:
         if($b[$p_2_strat]==''){
            echo "找不到数据";
            exit;
        }else{
            echo "冰月翻页示例"."\n";
        for ($i = $p_2_strat; $i < $p_2_end; $i++) {
             $a = $i+1;
             echo $a.','.$b[$i].PHP_EOL;
        }
        echo "当前为".$p_2.'页';
    }
        break;
}
function p($p){
    if($p==''||!is_numeric($p)){
        return '1';
    }else{
        return $p;
    }
}

?>