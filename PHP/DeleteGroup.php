<?php
require('../PHP/PDOConnect.php');

$adminName = $_POST['adminName'];
$GrpID = $_POST['GrpID'];

function DeleteGrp()
{
    try {
        global  $adminName ,$GrpID;
        global $mypdo;
        
        $SqlS="select count(*) as infoSum from t_advertisement where GrpID='".$GrpID."'";
        $infoSum = intval($mypdo->scalar($SqlS, 'infoSum'));

        if($infoSum<=0)
        {
            $sqlDel="delete from t_grouptaxi where GrpID =".$GrpID;
            $mypdo->execute($sqlDel);
            
            $sqlDel="delete from t_admingroup where GrpID =".$GrpID;
            $mypdo->execute($sqlDel);
            
            $json_arr = array("msg"=>"OK");
            $json_obj = json_encode($json_arr);
            echo $json_obj;
        }
        else 
        {
            $json_arr = array("msg"=>"分组存在广告不能删除");
            $json_obj = json_encode($json_arr);
            echo $json_obj;
        }        
    }
    catch (Exception $ex){

        $json_arr = array("msg"=>$ex->getMessage());
        $json_obj = json_encode($json_arr);
        echo $json_obj;
    }
}

DeleteGrp();
?>