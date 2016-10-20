<?php
require('../PHP/PDOConnect.php');

$adminName = $_POST['adminName'];
$TemplateID = $_POST['TemplateID'];
$TemplateName = $_POST['TemplateName'];
$TemplateGrp = $_POST['TemplateGrp'];
$TemplateCycle = $_POST['TemplateCycle'];
$TemplateTpList = $_POST['TemplateTpList'];

function EditTemplate($adminName,$TemplateID,$TemplateName,$TemplateGrp,$TemplateCycle,$TemplateTpList)
{
    try {        
        global $mypdo;

        $SqlS="select TemplateCycle from t_template where TemplateID=".$TemplateID;
        $objS = $mypdo->scalar($SqlS, 'TemplateCycle');
        $oldTemplateCycle=intval($objS);
        if(intval($TemplateCycle)>=$oldTemplateCycle)
        {
            $SqlS="select TemplateID from t_template where GrpID='".$TemplateGrp."' and TemplateID<>".$TemplateID." and TemplateName='".$TemplateName."'";       
            $objS = $mypdo->scalar($SqlS, 'TemplateID');
            if($objS==null)
            {
                $sql="update t_template set TemplateName='".$TemplateName."',TemplateCycle='".$TemplateCycle."',GrpID='".$TemplateGrp."' where TemplateID=".$TemplateID;
                $mypdo->execute($sql);            
               
                $sql="delete from t_templatetime  where TemplateID=".$TemplateID;
                $mypdo->execute($sql);
                
                for($i=0;$i<count($TemplateTpList);$i++)
                {
                    $tp = explode(":",$TemplateTpList[$i]);      
                    $ts="2016-09-30 ".$tp[0].":00";
                    $te="2016-09-30 ".$tp[1].":00";
                    $sql="insert into t_templatetime(TemplateID,TemplateStart,TemplateEnd) values('".$TemplateID."','".$ts."','".$te."')";
                    $mypdo->execute($sql);
                }
                
                $json_arr = array("msg"=>"OK");
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
        else {
            $json_arr = array("msg"=>"模板大小只能扩大不能缩小!");
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

EditTemplate($adminName,$TemplateID,$TemplateName,$TemplateGrp,$TemplateCycle,$TemplateTpList);
?>