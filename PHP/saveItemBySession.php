<?php
require('../Class/infoItem.php');

$ADID = $_POST['ADID'];
$pageName = $_POST['pageName'];
$itemName = $_POST['itemName'];
$x1 = $_POST['x1'];
$x2 = $_POST['x2'];
$y1 = $_POST['y1'];
$y2 = $_POST['y2'];
$cycCount = $_POST['cycCount'];
$displayType = $_POST['displayType'];
$speedID = $_POST['speedID'];
$lastStopMode = $_POST['lastStopMode'];
$stayTime = $_POST['stayTime'];
$rollSpace = $_POST['rollSpace'];
$texts = $_POST['texts'];

function GetInfoItem()
{
    try {
        $infoitem=null;
        global $pageName,$itemName,$x1,$x2,$y1,$y2,$cycCount,$displayType,$speedID,$lastStopMode,$stayTime,$rollSpace,$texts;
        session_start();
        if(isset($_SESSION['advInfo']))
        {
            $itemList=$_SESSION['advInfo']->arrayitem;
            foreach($itemList as $item)
            {
                if($item ->pageName==$pageName && $item->ItemName==$itemName)
                {
                    $index=0;$isRefresh=0;
                    $infoitem=$item;
                    if($infoitem->x1 != $x1)
                    {$infoitem->x1 = $x1;$index=1;$isRefresh=1;}
                    if($infoitem->y1 != $y1)
                    {$infoitem->y1 = $y1;$index=1;$isRefresh=1;}
                    if($infoitem->x2 != $x2)
                    {$infoitem->x2 = $x2;$index=1;$isRefresh=1;}
                    if($infoitem->y2 != $y2)
                    {$infoitem->y2 = $y2;$index=1;$isRefresh=1;}
                    if($infoitem->stayTime != $stayTime)
                    {$infoitem->stayTime = $stayTime;$index=1;}
                    if($infoitem->displayType != $displayType)
                    {$infoitem->displayType = $displayType;$index=1;}
                    if($infoitem->speedID != $speedID)
                    {$infoitem->speedID = $speedID;$index=1;}
                    if($infoitem->rollSpace != $rollSpace)
                    {$infoitem->rollSpace = $rollSpace;$index=1;}
                    if($infoitem->lastStopMode != $lastStopMode)
                    {$infoitem->lastStopMode = $lastStopMode;$index=1;}
                    if($infoitem->cycCount != $cycCount)
                    {$infoitem->cycCount = $cycCount;$index=1;}
                    if($infoitem->texts != $texts)
                    {$infoitem->texts = $texts;$index=1;$isRefresh=1;}
                    if($infoitem->Index!=2)
                    {$infoitem->Index=$index;}
                    $_SESSION['advInfo']->itemIndex=$index;
                    break;
                }
            }
            $_SESSION['advInfo']->itemIndex=1;
            $_SESSION['advInfo']->arrayitem=$itemList;
            $json_arr = array("msg"=>'OK',"isRefresh"=>$isRefresh);
            $json_obj = json_encode($json_arr);
            echo $json_obj;
        }
        else
        { 
            $json_arr = array("msg"=>'Session不存在!');
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

GetInfoItem();
?>