<?php
$type = $_GET['type'];
$file = file('xxxx.txt');
$n = rand(0,count($file,0));
switch ($type) {
    case 'text':
        echo str_replace(array("\n","\r","\r\n"),"",$file[$n]);
        break;
    case 'json':
        $a = str_replace(array("\n","\r","\r\n"),"",$file[$n]);
        echo json_encode(array('msg'=>$a),JSON_UNESCAPED_UNICODE);
        break;
    default:
          echo str_replace(array("\n","\r","\r\n"),"",$file[$n]);
        break;
}
?>