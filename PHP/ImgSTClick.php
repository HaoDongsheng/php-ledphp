<?php
require('../Class/infoItem.php');

$left = $_GET['left'];
$top = $_GET['top'];

function DrawSTClick($left,$top) {
    $imgPng = imageCreateFromPng("../Content/Img/ImgTem.png");
    $col_ellipse = imagecolorallocate($imgPng, 255, 0, 0);
    $col_white = imagecolorallocate($imgPng, 255, 255, 255);
    
    if(!isset($_SESSION)){
        session_start();
    }
    $templateCycle=0;
    if(isset($_SESSION['advInfo']))
    {
        $arrayTemplate=$_SESSION['advInfo']->arrayTemplate;
        if($arrayTemplate!=null && count($arrayTemplate)>0)
        { $templateCycle=intval($arrayTemplate[0]->TemplateCycle);}
    }
    
    $srceenW = imagesx($imgPng);
    $srceenH = imagesy($imgPng);
    $marginLeft=20;$marginTop=20;
    $pointW=20;
    $pointH=10;
    $colCount=intval(($srceenW - 2*$marginLeft)/$pointW);
    $rowCount=ceil($templateCycle/$colCount);
    
    $timeLength=10;
    if(($top>$marginTop && $top<$srceenH -$marginTop) && ($left>$marginLeft && $left<$srceenW -$marginLeft))
    {
        $startindex = intval(($top - $marginTop)/$pointH) * $colCount + intval(($left- $marginLeft)/$pointW);
        $isE = isEnable($startindex,$timeLength);

        $ExistIndex =isExist($startindex,$timeLength,$_SESSION['advInfo']->TemplateST);
        if($isE==true && $ExistIndex==-2)
        {
            if($startindex+$timeLength<=$templateCycle)
            {
                if($_SESSION['advInfo']->TemplateST==null)
                {
                    $_SESSION['advInfo']->TemplateST=new TemplateST();
                    $_SESSION['advInfo']->TemplateST->ADID=$_SESSION['advInfo']->ADID;
                    $_SESSION['advInfo']->TemplateST->infoTimeLength=$timeLength;
                    array_push( $_SESSION['advInfo']->TemplateST->arrayST, $startindex);
                    $_SESSION['advInfo']->STIndex=1;
                }
                else 
                {
                    array_push( $_SESSION['advInfo']->TemplateST->arrayST, $startindex);
                    $_SESSION['advInfo']->STIndex=1;
                }         
                for($i=$startindex;$i<$startindex+$timeLength;$i++)
                {            
                    $rowY=intval($i/$colCount) * $pointH + $marginTop;
                    $rowX=intval($i%$colCount) * $pointW + $marginLeft;
                    imagefilledrectangle ($imgPng, $rowX + 2,$rowY+2, $rowX + $pointW - 2, $rowY + $pointH - 2, $col_ellipse);
                }
            }
        }
        else if($ExistIndex>=0){       
            $startValue=$ExistIndex;
            for($i=$startValue;$i<$startValue+$timeLength;$i++)
            {
                $rowY=intval($i/$colCount) * $pointH + $marginTop;
                $rowX=intval($i%$colCount) * $pointW + $marginLeft;
                imagefilledrectangle ($imgPng, $rowX + 2,$rowY+2, $rowX + $pointW - 2, $rowY + $pointH - 2, $col_white);
            }
         
            $key = array_search($startValue,$_SESSION['advInfo']->TemplateST->arrayST);
            if ($key !== false)
            {
                array_splice($_SESSION['advInfo']->TemplateST->arrayST, $key, 1);
                $_SESSION['advInfo']->STIndex=1;
            }                                             
        }
    }
    ob_clean();
    header('Content-Type: image/png');
    imagepng($imgPng);
    imagepng($imgPng,"../Content/Img/ImgTem.png");
    imagedestroy($imgPng);
}

function isEnable($startIndex,$timeLength)
{
    try {
        $endIndex=$startIndex+$timeLength - 1;
        if(!isset($_SESSION)){
            session_start();
        }
        
        if(isset($_SESSION['TemST']))
        {
            $arrayTemST=$_SESSION['TemST'];
            $isEnable=true;
            foreach($arrayTemST as $TemST)
            {
                $ADID= $TemST->ADID;
                $infoTimeLength= $TemST->infoTimeLength;
                $arrayST= $TemST->arrayST;
                foreach ($arrayST as $startValue)
                {
                    $endValue=$startValue+$infoTimeLength - 1;
                    if(($startIndex>=$startValue && $startIndex<=$endValue) || ($endIndex>=$startValue && $endIndex<=$endValue) || ($startIndex<=$startValue && $endIndex>=$endValue))
                    {
                        $isEnable=false;break;
                    }
                }
                if($isEnable==false){break;}
            }
            return $isEnable;
        }
        else {return true;}        
    }
    catch (Exception $ex){return false;}
}

function isExist($startIndex,$timeLength,$TemplateST){
    try {      
        if($TemplateST==null){return -2;}
        $endIndex=$startIndex+$timeLength - 1;
        $infoTimeLength= $TemplateST->infoTimeLength;
        $arrayST= $TemplateST->arrayST;
        $index=-2;
        if($arrayST !=null && count($arrayST)>0)
        {
            foreach ($arrayST as $startValue)
            {
                $endValue=$startValue+$infoTimeLength - 1;
                if(($startIndex>=$startValue && $startIndex<=$endValue))
                {
                    $index = $startValue;break;
                }
                else if(($endIndex>=$startValue && $endIndex<=$endValue) || ($startIndex<=$startValue && $endIndex>=$endValue))
                {$index = -1;break;}            
            }
        }
        return $index;
    }
    catch (Exception $ex){return -1;}
}

DrawSTClick($left,$top);
?>