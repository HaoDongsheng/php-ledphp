<?php
require('../PHP/PDOConnect.php');

$adminName = $_POST['adminName'];

$json_arr = array();

$sqlGetAdminIDList="select GrpID from t_admingroup where  adminID in (select ID from t_admin where Name='".$adminName."')";
$adlResult= $mypdo->getAll($sqlGetAdminIDList);
foreach ($adlResult as $row)
{
    $grpID= $row['GrpID'];
    $sqlGetGN="select GrpName from t_grouptaxi where GrpID=".$grpID;
    $GrpName = $mypdo->scalar($sqlGetGN, 'GrpName');

    $grpItem_arr = array("GrpID"=>$grpID,"GrpName"=>$GrpName);
    array_push($json_arr,$grpItem_arr);
}

$json_obj = json_encode($json_arr);
echo $json_obj;

$mypdo->__destruct();
?>