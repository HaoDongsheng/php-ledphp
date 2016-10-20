<?php
require('../PHP/PDOConnect.php');

function Login($adminName,$Pwd)
{        
        try {           
            global $mypdo;
            $result = $mypdo ->getOne("SELECT count(*) as sum from t_admin where Name='".$adminName."' and Password='".$Pwd."'");
            if($result["sum"]>=1)
            {
                $json_arr = array("msg"=>"OK");
                $json_obj = json_encode($json_arr);
                echo $json_obj;                
            }
            else 
            {                
                $json_arr = array("msg"=>"Error");
                $json_obj = json_encode($json_arr);
                echo $json_obj;
            }            
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
}

$username = $_POST['adminName'];
$pwd = $_POST['pwd'];

Login($username,$pwd);

$mypdo->__destruct();
?>