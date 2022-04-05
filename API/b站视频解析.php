<?php
## 作者:滨河!
## 冰月api https://apii.bingyue.xyz
$msg = $_GET['msg'];
$type = $_GET['type'];
class bilibili
{	
	var $msg;
	
	public function __curl()
	{
	$url = 'https://api.bilibili.com/x/web-interface/view?bvid='.$this->$jiexi;
	$cu = curl_init();
	$ch = array(
        CURLOPT_URL=>$url,
        CURLOPT_HTTPHEADER=>array('content-type: application/json; charset=utf-8'),
        CURLOPT_RETURNTRANSFER=>1,
        CURLOPT_USERAGENT=>'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36',
        );
    curl_setopt_array($cu, $ch);
    $result = curl_exec($cu);
    $this->$data_json=$result;
	}

	public function __z($msg){
	 $this->$jiexi=$msg;
	}

	public function __jiexi($msg,$type){
		$json = new bilibili;
		$json -> __z($msg);
		$json -> __curl();
		$json = json_decode($json->$data_json,true);
		switch ($type) {
			case 'json':
			    if($json['code']!='0'){
			        echo json_encode(array('code'=>'104','msg'=>"解析失败"),JSON_UNESCAPED_UNICODE);
			        exit;
			    }else{
			    	echo json_encode(array(
			    		'code'=>'200',
			    		'bv'=>$json['data']['bvid'],
			    		'av'=>'av'.$json['data']['aid'],
			    		'fenxiang'=>$json['data']['stat']['share'],
			    		'yb'=>$json['data']['stat']['coin'],
			    		'shoucang'=>$json['data']['stat']['favorite'],
			    		'dianzan'=>$json['data']['stat']['like'],
			    		'guankan'=>$json['data']['stat']['view'],
			    		'fm'=>$json['data']['pic'],
			    		'biaoti'=>$json['data']['title'],
			    		'jj'=>$json['data']['desc']
			    	),JSON_UNESCAPED_UNICODE);
			    }
				break;
			case 'text':
				if($json['code']!='0'){
					echo '解析失败';
					exit;
				}else{
					echo '标题:'.$json['data']['title']."\n".'封面:±img='.$json['data']['pic'].'±'."\n硬币:".$json['data']['stat']['coin']."\n收藏:".$json['data']['stat']['favorite']."\n观看:".$json['data']['stat']['view']."\n点赞:".$json['data']['stat']['like']."\n分享:".$json['data']['stat']['share']."\n简介:".$json['data']['desc'];
				}
				break;
			default:
				if($json['code']!='0'){
					echo '解析失败';
					exit;
				}else{
					echo '标题:'.$json['data']['title']."\n".'封面:±img='.$json['data']['pic'].'±'."\n硬币:".$json['data']['stat']['coin']."\n收藏:".$json['data']['stat']['favorite']."\n观看:".$json['data']['stat']['view']."\n点赞:".$json['data']['stat']['like']."\n分享:".$json['data']['stat']['share']."\n简介:".$json['data']['desc'];
				}
				break;
		}
	}
	public function __cuowu($type){
	    if($type==''||$type=='text'){
	        echo '请输入视频号';
	        exit;
	    }else{
	        echo json_encode(array('code'=>'104','msg'=>'请输入视频号'),JSON_UNESCAPED_UNICODE);
	    }
	}
}
$a = new bilibili;
if($msg==''){
$a -> __cuowu($type);
exit;
}else{
    $a -> __jiexi($msg,$type);
}
?>