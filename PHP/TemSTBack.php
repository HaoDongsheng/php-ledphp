<?php
require('../Class/infoItem.php');

$TemID = $_POST['TemID'];
$TemName = $_POST['TemName'];
$TemCycle = $_POST['TemCycle'];
$TemList= $_POST['TemList'];

function TemSTBack() {
    try {
        global $TemID,$TemName,$TemCycle,$TemList;
        if(!isset($_SESSION)){
            session_start();
        }
        if(isset($_SESSION['advInfo']))
        {
            $advInfo=$_SESSION['advInfo'];
            if($advInfo->arrayTemplate[0]->TemplateID==$TemID)
            {
                if($advInfo->STIndex==1)
                {return true;}
                else 
                {return false;}
            }
            else 
            {
                $advInfo->arrayTemplate[0]->TemplateID=$TemID;
                $advInfo->TemplateID=$TemID;
                $advInfo->arrayTemplate[0]->TemplateName=$TemName;
                $advInfo->arrayTemplate[0]->TemplateCycle=$TemCycle;
                $advInfo->arrayTemplate[0]->TemplateList=array();
                $arrayTimePart = explode('|', $TemList); 
                foreach($arrayTimePart as $tp)
                {
                    if($tp!="")
                    {
                        $TimePartCls=new TimePartCls();
                        $TimePartCls->tStart=explode("---", $tp)[0];
                        $TimePartCls->tEnd=explode("---", $tp)[1];
                        array_push($advInfo->arrayTemplate[0]->TemplateList, $TimePartCls);
                    }
                }
                $advInfo->TemplateST->arrayST=array();
                $advInfo->STIndex=1;
                $advInfo->TemplateIndex=1;
                return true;
            }            
        }
        else
        {return false;}
    }
    catch (Exception $ex){return false;}    
}

if(TemSTBack())
{
    $json_arr = array("msg"=>"OK");
    $json_obj = json_encode($json_arr);
    echo $json_obj;
}
else { $json_arr = array("msg"=>"Error");
    $json_obj = json_encode($json_arr);
    echo $json_obj;}
?>