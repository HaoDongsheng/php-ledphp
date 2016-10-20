<?php
require('../Class/infoItem.php');

$TemSTValue = $_POST['TemSTValue'];

function DeleteTemST($value) {
try {    
    if(!isset($_SESSION)){
        session_start();
    }
    if(isset($_SESSION['advInfo']))
    {
        $advInfo=$_SESSION['advInfo'];
        
        foreach ($advInfo->TemplateST->arrayST as $ST)
        {
            if($ST==$value)
            {
                $key = array_search($ST,$advInfo->TemplateST->arrayST);
                if ($key !== false)
                {
                    array_splice($advInfo->TemplateST->arrayST, $key, 1);
                }
            }
        }
        $advInfo->STIndex=1;
        return true;
    }
    else 
    {return false;}
}
catch (Exception $ex){return false;}
}

if(DeleteTemST($TemSTValue))
{
    $json_arr = array("msg"=>"OK");
    $json_obj = json_encode($json_arr);
    echo $json_obj;
}
else { $json_arr = array("msg"=>"Error");
    $json_obj = json_encode($json_arr);
    echo $json_obj;}
?>