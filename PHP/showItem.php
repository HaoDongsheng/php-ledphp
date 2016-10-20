<?php
require('../Class/infoItem.php');

$ADID = $_POST['ADID'];
$pageName = $_POST['pageName'];
$itemName = $_POST['itemName'];

function GetInfoItem()
{
    $infoitem=null;
    global $pageName,$itemName;
  if(!isset($_SESSION)){
        session_start();
    }
    if(isset($_SESSION['advInfo']))
    {
        $itemList=$_SESSION['advInfo']->arrayitem;
        foreach($itemList as $item)
        {
            if($item ->pageName==$pageName && $item->ItemName==$itemName)
            {
                $infoitem=$item;
                break;
            }
        }
        return $infoitem;
    }
    else
    {return null;}
}

function GetDatabyInfoItem()
{
    try {
        $infoitem=GetInfoItem();
        if($infoitem!=null)
        {
            $x1 = $infoitem->x1;
            $y1 = $infoitem->y1;
            $x2 = $infoitem->x2;
            $y2 = $infoitem->y2;
            $stayTime = $infoitem->stayTime;
            $displayType = $infoitem->displayType;
            $speedID = $infoitem->speedID;
            $rollSpace = $infoitem->rollSpace;
            $lastStopMode = $infoitem->lastStopMode;
            $cycCount = $infoitem->cycCount;
            $texts = $infoitem->texts;
            
            $json_arr = array("msg"=>"OK","x1"=>$x1,"y1"=>$y1,"x2"=>$x2,"y2"=>$y2,"stayTime"=>$stayTime,"displayType"=>$displayType,"speedID"=>$speedID,"rollSpace"=>$rollSpace,"lastStopMode"=>$lastStopMode,"cycCount"=>$cycCount,"texts"=>$texts);
            $json_obj = json_encode($json_arr);
            echo $json_obj;
        }
        else {echo null;}
    }
    catch (Exception $ex){
        $json_arr = array("msg"=>$ex->getMessage());
        $json_obj = json_encode($json_arr);
        echo $json_obj;
    }
}

GetDatabyInfoItem();
?>