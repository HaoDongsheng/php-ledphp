<!DOCTYPE html>

<html>
<meta charset="UTF-8">
<body>

<?php
require('../PHP/PDOConnect.php');
require('../Class/infoItem.php');
$ADID = $_GET['ADID'];
function GetTemplate()
{
    if(!isset($_SESSION)){
        session_start();
    }
    if(isset($_SESSION['advInfo']))
    {
        $arrayTemplate=$_SESSION['advInfo']->arrayTemplate;
        if($arrayTemplate!=null && count($arrayTemplate)>0)
        {return $arrayTemplate[0];}
    }
    else {return null;}
}

function GetSTList($arrayTemplate) {
    global $mypdo,$ADID;
    if(!isset($_SESSION)){
        session_start();
    }
    $arrayTemST=array();
    $GrpID = $_SESSION['advInfo']->GrpID;
    $dts = $_SESSION['advInfo']->lifeAct;
    $dte = $_SESSION['advInfo']->lifeDie;
    $strSelInfo = "select ADID,TemplateID,playingTime from T_advertisement where  DelIndex<>2 and ";
    $strSelInfo=$strSelInfo."GrpID=".$GrpID." and advType=". $_SESSION['advInfo']->advType." and ADID<>".$ADID." and ((LifeAct>='".$dts."' and LifeAct<='".$dte."') or (LifeDie>='".$dts."' and LifeDie<='".$dte."') or (LifeAct<='".$dts."' and LifeDie>='".$dte."'))";
    $getInfoRs= $mypdo->getAll($strSelInfo);
    foreach ($getInfoRs as $row)
    {
        $orderADID=$row['ADID'];
        $orderTemplateID=$row['TemplateID'];
        $orderplayingTime=$row['playingTime'];
        
        $orderTemplate=new Template();
        $orderTemplate->TemplateID=intval($orderTemplateID);
        $strGetTemplateSql="select * from t_template where TemplateID=".$orderTemplateID;
        $getGetTemplateRs= $mypdo->getAll($strGetTemplateSql);
        $orderTemplate->TemplateName=$getGetTemplateRs[0]["TemplateName"];
        $orderTemplate->TemplateCycle=$getGetTemplateRs[0]["TemplateCycle"];
        $strGetTTSql= "select * from T_TemplateTime where TemplateID=".$orderTemplateID;
        $getGetTTRs= $mypdo->getAll($strGetTTSql);
        $isJ=false;
        foreach ($getGetTTRs as $rowTt)
        {
            $TimePartCls=new TimePartCls();
            $TimePartCls->tStart=$rowTt["TemplateStart"];
            $TimePartCls->tEnd=$rowTt["TemplateEnd"];
            foreach ($arrayTemplate->TemplateList as $TemplateTime)
            {
                if(($TemplateTime->tStart<=$TimePartCls->tStart && $TemplateTime->tStart>=$TimePartCls->tEnd) || ($TemplateTime->tEnd<=$TimePartCls->tStart && $TemplateTime->tEnd>=$TimePartCls->tEnd) || ($TemplateTime->tStart<=$TimePartCls->tStart && $TemplateTime->tEnd>=$TimePartCls->tEnd))
                {$isJ=true;break;}
            }
             
            array_push($orderTemplate->TemplateList,$TimePartCls);
        }
        
        if($isJ)
        {
            $templateST=new TemplateST();
            $templateST->ADID=$orderADID;
            $templateST->infoTimeLength=$orderplayingTime;
            $strGetTem = "select RelativeTime from T_TemST where ADID=".$orderADID." and GrpID=".$GrpID." and DelIndex=0";
            $getSTRs= $mypdo->getAll($strGetTem);
            if(count($getSTRs)>0)
            {
                if($orderTemplate->TemplateID==$arrayTemplate->TemplateID)
                {
                    foreach ($getSTRs as $row)
                    {        
                        $RelativeTime=intval($row["RelativeTime"]);
                        array_push($templateST->arrayST, $RelativeTime);                    
                    }
                    array_push($arrayTemST,$templateST);
                }    
                else 
                {     
                    foreach ($getSTRs as $row)
                    {
                        $RelativeTime=intval($row["RelativeTime"]);
                        var_dump($orderTemplate);
                        foreach ($orderTemplate->TemplateList as $TemplateTime)
                        {
                            $tStart = date('H:i:s',strtotime($TemplateTime->tStart));
                            $tEnd = date('H:i:s',strtotime($TemplateTime->tEnd));
                            var_dump($orderplayingTime);
                            for($t=$tStart;$t<$tEnd;$t= date('H:i:s',strtotime($t.' +'.$orderplayingTime.' second' )))
                            {
                                $startDt=$t;
                                //var_dump($startDt);
                                //var_dump($RelativeTime);
                                $startDt =date('H:i:s',strtotime($startDt.' +'.$RelativeTime.' second'));
                                //var_dump($startDt);
                                foreach ($arrayTemplate->TemplateList as $TemplateTime)
                                {
                                    if($startDt>=date('H:i:s',strtotime($TemplateTime->tStart)) &&$startDt<=date('H:i:s',strtotime($TemplateTime->tEnd)))
                                    {                                    
                                        $second=floor((strtotime($startDt)-strtotime($TemplateTime->tStart)));
                                        $orderRelativeTime = $second%$arrayTemplate->TemplateCycle;                            
                                        if (in_array($orderRelativeTime, $templateST->arrayST)==false)
                                        { array_push($templateST->arrayST, $RelativeTime); }
                                        break;
                                    }
                                }                            
                            }
                        }
                    }
                    array_push($arrayTemST,$templateST);
                }
            }        
        }
    }
    $_SESSION['TemST']=$arrayTemST;
}
$arrayTemplate=GetTemplate();
$templateCycle=intval($arrayTemplate->TemplateCycle);
var_dump($templateCycle);

GetSTList($arrayTemplate);
var_dump($_SESSION['TemST']);
?>  
  
</body>
</html>