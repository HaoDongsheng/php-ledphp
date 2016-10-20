<?php
require('../PHP/PDOConnect.php');

function ChangePwd($adminName,$oldPwd,$newPwd)
{
    try {
        global $mypdo;
        
        $result = $mypdo ->getOne("SELECT count(*) as sum from t_admin where Name='".$adminName."' and Password='".$oldPwd."'");
        if($result["sum"]>=1)
        {
            $mypdo->execute("update t_admin set Password='".$newPwd."' where Name='".$adminName."'");
            $json_arr = array("msg"=>"OK");
            $json_obj = json_encode($json_arr);
            echo $json_obj;
        }
        else
        {
            $json_arr = array("msg"=>"旧密码不正确！");
            $json_obj = json_encode($json_arr);
            echo $json_obj;
        }
    }
    catch(PDOException $e)
    {
        $json_arr = array("msg"=>$e->getMessage());
        $json_obj = json_encode($json_arr);
        echo $json_obj;
    }
}

$adminName = $_POST['adminName'];
$oldPwd = $_POST['oldPwd'];
$newPwd = $_POST['newPwd1'];

ChangePwd($adminName,$oldPwd,$newPwd);

$mypdo->__destruct();
?>