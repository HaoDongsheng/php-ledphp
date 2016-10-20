<?php
require('../PHP/PDOConnect.php');

$adminName = $_POST['adminName'];
$TemplateID = $_POST['TemplateID'];

function DeleteTem()
{
    try {
        global  $adminName ,$TemplateID;
        global $mypdo;
        
        $SqlS="select count(*) as infoSum from t_advertisement where TemplateID='".$TemplateID."'";
        $infoSum = intval($mypdo->scalar($SqlS, 'infoSum'));

        if($infoSum<=0)
        {
            $sqlDel="delete from t_template where TemplateID =".$TemplateID;
            $mypdo->execute($sqlDel);
            
            $sqlDel="delete from t_templatetime where TemplateID =".$TemplateID;
            $mypdo->execute($sqlDel);
            
            $json_arr = array("msg"=>"OK");
            $json_obj = json_encode($json_arr);
            echo $json_obj;
        }
        else 
        {
            $json_arr = array("msg"=>"模板存在广告不能删除");
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

DeleteTem();
?>