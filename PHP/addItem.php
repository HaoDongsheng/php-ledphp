<?php
require('../Class/infoItem.php');

function addItem() {
    session_start();
    $advInfo = $_SESSION['advInfo'];
    $pageNo=0;
    foreach ($advInfo->arrayitem as $item)
    {
        if($item->pageName>$pageNo)
        {$pageNo=$item->pageName;}
    }
    $pageNo+=1;
    $infoItem=new infoItem();
    
    $infoItem->itemID=0;
    $infoItem->pageName=$pageNo;
    $infoItem->ItemName=1;
    $infoItem->dataType=0;
    $infoItem->Color=0;
    
    $infoItem->fontNo=0;
    $infoItem->fontName='宋体';
    $infoItem->fontSize=12;
    $infoItem->fontBold=0;
    $infoItem->fontItalic=0;
    
    $infoItem->fontUnderline=0;
    $infoItem->x1=0;
    $infoItem->y1=0;
    $infoItem->x2=127;
    $infoItem->y2=15;
    
    $infoItem->stayTime=0;
    $infoItem->displayType=1;
    $infoItem->speedID=5;
    $infoItem->lineSpace=0;
    $infoItem->rollSpace=0;
    
    $infoItem->lastStopMode=0;
    $infoItem->cycCount=1;
    $infoItem->texts='<p>新增条目长按修改</p>';
    $infoItem->pic='';
    $infoItem->timeLong=5;
    
    $infoItem->DelIndex=0;
    $infoItem->isForceTime=0;
    $infoItem->PTID=0;
    array_push($advInfo->arrayitem,$infoItem);
    $advInfo->itemIndex=1;
    $_SESSION['advInfo']=$advInfo;
    
    $json_obj = json_encode($infoItem);
    echo $json_obj;
}

addItem();
?>