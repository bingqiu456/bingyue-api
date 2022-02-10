<?php
function get_ldw($skey,$uin,$pskey){
    $url = 'https://id.qq.com/cgi-bin/get_base_key?r=0.18776368348709482';
    $cookie="skey=".$skey.";p_skey=".$pskey.";uin=o".$uin.";p_uin=o".$uin;
     $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36';
     $re = "https://id.qq.com/index.html";
    $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL,$url);
   curl_setopt($curl, CURLOPT_COOKIE,$cookie);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($curl,CURLOPT_REFERER,$re);
   curl_setopt($curl, CURLOPT_USERAGENT, $ua);
   curl_setopt($curl, CURLOPT_COOKIEJAR,'123.txt');
   $a = curl_exec($curl);
   curl_close($curl);
   return $a;
}
$file = file("123.txt") ;
$a = $file[4];
$b =  str_replace('.id.qq.com	TRUE	/	FALSE	0	ldw	','',$a);
$ldw  = str_replace(array("\n","\r","\n\r"),'',$b);
echo $ldw;
?>