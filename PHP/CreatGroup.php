<?php
require('../PHP/PDOConnect.php');

$adminName = $_POST['adminName'];
$GrpName = $_POST['GrpName'];

function CreatGrp()
{
    try {
        global  $adminName ,$GrpName;

        $SqlS="select GrpID from t_grouptaxi where GrpName='".$GrpName."'";
        
        global $mypdo;

        $objS = $mypdo->scalar($SqlS, 'GrpID');
        if($objS==null)
        {
            $Sqladmin="select ID from t_admin where Name='".$adminName."'";
            $adminID = intval($mypdo->scalar($Sqladmin, 'ID'));
            
            $SqlSum="select count(*) as grpSum from t_admingroup where adminID in (select ID from t_admin where Name='".$adminName."')";
            $grpSum = intval($mypdo->scalar($SqlSum, 'grpSum'));
            
            $SqlSum="select GrpSum from t_admin where Name='".$adminName."'";
            $SqlgrpSum = intval($mypdo->scalar($SqlSum, 'GrpSum'));
            
            if($grpSum<$SqlgrpSum)
            {
                $sql="insert into t_grouptaxi(GrpName,DelIndex) values('".$GrpName."',0)";
                $mypdo->execute($sql);
                
                $Sql="select GrpID from t_grouptaxi where GrpName='".$GrpName."'";
                $grpID = intval($mypdo->scalar($SqlS, 'GrpID'));
                
                $sql="insert into t_admingroup(adminID,GrpID) values(".$adminID.",".$grpID.")";
                $mypdo->execute($sql);
                $json_arr = array("msg"=>"OK","grpID"=>$grpID,"grpName"=>$GrpName);
                $json_obj = json_encode($json_arr);
                echo $json_obj;
            }
            else 
            {
                $json_arr = array("msg"=>"可管理的分组已满,不能创建!");
                $json_obj = json_encode($json_arr);
                echo $json_obj;
            }
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

CreatGrp();
?>