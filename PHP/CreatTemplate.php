<?php
require('../PHP/PDOConnect.php');

$adminName = $_POST['adminName'];
$TemplateName = $_POST['TemplateName'];
$TemplateGrp = $_POST['TemplateGrp'];
$TemplateCycle = $_POST['TemplateCycle'];
$TemplateTpList = $_POST['TemplateTpList'];

function CreatTemplate($adminName,$TemplateName,$TemplateGrp,$TemplateCycle,$TemplateTpList)
{
    try {        
        global $mypdo;

        $SqlS="select TemplateID from t_template where GrpID='".$TemplateGrp."' and TemplateName='".$TemplateName."'";       
        $objS = $mypdo->scalar($SqlS, 'TemplateID');
        if($objS==null)
        {
            $sql="insert into t_template(TemplateName,TemplateCycle,GrpID) values('".$TemplateName."','".$TemplateCycle."','".$TemplateGrp."')";
            $mypdo->execute($sql);
            
            $SqlS="select TemplateID from t_template where GrpID='".$TemplateGrp."' and TemplateName='".$TemplateName."'";
            $TemplateID = intval($mypdo->scalar($SqlS, 'TemplateID'));
           
            for($i=0;$i<count($TemplateTpList);$i++)
            {
                $tp = explode(":",$TemplateTpList[$i]);      
                $ts="2016-09-30 ".$tp[0].":00";
                $te="2016-09-30 ".$tp[1].":00";
                $sql="insert into t_templatetime(TemplateID,TemplateStart,TemplateEnd) values('".$TemplateID."','".$ts."','".$te."')";
                $mypdo->execute($sql);
            }
            
            $json_arr = array("msg"=>"OK","TemplateID"=>$TemplateID);
            $json_obj = json_encode($json_arr);
            echo $json_obj;
        }
        else
        {
            $json_arr = array("msg"=>"模板名称已存在!");
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

CreatTemplate($adminName,$TemplateName,$TemplateGrp,$TemplateCycle,$TemplateTpList);
?>