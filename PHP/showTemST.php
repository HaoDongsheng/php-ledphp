<?php
require('../PHP/PDOConnect.php');
require('../Class/infoItem.php');

function openTemST() {
    global  $mypdo;
     if(!isset($_SESSION)){
        session_start();
    }    
    if(isset($_SESSION['advInfo']))
    {
       $advInfo=$_SESSION['advInfo'];
       
       $Template=null;
       $arrayTemplate=$advInfo->arrayTemplate;
       if($arrayTemplate!=null && count($arrayTemplate)>0)
       {$Template = $arrayTemplate[0];}
       
       $TemplateST=$advInfo->TemplateST;
       
       $arrayTemList=array();
        $getTemSql="select * from t_template";
        $getTemRs= $mypdo->getAll($getTemSql);
        foreach ($getTemRs as $row)
        {
            $orderTemplate=new Template();
            $orderTemplate->TemplateID=intval($row["TemplateID"]);
            $orderTemplate->TemplateName=$row["TemplateName"];
            $orderTemplate->TemplateCycle=$row["TemplateCycle"];
            $strGetTTSql= "select * from T_TemplateTime where TemplateID=".$orderTemplate->TemplateID;
            $getGetTTRs= $mypdo->getAll($strGetTTSql);
            foreach ($getGetTTRs as $rowTt)
            {
                $TimePartCls=new TimePartCls();
                $TimePartCls->tStart=$rowTt["TemplateStart"];
                $TimePartCls->tEnd=$rowTt["TemplateEnd"];
                array_push($orderTemplate->TemplateList, $TimePartCls);
            }
            array_push($arrayTemList,$orderTemplate);
        }
       
       $json_arr = array("ArrayTp"=>$arrayTemList,"TemplateName"=>$Template->TemplateName,"TemplateST"=>$TemplateST);
       $json_obj = json_encode($json_arr);
       echo $json_obj;
    }
}

openTemST();
?>