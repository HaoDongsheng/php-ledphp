<?php

require('../PHP/PDOConnect.php');
$ADID = $_POST['ADID'];
try {
$sql="update t_advertisement set Infostatus=3 where ADID=".$ADID;
$mypdo->execute($sql);

$json_arr = array("msg"=>'OK');
$json_obj = json_encode($json_arr);
echo $json_obj;
}
catch (Exception $ex)
{$json_arr = array("msg"=>'审核失败');
$json_obj = json_encode($json_arr);
echo $json_obj;}
?>