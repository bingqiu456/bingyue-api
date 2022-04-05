<?php
## 获取QQ空间最新说说
## 作者 滨河
## 冰月api http://apii.bingyue.xyz
$skey = $_GET['skey']; $pskey= $_GET['pskey']; $uin=$_GET['uin']; $type = $_GET['type'];
function getGTK($pskey){
    $hash = 5381;
    for($i=0;$i<strlen($pskey);++$i){
        $hash += ($hash<<5) + utf8_uni($pskey[$i]);
    }
    return $hash & 0x7fffffff;
}
    function jk_gtk($pskey){
    $len = strlen($pskey);
    $hash = 5381;
    for ($i = 0; $i < $len; $i++) {
    $hash += ($hash << 5 & 2147483647) + ord($pskey[$i]) & 2147483647;
    $hash &= 2147483647;
    }
    return $hash & 2147483647;
    }
function utf8_uni($u){
    switch (strlen($u)) {
        case 1:
            return ord($u);
        case 2:
            $n = (ord($u[0]) & 0x3f) << 6;
            $n += ord($u[1]) & 0x3f;
        case 3:
            $n = (ord($u[0]) & 0x1f) << 12;
            $n += (ord($u[0]) & 0x3f) << 6;
            $n += ord($u[2]) & 0x3f;
            return $n;
        case 4:
            $n = (ord($u[0]) & 0x0f) << 18;
            $n += (ord($u[1]) & 0x3f) << 12;
            $n += (ord($u[2]) & 0x3f) << 6;
            $n += ord($u[3]) & 0x3f;
            return $n;
    }
}
/**
 * 空间获取最新说说
 */
class qzone_all
{

	public function __bkn($pskey)
	{
		$a = jk_gtk($pskey);
		$this->bkn=$a;
	}

	public function __cuowu($type)
	{
		if($type==''||$type=='text'){
			echo "参数不完整";
			exit;
		}else{
			echo json_encode(array('code'=>'103','msg'=>'参数不完整'),JSON_UNESCAPED_UNICODE);
			exit;
		}
	}

	public function __canshu($skey,$pskey,$uin){
		$this->skey=$skey;
		$this->uin=$uin;
		$this->pskey=$pskey;
	}

	public function __curl(){
	    $cookie="skey=".$this->skey.";p_skey=".$this->pskey.";uin=o".$this->uin.";p_uin=o".$this->uin;
		$curl = curl_init();
		$cu = array(
    CURLOPT_URL=>'https://h5.qzone.qq.com/webapp/json/mqzone_feeds/getActiveFeeds?g_tk='.$this->bkn,
    CURLOPT_COOKIE=>$cookie,
    CURLOPT_RETURNTRANSFER=>1,
		);
		curl_setopt_array($curl, $cu);
		$this->retur=curl_exec($curl);
	}

	public function __jiexi($type){
		$json = json_decode($this->retur,true);
		switch ($type) {
			case 'json':
				if($json['code']!='0'){
					echo json_encode(array('code'=>'103','msg'=>'登录过期，请重新登录'),JSON_UNESCAPED_UNICODE);
					exit;
				}else{
			$ab = $json['data']['vFeeds'];
			$b2 = count($ab,1);
					for ($i=0; $i < $b2; $i++) { 
						$a[] = $json['data']['vFeeds'][$i]['summary']['summary'];
						$b[] = $json['data']['vFeeds'][$i]['userinfo']['user']['uin'];
						$c[] = $json['data']['vFeeds'][$i]['userinfo']['user']['nickname'];
						$d[] = $json['data']['vFeeds'][$i]['id']['cellid'];
						$e[] = $json['data']['vFeeds'][$i]['operation']['weixin_url'];
						$f[] = $json['data']['vFeeds'][$i]['operation']['qq_url'];
					}
					echo json_encode(array(
						'code'=>'200',
						'msg'=>'获取成功',
						'msg'=>$a,
						'qq'=>$b,
						'name'=>$c,
						'id'=>$d,
						'weixinshare'=>$e,
						'qqshare'=>$f

					),JSON_UNESCAPED_UNICODE);
				}
				break;
			case 'text':
			 if($json['code']!='0'){
			     echo '登录失败';
			     exit;
			 }else{
			echo '————————'."\n";
			$ab = $json['data']['vFeeds'];
			$b2 = count($ab,0);
			for ($i=0; $i < $b2; $i++) { 
				echo 'QQ:'.$json['data']['vFeeds'][$i]['userinfo']['user']['uin']."\n".'昵称:'.$json['data']['vFeeds'][$i]['userinfo']['user']['nickname']."\n".'内容:'.$json['data']['vFeeds'][$i]['summary']['summary'];
				echo "\n"."————————"."\n";
				echo "冰月api -- https://apii.bingyue.xyz";
			}
			 }
			break;
			default:
			 if($json['code']!='0'){
			     echo '登录失败';
			     exit;
			 }else{
			echo '————————'."\n";
			$ab = $json['data']['vFeeds'];
			$b2 = count($ab,0);
			for ($i=0; $i < $b2; $i++) { 
				echo 'QQ:'.$json['data']['vFeeds'][$i]['userinfo']['user']['uin']."\n".'昵称:'.$json['data']['vFeeds'][$i]['userinfo']['user']['nickname']."\n".'内容:'.$json['data']['vFeeds'][$i]['summary']['summary'];
				echo "\n"."————————"."\n";
				echo "冰月api -- https://apii.bingyue.xyz";
			}
			 }
				break;
		}
}
    }
$a = new qzone_all;
if($skey==''||$pskey==''||$uin==''){
    $a->__cuowu($type);
}else{
$a -> __bkn($pskey);
$a -> __canshu($skey,$pskey,$uin);
$a -> __curl();
$a -> __jiexi($type);
}

?>