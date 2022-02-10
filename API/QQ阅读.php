<?php
$msg = $_GET['msg'];
$n = $_GET['n'];
$type = $_GET['type'];
$p = $_GET['p'];
$p = p($p);
if($msg==''){
    echo cuowu($type);
}else if(!is_numeric($n)){
    $sous  = new qqyd;
    $sous -> __sous($msg,$p,$type);
    exit;
}else{
    $sous = new qqyd;
    $sous -> __xz($msg,$n,$type);
    exit;
}
 class qqyd{   
    public function __sous($msg,$p,$type)
    {
        
    $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL,'https://book.qq.com/api/booksearch/query?keyWord='.$msg.'&pageNo=1&pageSize=40');
   curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36');
   $a = curl_exec($curl);
   curl_close($curl);
   $json =  json_decode($a,true);
   $p_2_end = $p*10;
   $p_2_strat = $p*10-9-1;
   switch ($type) {
       case 'json':
           if($json['data']['bookList'][$p_2_strat]['title']==''){
               echo json_encode(array('code'=>'104','msg'=>'找不到搜索的书籍'),JSON_UNESCAPED_UNICODE);
               exit;
           }else{
               for ($i = $p_2_strat; $i < $p_2_end; $i++) {
                    $aa = $json['data']['bookList'][$i]['title'].' ---- '.$json['data']['bookList'][$i]['author'];
                    $aaa[] =$aa;
               }
               $abc = array('code'=>'200','msg'=>'获取成功','data'=>$aaa);
               echo json_encode($abc,JSON_UNESCAPED_UNICODE);
           }
           break;
       case 'text':
           if($json['data']['bookList'][$p_2_strat]['title']==''){
               echo '找不到搜索的书籍';
               exit;
           }else{
               echo '————小说列表————'."\n";
               for ($i = $p_2_strat; $i < $p_2_end; $i++) {
                   $c = $i+1;
                    echo $c.','.$json['data']['bookList'][$i]['title'].' ---- '.$json['data']['bookList'][$i]['author'].PHP_EOL;
               }
               echo '————————————————'.'提示:当前在第'.$p.'页';
           }
       default:
             if($json['data']['bookList'][$p_2_strat]['title']==''){
               echo '找不到搜索的书籍';
               exit;
           }else{
               echo '————小说列表————'."\n";
               for ($i = $p_2_strat; $i < $p_2_end; $i++) {
                   $c = $i+1;
                    echo $c.','.$json['data']['bookList'][$i]['title'].' ---- '.$json['data']['bookList'][$i]['author'].PHP_EOL;
               }
               echo '————————————'."\n".'提示:当前在第'.$p.'页';
           }
           break;
   }
    }
    public function __xz($msg,$n,$type){
    $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL,'https://book.qq.com/api/booksearch/query?keyWord='.$msg.'&pageNo=1&pageSize=40');
   curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36');
   $a = curl_exec($curl);
   curl_close($curl);
   $json =  json_decode($a,true);
   $abc = substr($json['data']['bookList'][$n-1]['bid'],-3);
   switch ($type) {
       case 'text':
           if($json['data']['bookList'][$n-1]['bid']==''){
               echo "你搜索的书籍不存在";
               exit;
           }else{
           echo "封面±img=".'https://wfqqreader-1252317822.image.myqcloud.com/cover/'.$abc.'/'.$json['data']['bookList'][$n-1]['bid'].'/t5_'.$json['data']['bookList'][$n-1]['bid'].'.jpg±'."\n".'作者:'.$json['data']['bookList'][$n-1]['author']."\n".'书名：'.$json['data']['bookList'][$n-1]['title']."\n".'简介:'.$json['data']['bookList'][$n-1]['intro']."\n".'链接:https://book.qq.com/book-detail/'.$json['data']['bookList'][$n-1]['bid'];
           }
           break;
       case 'json':
           if($json['data']['bookList'][$n-1]['bid']==''){
               echo json_encode(array('code'=>'104','msg'=>'你搜索的书籍不存在'),JSON_UNESCAPED_UNICODE);
               exit;
           }else{
               echo json_encode(array(
            'code'=>'200',
            'fm'=>'https://wfqqreader-1252317822.image.myqcloud.com/cover/'.$abc.'/'.$json['data']['bookList'][$n-1]['bid'].'/t5_'.$json['data']['bookList'][$n-1]['bid'].'.jpg',
            'zuojia'=>$json['data']['bookList'][$n-1]['author'],
            'shuming'=>$json['data']['bookList'][$n-1]['title'],
            'intro'=>$json['data']['bookList'][$n-1]['intro'],
            'url'=>'https://book.qq.com/book-detail/'.$json['data']['bookList'][$n-1]['bid']
               ),JSON_UNESCAPED_UNICODE
               );
           }
           break;
       default:
             if($json['data']['bookList'][$n-1]['bid']==''){
               echo "你搜索的书籍不存在";
               exit;
           }else{
           echo "封面±img=".'https://wfqqreader-1252317822.image.myqcloud.com/cover/'.$abc.'/'.$json['data']['bookList'][$n-1]['bid'].'/t5_'.$json['data']['bookList'][$n-1]['bid'].'.jpg±'."\n".'作者:'.$json['data']['bookList'][$n-1]['author']."\n".'书名：'.$json['data']['bookList'][$n-1]['title']."\n".'简介:'.$json['data']['bookList'][$n-1]['intro']."\n".'链接:https://book.qq.com/book-detail/'.$json['data']['bookList'][$n-1]['bid'];
           }
           break;
   }
    }
}
function cuowu($type){
    if($type==''||$type=='text'){
    return "请输入书名";
    exit;
    }else{
        return json_encode(array('code'=>'104','msg'=>'请输入书名'),JSON_UNESCAPED_UNICODE);
        exit;
    }
}
function p($p){
    if($p==''||!is_numeric($p)){
        return  '1';
        exit;
    }else{
        return $p;
    }
}
?>