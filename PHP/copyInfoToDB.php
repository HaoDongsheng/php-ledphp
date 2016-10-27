<?php
require('../PHP/PDOConnect.php');
$ADID = $_POST['ADID'];

function GetADID()
{
    $minID=1;
    $maxID=65535;
    $ADID=$minID;
    $getMaxADID="select max(ADID) as maxAID from t_advertisement";
    global $mypdo;
    $maxADID=$mypdo->scalar($getMaxADID, "maxAID");
    if($maxADID!=null)
    {
        $ADID=$maxADID+1;
        if($ADID<$minID || $ADID>$maxID)
        {
            for($i=$minID;$i<$maxID;$i++)
            {
                $getSADID="select ADID from t_advertisement where ADID=".$i;
                $objAid=$mypdo->scalar($getSADID, "ADID");
                if($objAid==null)
                {$ADID=$i;break;}
            }
        }
    }
    else
    {
        $ADID=$minID;
    }
    return $ADID;
}

function getCopyInfoName($infoName) {
    global $mypdo;
    $index=1;
    while (true)
    {
        $newInfoName=$infoName."_".$index;
        $SqlS="select infoName from t_advertisement where infoName='".$newInfoName."'";
        $objS = $mypdo->scalar($SqlS, 'infoName');
        if($objS==null)
        {return $newInfoName;}
        $index++;
    }
}

function copyInfo() {
    try {
        global $mypdo,$ADID;
        
        $newADID=GetADID();
        $sql="select * from t_advertisement where ADID=".$ADID;
        $getAdvRs= $mypdo->getAll($sql);
        foreach ($getAdvRs as $row)
        {  
            $infoName=$row['infoName'];
            $lifeAct=$row['LifeAct'];
            $lifeDie=$row['LifeDie'];
            $advType=$row['advType'];
            $GrpID=$row['GrpID'];
            $infoTimeLength=$row['playingTime'];
            $TemplateID=$row['TemplateID'];
            $newInfoName=getCopyInfoName($infoName);
            $sqlInsertAdv="insert into t_advertisement (ADID,infoName,LifeAct,LifeDie,Infostatus,DelIndex,advType,TemplateID,GrpID,playingTime) ";
            $sqlInsertAdv=$sqlInsertAdv."values(".$newADID.",'".$newInfoName."','".$lifeAct."','".$lifeDie."',0,0,".$advType.",".$TemplateID.",".$GrpID.",".$infoTimeLength.")";
            $mypdo->execute($sqlInsertAdv);
            break;
        }
        
        $sqlGetItem="select * from t_item where DelIndex=0 and ADID=".$ADID;
        $getItemRs= $mypdo->getAll($sqlGetItem);
        foreach ($getItemRs as $row)
        {
            $itemID=$row['ItemID'];
            $pageName=$row['pageName'];
            $ItemName=$row['ItemName'];
            $dataType=$row['dataType'];
            $Color=$row['Color'];
            
            $fontNo=$row['fontNo'];
            $fontName=$row['fontName'];
            $fontSize=$row['fontSize'];
            $fontBold=$row['fontBold'];
            $fontItalic=$row['fontItalic'];
            
            $fontUnderline=$row['fontUnderline'];
            $x1=$row['x1'];
            $y1=$row['y1'];
            $x2=$row['x2'];
            $y2=$row['y2'];
            
            $stayTime=$row['stayTime'];
            $displayType=$row['displayType'];
            $speedID=$row['speedID'];
            $lineSpace=$row['lineSpace'];
            $rollSpace=$row['rollSpace'];
            
            $lastStopMode=$row['lastStopMode'];
            $cycCount=$row['cycCount'];
            $texts=$row['texts'];
            $pic=$row['pic'];
            $timeLong=$row['timeLong'];
            
            $DelIndex=$row['DelIndex'];
            $isForceTime=$row['isForceTime'];
            $PTID=$row['PTID'];
            
            $sql="insert into t_item(ADID,pageName,ItemName,dataType,Color,fontNo,x1,y1,x2,y2,stayTime,displayType,speedID,rollSpace,lastStopMode,cycCount,texts,timeLong,DelIndex,isForceTime,PTID)
                    values(".$newADID.",".$pageName.",".$ItemName.",".$dataType.",".$Color.",".$fontNo.",".$x1.",".$y1.",".$x2.",".$y2.",".$stayTime.",".$displayType.",".$speedID.",".$rollSpace.",".$lastStopMode.",".$cycCount.",'".$texts."',".$timeLong.",".$DelIndex.",".$isForceTime.",".$PTID.")";
            $mypdo->execute($sql);
        }
        $strAdvType='普通广告';
        switch ($advType)
        {
            case 1:{$strAdvType='转场信息';};break;
            case 2:{$strAdvType='普通广告';};break;
            case 3:{$strAdvType='通知信息';};break;
        }
    
        $ts = date("Y-m-d",strtotime($lifeAct));
        $te = date("Y-m-d",strtotime($lifeDie));
        
        $json_arr = array("msg"=>'OK',"ADID"=>$newADID,"infoName"=>$newInfoName,"dtStart"=>$ts,"dtEnd"=>$te,"advType"=>$strAdvType,"GrpID"=>$GrpID,);
        $json_obj = json_encode($json_arr);
        echo $json_obj;
    }
    catch (Exception $ex)
    {$json_arr = array("msg"=>'复制失败');
    $json_obj = json_encode($json_arr);
    echo $json_obj;}    
}

copyInfo();
?>