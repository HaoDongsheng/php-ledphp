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

    $result = $mypdo->getAll("select * from t_template where GrpID=".$grpID);
    $Item_arr = array();
    foreach ($result as $rowInfo) {

        $resultTP = $mypdo->getAll("select * from t_templatetime where TemplateID=".$rowInfo['TemplateID'] );
        $tp_arr = array();
          foreach ($resultTP as $tp) {
                           
              $ts = date("H:i",strtotime($tp['TemplateStart']));
              $te = date("H:i",strtotime($tp['TemplateEnd']));
              $itemTp=array("TemplateStart"=>$ts,"TemplateEnd"=>$te);
              //$itemTp=array("TemplateStart"=>$tp['TemplateStart'],"TemplateEnd"=>$tp['TemplateEnd']);
              array_push($tp_arr,$itemTp);
          }
        
        $Item= array("TemplateID"=>$rowInfo['TemplateID'] ,"TemplateName"=>$rowInfo['TemplateName'],"TemplateCycle"=>$rowInfo['TemplateCycle'],"TemTpList"=>$tp_arr);
        array_push($Item_arr,$Item);
    }
    $grpItem_arr = array("GrpID"=>$grpID,"GrpName"=>$GrpName,"itemArr"=>$Item_arr);
    array_push($json_arr,$grpItem_arr);
}

$json_obj = json_encode($json_arr);
echo $json_obj;

$mypdo->__destruct();
?>
