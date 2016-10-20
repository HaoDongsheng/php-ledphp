<?php
require('../PHP/PDOConnect.php');
require('../Class/infoItem.php');

$ADID = $_POST['ADID'];
$advInfo=new advinfo();
$getAdvSql="select * from t_advertisement where ADID=".$ADID;
$getAdvRs= $mypdo->getAll($getAdvSql);
foreach ($getAdvRs as $row)
{
    $advInfo->ADID=$row['ADID'];
    $advInfo->infoName=$row['infoName'];
    $advInfo->lifeAct=$row['LifeAct'];
    $advInfo->lifeDie=$row['LifeDie'];
    $advInfo->advType=$row['advType'];
    $advInfo->GrpID=$row['GrpID'];
    $advInfo-> infoTimeLength=$row['playingTime'];    
    $advInfo->TemplateID=$row['TemplateID'];
    $advInfo->index=0;
}

if($advInfo->TemplateID==0)
{
    $getTemIDSql="select TemplateID from t_template";
    $advInfo->TemplateID= $mypdo->scalar($getTemIDSql, "TemplateID");    
}

$getTemSql="select * from t_template where TemplateID=".$advInfo->TemplateID;
$getTemRs= $mypdo->getAll($getTemSql);
foreach ($getTemRs as $row)
{
    $Template=new Template();
    $Template->TemplateID=$row['TemplateID'];
    $Template->TemplateName=$row['TemplateName'];
    $Template->TemplateCycle=$row['TemplateCycle'];
    $getTemSTSql="select * from t_templatetime where TemplateID=".$advInfo->TemplateID;
    $getTemSTRs= $mypdo->getAll($getTemSTSql);
    foreach ($getTemSTRs as $TemRow)
    {
        $TimePartCls=new TimePartCls();
        $TimePartCls->tStart=$TemRow['TemplateStart'];
        $TimePartCls->tEnd=$TemRow['TemplateEnd'];
        array_push($Template->TemplateList,$TimePartCls);        
    }
    array_push($advInfo->arrayTemplate,$Template);
}

$json_arr = array();

$sql="select * from t_item where ADID=".$ADID." and DelIndex=0 order by pageName asc,ItemName asc";
$Result= $mypdo->getAll($sql);

foreach ($Result as $row)
{
    $infoItem=new infoItem();
    
    $infoItem->itemID=$row['ItemID'];
    $infoItem->pageName=$row['pageName'];
    $infoItem->ItemName=$row['ItemName'];
    $infoItem->dataType=$row['dataType'];
    $infoItem->Color=$row['Color'];
    
    $infoItem->fontNo=$row['fontNo'];
    $infoItem->fontName=$row['fontName'];
    $infoItem->fontSize=$row['fontSize'];
    $infoItem->fontBold=$row['fontBold'];
    $infoItem->fontItalic=$row['fontItalic'];
    
    $infoItem->fontUnderline=$row['fontUnderline'];
    $infoItem->x1=$row['x1'];
    $infoItem->y1=$row['y1'];
    $infoItem->x2=$row['x2'];
    $infoItem->y2=$row['y2'];
    
    $infoItem->stayTime=$row['stayTime'];
    $infoItem->displayType=$row['displayType'];
    $infoItem->speedID=$row['speedID'];
    $infoItem->lineSpace=$row['lineSpace'];
    $infoItem->rollSpace=$row['rollSpace'];
    
    $infoItem->lastStopMode=$row['lastStopMode'];
    $infoItem->cycCount=$row['cycCount'];
    $infoItem->texts=$row['texts'];
    $infoItem->pic=$row['pic'];
    $infoItem->timeLong=$row['timeLong'];
    
    $infoItem->DelIndex=$row['DelIndex'];
    $infoItem->isForceTime=$row['isForceTime'];
    $infoItem->PTID=$row['PTID'];
    
    array_push($json_arr,$infoItem);
}

$advInfo->arrayitem=$json_arr;

$sql="select * from t_temst where ADID=".$ADID." and DelIndex=0 order by RelativeTime asc";
$Result= $mypdo->getAll($sql);

$TemplateST=new TemplateST();
$TemplateST->ADID=$ADID;
$TemplateST->infoTimeLength=$advInfo-> infoTimeLength;
foreach ($Result as $row)
{
    array_push($TemplateST->arrayST, intval($row["RelativeTime"])); 
}
$advInfo->TemplateST=$TemplateST;

if(!isset($_SESSION)){
    session_start();
}
$_SESSION['advInfo']=$advInfo;

$json_obj = json_encode($json_arr);
echo $json_obj;

$mypdo->__destruct();
?>