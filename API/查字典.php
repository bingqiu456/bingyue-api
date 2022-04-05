<?php
## 作者:滨河
## 冰月api by1106.bingyue.xyz
$msg = $_GET['msg'];
$type = $_GET['type'];
class zici{
    public function __canshu($msg,$type){
        $this->msg=$msg;
        $this->type=$type;
    }
    
    public function __cuowu($type){
        if($this->type=='text'||$this->type==''){
            echo "参赛不完整";
            exit;
        }else{
            echo json_encode(array('code'=>'104','msg'=>'参数不完整'),JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function __curl(){
        $curl = curl_init();
        $ch = array(
            CURLOPT_URL=>'https://hanyu.baidu.com/s?wd='.$this->msg.'&ptype=zici',
            CURLOPT_HTTPHEADER=>array('Content-Type: text/html; charset=UTF-8'),
            CURLOPT_RETURNTRANSFER=>1,
            CURLOPT_REFERER=>'https://hanyu.baidu.com',
            CURLOPT_USERAGENT=>'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.74 Safari/537.36',
            );
        curl_setopt_array($curl, $ch);
        $result = curl_exec($curl);
        $this->jieguo=$result;
    }

    public function __jiexi(){
        $is = preg_match_all('/<span>(.*?)<\/span>/', $this->jieguo, $matches);
        $isa = preg_match_all('/<p id="riddle">(.*?)<\/p>/', $this->jieguo, $mat);
        $isaa = preg_match_all('/<b>(.*?)<\/b>/',$this->jieguo,$mayy);
        switch ($this->type) {
            case 'text':
                if($mayy[1][0]==''){
                    echo "查询失败";
                    exit;
                }else{
                echo "拼音：".$mayy[1][0]."\n"."部首：".$matches[1][0]."\n笔画：".$matches[1][1]."\n五行：".$matches[1][2]."\n繁体：".$matches[1][3]."\n五笔：".$matches[1][4]."\n";
                    for ($i = 0; $i < 3; $i++) {
                         echo $mat[1][$i]."\n";
                    }
                    echo "以上是该字谜语(只显示前三)";
                }
                break;
            case 'json':
                if($mayy[1][0]==''){
                    echo json_encode(array('code'=>'104','msg'=>'查询失败'),JSON_UNESCAPED_UNICODE);
                    exit;
                }else{
                for ($i=0; $i < 3; $i++) { 
                    $b[] = $mat[1][$i];
                }
                $a = array('code'=>'200','pinyin'=>$mayy[1][0],'bushou'=>$matches[1][0],'bihua'=>$matches[1][1],'wuhang'=>$matches[1][2],'fanti'=>$matches[1][3],'wubi'=>$matches[1][4],'miyu'=>$b);
                echo json_encode($a,JSON_UNESCAPED_UNICODE);
            }
                break;
            default:
            if($mayy[0][1]==''){
                echo "查询失败";
                exit;
            } else{
            echo "拼音：".$mayy[1][0]."\n"."部首：".$matches[1][0]."\n笔画：".$matches[1][1]."\n五行：".$matches[1][2]."\n繁体：".$matches[1][3]."\n五笔：".$matches[1][4]."\n";
            for ($i = 0; $i < 3; $i++) {
                 echo $mat[1][$i]."\n";
            }
            echo "以上是该字谜语(只显示前三)";
            }
                break;
        }
    }

    

}
$a = new zici;
$a->__canshu($msg,$type);
if($msg==''){
    $a->__cuowu();
    exit;
}else{
$a->__curl();
$a->__jiexi();
exit;
}
?>