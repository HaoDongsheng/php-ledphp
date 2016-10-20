<?php
require('../PHP/PDOConnect.php');
require('../Class/infoItem.php');

$srceenW = $_GET['srceenW'];
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
                    foreach ($orderTemplate->TemplateList as $TemplateTime)
                    {
                        $tStart = $TemplateTime->tStart;
                        $tEnd = $TemplateTime->tEnd;
                        for($t=$tStart;$t<$tEnd;$t=$t+$orderplayingTime)
                        {
                            $startDt=$t;
                            date_add($startDt, date_interval_create_from_date_string($RelativeTime." seconds"));
                            
                            foreach ($arrayTemplate->TemplateList as $TemplateTime)
                            {
                                if($startDt>=$TemplateTime->tStart &&$startDt<=$TemplateTime->tEnd)
                                {
                                    $second=floor((strtotime($startDt)-strtotime($TemplateTime->tStart)));
                                    $RelativeTime = $second%$arrayTemplate->TemplateCycle;
                                    if (in_array($RelativeTime, $templateST->arrayST)==false)
                                    { array_push($templateST->arrayST, $RelativeTime); }
                                    break;
                                }
                            }                            
                        }
                    }
                }
            }        
        }
    }
    $_SESSION['TemST']=$arrayTemST;
}

function DrawST()
{
    global $srceenW;
    
    $arrayTemplate=GetTemplate();
    $templateCycle=intval($arrayTemplate->TemplateCycle);

    GetSTList($arrayTemplate);
    
    $marginLeft=20;$marginTop=20;
    $pointW=20;
    $pointH=10;
    $colCount=intval(($srceenW - 2*$marginLeft)/$pointW);
    $rowCount=ceil($templateCycle/$colCount);
    $srceenH=$rowCount*$pointH + 2*$marginTop;
    
    $im = imagecreatetruecolor($srceenW, $srceenH);
    
    $col_bc = imagecolorallocate($im, 128, 128, 128);
    $col_red = imagecolorallocate($im, 255, 0, 0);
    
    imagefilledrectangle($im, 0, 0,$srceenW,$srceenH, $col_bc);
    // 选择椭圆的颜色
    $col_white = imagecolorallocate($im, 255, 255, 255);
    $col_blue = imagecolorallocate($im, 0, 0, 255);
    $font="c://Windows//Fonts//simsun.ttc";
    
    $pcount=0;
    for($t=0;$t<$rowCount;$t++)
    {
        $top=$t*$pointH +$marginTop ;
        if($t%5==0)
        {
            imagettftext($im, 12, 90, $marginLeft, $top + $pointH, $col_red, $font, $t * $colCount);
        }
        for($l=0;$l<$colCount;$l++)
        {
            if($l%5==0 && $t==0)
            {imagettftext($im, 12, 0, $l * $pointW + 2 + $marginLeft, $marginTop, $col_red, $font, $l);}
            // 画一个矩形
            if(!isset($_SESSION)){
                session_start();
            }
            $col_ellipse = $col_white;
            if(isset($_SESSION['TemST']))
            {
                $arrayTemST=$_SESSION['TemST'];
                foreach ($arrayTemST as $TemST)
                {
                    foreach ($TemST->arrayST as $StartIndex)
                    {
                        if($pcount>=$StartIndex && $pcount<=$StartIndex+$TemST->infoTimeLength - 1)
                        {$col_ellipse=$col_blue;break;}
                    }
                    if($col_ellipse==$col_blue){break;}
                }
            }
            
             if(isset($_SESSION['advInfo']))
             {
                 if($_SESSION['advInfo']->TemplateST!=null)
                 {
                     $TemST=$_SESSION['advInfo']->TemplateST;
                     foreach ($TemST->arrayST as $StartIndex)
                     {
                         if($pcount>=$StartIndex && $pcount<=$StartIndex+$TemST->infoTimeLength - 1)
                         {$col_ellipse=$col_red;break;}
                     }
                 }
             }
             
            imagefilledrectangle ($im, $l * $pointW + 2 + $marginLeft,$top+2, ($l +1) * $pointW - 2  + $marginLeft, $top + $pointH - 2, $col_ellipse);
            $pcount++;
            if($pcount>=$templateCycle){break;}
        }
        if($pcount>=$templateCycle){break;}
    }
    ob_clean();
    header('Content-Type: image/png');
    imagepng($im);
    imagepng($im,"../Content/Img/ImgTem.png");
    imagedestroy($im);
}

DrawST();
?>