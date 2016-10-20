<?php
  require('../PHP/PDOConnect.php');

  $adminName = $_POST['adminName'];
  $pageNo = $_POST['pageNo'];
  $pageSize = $_POST['pageSize'];  
    
  $now=date('y-m-d h:i:s',time());
  $sql ="select count(*) as pageSum from t_advertisement where (DelIndex=1 or LifeDie<'".$now."') and GrpID in (select GrpID from t_admingroup where  adminID in (select ID from t_admin where Name='".$adminName."'))";
  $pageSum = ceil(intval($mypdo->scalar($sql, 'pageSum'))/$pageSize);
        
  $result = $mypdo->getAll("select ADID,infoName,advType,LifeAct,LifeDie,Infostatus from t_advertisement where (DelIndex=1 or LifeDie<'".$now."') and GrpID in (select GrpID from t_admingroup where  adminID in (select ID from t_admin where Name='".$adminName."')) order by lifeDie asc,infoName asc limit ".$pageSize*($pageNo - 1).", ".$pageSize);
  $Item_arr = array();
  foreach ($result as $rowInfo) {  
      $ts = date("Y-m-d",strtotime($rowInfo['LifeAct']));
      $te = date("Y-m-d",strtotime($rowInfo['LifeDie']));
      $Item= array("ADID"=>$rowInfo['ADID'] ,"infoName"=>$rowInfo['infoName'],"advType"=>$rowInfo['advType'],"LifeAct"=>$ts,"LifeDie"=>$te, "Infostatus"=>$rowInfo['Infostatus']);
      array_push($Item_arr,$Item);
  }
  
  $grpItem_arr = array("pageSum"=>$pageSum,"itemArr"=>$Item_arr);
    
$json_obj = json_encode($grpItem_arr);
echo $json_obj;
        
$mypdo->__destruct();
?>