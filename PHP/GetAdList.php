<?php
  require('../PHP/PDOConnect.php');

  $adminName = $_POST['adminName'];
  $json_arr = array();
  
  $sqlGetAdminIDList="select GrpID from t_admingroup where  adminID in (select ID from t_admin where Name='".$adminName."')";
  $adlResult= $mypdo->getAll($sqlGetAdminIDList);
  foreach ($adlResult as $row)
  {
      $grpID= $row['GrpID'];
      $sqlGetGN="select GrpName from t_grouptaxi where GrpID=".$grpID;
      $GrpName = $mypdo->scalar($sqlGetGN, 'GrpName');      
      
      $now=date('y-m-d h:i:s',time());
      
      $result = $mypdo->getAll("select ADID,infoName,advType,LifeAct,LifeDie,Infostatus from t_advertisement where DelIndex=0 and LifeDie>='".$now."' and GrpID=".$grpID);
      $Item_arr = array();
      foreach ($result as $rowInfo) {
      
         $ts = date("Y-m-d",strtotime($rowInfo['LifeAct']));
          $te = date("Y-m-d",strtotime($rowInfo['LifeDie']));
          $Item= array("ADID"=>$rowInfo['ADID'] ,"infoName"=>$rowInfo['infoName'],"advType"=>$rowInfo['advType'],"LifeAct"=>$ts,"LifeDie"=>$te, "Infostatus"=>$rowInfo['Infostatus']);
          array_push($Item_arr,$Item);
      }
      $grpItem_arr = array("GrpID"=>$grpID,"GrpName"=>$GrpName,"itemArr"=>$Item_arr);
      array_push($json_arr,$grpItem_arr);
  }
    
    $json_obj = json_encode($json_arr);
    echo $json_obj;
            
   $mypdo->__destruct();
?>