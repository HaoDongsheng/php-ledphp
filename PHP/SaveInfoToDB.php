<?php
require('../Class/infoItem.php');
require('../PHP/PDOConnect.php');

function SaveItemToDB()
{
    try {
        
        global $advInfo;
        global $mypdo;
       
        $itemIndex=$advInfo->itemIndex;
        
        if($itemIndex==1)
        {            
            $ADID=$advInfo->ADID;
            $sql="delete from t_item where ADID=".$ADID;
            $mypdo->execute($sql);
            $arrayitem=$advInfo->arrayitem;
            for($i=0;$i<count($arrayitem);$i++)
            //foreach ($arrayitem as $infoItem)
            {                                
                $infoItem=$arrayitem[$i];
                $itemID =$infoItem->itemID;
                $pageName =$infoItem->pageName;
                $ItemName =$infoItem->ItemName;
                $dataType = $infoItem->dataType;
                $Color =$infoItem->Color;
                
                $fontNo =$infoItem->fontNo;
                //$fontName =$infoItem->fontName;
                //$fontSize =$infoItem->fontSize;
                //$fontBold =$infoItem->fontBold;
                //$fontItalic =$infoItem->fontItalic;
                
                //$fontUnderline =$infoItem->fontUnderline;
                $x1=$infoItem->x1;
                $y1 =$infoItem->y1;
                $x2 =$infoItem->x2;
                $y2 =$infoItem->y2;
                
                $stayTime =$infoItem->stayTime;
                $displayType =$infoItem->displayType;
                $speedID =$infoItem->speedID;
                //$lineSpace =$infoItem->lineSpace;
                $rollSpace =$infoItem->rollSpace;
                
                $lastStopMode =$infoItem->lastStopMode;
                $cycCount =$infoItem->cycCount;
                $texts =$infoItem->texts;
                //$pic =$infoItem->pic;
                $timeLong =$infoItem->timeLong;
                
                $DelIndex =$infoItem->Index;
                $isForceTime =$infoItem->isForceTime;
                $PTID =$infoItem->PTID;
                
                $sql="insert into t_item(ADID,pageName,ItemName,dataType,Color,fontNo,x1,y1,x2,y2,stayTime,displayType,speedID,rollSpace,lastStopMode,cycCount,texts,timeLong,DelIndex,isForceTime,PTID) 
                    value(".$ADID.",".$pageName.",".$ItemName.",".$dataType.",".$Color.",".$fontNo.",".$x1.",".$y1.",".$x2.",".$y2.",".$stayTime.",".$displayType.",".$speedID.",".$rollSpace.",".$lastStopMode.",".$cycCount.",'".$texts."',".$timeLong.",0,".$isForceTime.",".$PTID.")";
               $mypdo->execute($sql);
            }
                 
            $advInfo->itemIndex=0;
        }   
          
        return true;
    }
    catch(Exception $ex){return false;}
}

function SaveTemSTToDB() {
    try {
        global $advInfo;
        global $mypdo;
        $STIndex=$advInfo->STIndex;
        if($STIndex==1)
        {
            $ADID=$advInfo->ADID;
            $sql="delete from t_temst where ADID=".$ADID;
            $mypdo->execute($sql);
            $TemplateST=$advInfo->TemplateST;            
            foreach ($TemplateST->arrayST as $startIndex)
            {
                $sql="insert into t_temst(ADID,RelativeTime,lifeTime,GrpID,DelIndex) values(".$ADID.",".$startIndex.",".$advInfo->TemplateST->infoTimeLength.",".$advInfo->GrpID.",0)";
                $mypdo->execute($sql);
            }    
        }
        $advInfo->STIndex=0;
        return true;
    }
    catch (Exception $ex){return false;}
}

function SavetemplateToDB() {
try {
        global $advInfo;
        global $mypdo;
        $TemplateIndex=$advInfo->TemplateIndex;
        if($TemplateIndex==1)
        {
            $ADID=$advInfo->ADID;
            $sql="update t_advertisement set TemplateID=".$advInfo->TemplateID." where ADID=".$ADID;
            $mypdo->execute($sql);    
        }
        $advInfo->TemplateIndex=0;
        return true;
    }
    catch (Exception $ex){return false;}
}

$isSaveItem=true;

if(!isset($_SESSION)){
    session_start();
}
if(isset($_SESSION['advInfo']))
{
    $advInfo = $_SESSION['advInfo'];
}

$isSaveItem = SaveItemToDB();

$isSaveItem = SaveTemSTToDB();

$TemplateST=$advInfo->TemplateST;

$isSaveItem  = SavetemplateToDB();


if($isSaveItem)
{
$json_arr = array("msg"=>'OK',"count"=>count($TemplateST->arrayST));
$json_obj = json_encode($json_arr);
echo $json_obj;
}
else {$json_arr = array("msg"=>'保存失败');
$json_obj = json_encode($json_arr);
echo $json_obj;}

$mypdo->__destruct();
?>