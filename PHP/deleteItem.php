<?php
require('../Class/infoItem.php');

$ADID = $_POST['ADID'];
$pageName = $_POST['pageName'];
$itemName = $_POST['itemName'];

function deleteItem() {
    global $pageName,$itemName;
    session_start();
    if(isset($_SESSION['advInfo']))
    {
        $advInfo = $_SESSION['advInfo'];
        foreach ($advInfo->arrayitem as $item)
        {
            if($item->pageName==$pageName && $item->ItemName==$itemName)
            {
                $key = array_search($item, $advInfo->arrayitem);
                if ($key !== false)
                {array_splice($advInfo->arrayitem, $key, 1);}
                break;
            }
        }
        $advInfo->itemIndex=1;
        $_SESSION['advInfo']=$advInfo;
        return true;
    }
    else {return false;}
}
if(deleteItem())
{
    $json_arr = array("msg"=>'OK');
    $json_obj = json_encode($json_arr);
    echo $json_obj;
}
else 
{$json_arr = array("msg"=>'删除失败');
    $json_obj = json_encode($json_arr);
    echo $json_obj;}
?>