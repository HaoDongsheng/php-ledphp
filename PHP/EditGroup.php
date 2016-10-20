<?php
require('../PHP/PDOConnect.php');

$adminName = $_POST['adminName'];
$GrpName = $_POST['GrpName'];
$GrpID = $_POST['GrpID'];

function EditGrp()
{
    try {
        global  $adminName ,$GrpName,$GrpID;

        $SqlS="select GrpID from t_grouptaxi where GrpName='".$GrpName."'";
        global $mypdo;

        $objS = $mypdo->scalar($SqlS, 'GrpID');
        if($objS==null)
        {
            $sql="update t_grouptaxi set GrpName='".$GrpName."' where GrpID=".$GrpID;
            $mypdo->execute($sql);
            
            $json_arr = array("msg"=>"OK","grpName"=>$GrpName);
            $json_obj = json_encode($json_arr);
            echo $json_obj;           
        }
        else
        {
            $json_arr = array("msg"=>"分组名称已存在!");
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

EditGrp();
?>