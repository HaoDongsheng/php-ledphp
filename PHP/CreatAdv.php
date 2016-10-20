<?php
require('../PHP/PDOConnect.php');

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

function CreatAdv()
{
    try {        
        global  $infoName ,$advType ,$dtStart , $dtEnd,$GrpID;
        
    $advTypeID=2;
    switch ($advType)
    {
        case "转场信息":$advTypeID=1;break;
        case "普通广告":$advTypeID=2;break;
        case "通知信息":$advTypeID=3;break;
    }
    
    $ADID=GetADID();
    
    $SqlS="select infoName from t_advertisement where infoName='".$infoName."'";
    
    $sql="insert into t_advertisement (ADID,infoName,LifeAct,LifeDie,Infostatus,DelIndex,advType,TemplateID,GrpID,playingTime) values(".$ADID.",'".$infoName."','".$dtStart."','".$dtEnd."',0,0,".$advTypeID.",0,$GrpID,2.57)";
    global $mypdo;
    
    $objS = $mypdo->scalar($SqlS, 'infoName');
    if($objS==null)
    {
        $mypdo->execute($sql);
        
        $sql="insert into t_item(ADID,pageName,ItemName,dataType,Color,fontNo,x1,y1,x2,y2,stayTime,displayType,speedID,rollSpace,lastStopMode,cycCount,texts,timeLong,DelIndex,isForceTime,PTID)
                    values(".$ADID.",1,1,0,0,0,0,0,127,15,0,1,5,0,0,1,'<p>新增条目长按修改</p>',2.57,0,0,0)";
        $mypdo->execute($sql);
        $json_arr = array("msg"=>"OK","ADID"=>$ADID);
        $json_obj = json_encode($json_arr);
        echo $json_obj;
    }
    else 
    {
        $json_arr = array("msg"=>"广告名称已存在!");
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

$infoName = $_POST['infoName'];
$advType = $_POST['advType'];
$dtStart = $_POST['dtStart'];
$dtEnd = $_POST['dtEnd'];
$GrpID = $_POST['GrpID'];

CreatAdv();
?>