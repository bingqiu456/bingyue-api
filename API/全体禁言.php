<?php
## 作者:滨河!
## 冰月api：http://apii.bingyue.xyz
 function getGTK($skey){
    $hash = 5381;
    for($i=0;$i<strlen($skey);++$i){
        $hash += ($hash<<5) + utf8_uni($skey[$i]);
    }
    return $hash & 0x7fffffff;
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
	 function k($k)
	{
		if($k=='1'){
			return '4294967295';
			exit;
		}else if($k=='2'){
			return '0';
			exit;
		}else{
		    return '4294967295';
		    exit;
		}
	}
	/**
 * 全体禁言
 */
class quanti
{
	var $skey;
	var $pskey;
	var $k;
	var $group;
	var $uin;
	
	public function __cuowu($type){
		if($type==''||$type=='text'){
			echo '请输入参数';
			exit;
		}else{
			echo json_encode(array('code'=>'103','msg'=>'请输入参数'),JSON_UNESCAPED_UNICODE);
			exit;
		}
	}

	public function __canshu($skey,$pskey,$uin,$group,$k){
		$this->skey=$skey;
		$this->pskey=$pskey;
		$this->uin=$uin;
		$this->group=$group;
		$bkn = getGTK($skey);
		$this->bkn=$bkn;
		$k = k($k);
		$this->k=$k;
	}

	public function __curl(){
		  $cookie="skey=".$this->skey.";p_skey=".$this->pskey.";uin=o".$this->uin.";p_uin=o".$this->uin;
		$post = 'src=qinfo_v3&gc='.$this->group.'&bkn='.$this->bkn.'&all_shutup='.$this->k;
		$curl = curl_init();
		$cu = array(
   CURLOPT_URL=>'https://qinfo.clt.qq.com/cgi-bin/qun_info/set_group_shutup',
   CURLOPT_HTTPHEADER=>array('Content-Type: application/x-www-form-urlencoded'),
   CURLOPT_COOKIE=>$cookie,
   CURLOPT_REFERER=>'https://qinfo.clt.qq.com/qinfo_v3/setting.html',
   CURLOPT_RETURNTRANSFER=>1,
   CURLOPT_POST=>1,
   CURLOPT_POSTFIELDS=>$post,
   CURLOPT_USERAGENT=>'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) QQ/9.4.9.27847 Chrome/43.0.2357.134 Safari/537.36 QBCore/3.43.1298.400 QQBrowser/9.0.2524.400',
		);
		curl_setopt_array($curl, $cu);
		$return = curl_exec($curl);
		$this->return=$return;
	}
	public function __jiexi($type){
		$json = json_encode($this->return,true);
		switch ($type) {
			case 'json':
				if($json['ec']!='0'){
					echo json_encode(array('code'=>'103','msg'=>'禁言失败，请检查参数或者权限'),JSON_UNESCAPED_UNICODE);
					exit;
				}else{
					echo json_encode(array('code'=>'200','msg'=>'禁言成功'),JSON_UNESCAPED_UNICODE);
					exit;
				}
				break;
			case 'text':
				if($json['ec']!='0'){
					echo '禁言失败，请检查参数或者权限';
					exit;
				}else{
					echo '禁言成功';
					exit;
				}
				break;
			default:
				if($json['ec']!='0'){
					echo json_encode(array('code'=>'103','msg'=>'禁言失败，请检查参数或者权限'),JSON_UNESCAPED_UNICODE);
					exit;
				}else{
					echo json_encode(array('code'=>'200','msg'=>'禁言成功'),JSON_UNESCAPED_UNICODE);
					exit;
				}
				break;
		}
	}
}
	$skey = $_GET['skey'];
	$pskey = $_GET['pskey'];
	$uin = $_GET['uin'];
	$group = $_GET['group'];
	$k = $_GET['k'];
	$type = $_GET['type'];
$a = new quanti;
if($skey==''||$pskey==''||$uin==''||$group==''){
    $a -> __cuowu($type);
    exit;
}else{
	$a ->__canshu($skey,$pskey,$uin,$group,$k);
	$a -> __curl();
	$a -> jiexi($type);
	exit;
}
?>