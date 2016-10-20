<?php
class  advinfo
{
    var $ADID;
    var $infoName;
    var $lifeAct;
    var $lifeDie;
    var $advType;
    var $index=0;
    var $TemplateIndex=0;
    var $itemIndex=0;
    var $STIndex=0;
    var $GrpID=0;
    var $TemplateID=0;
    var $infoTimeLength=10;
    var $arrayitem=array();
    var $arrayTemplate=array();
    var $TemplateST;
    public function __construct() {}
}

class infoItem
{    
    var $itemID;
    var $pageName;
    var $ItemName;
    var $dataType;
    var $Color;
    
    var $fontNo;
    var $fontName;
    var $fontSize;
    var $fontBold;
    var $fontItalic;
    
    var $fontUnderline;
    var $x1;
    var $y1;
    var $x2;
    var $y2;
    
    var $stayTime;
    var $displayType;
    var $speedID;
    var $lineSpace;
    var $rollSpace;
    
    var $lastStopMode;
    var $cycCount;
    var $texts;
    var $pic;
    var $timeLong;
    
    var $Index;
    var $isForceTime;
    var $PTID;
    
    public function __construct() {}
}

class Template
{
    public function __construct() {}
    var $TemplateID;
    var $TemplateName;
    var $TemplateCycle=180;
    var $TemplateList=array();
}

class TimePartCls
{
    public function __construct() {}
    var $tStart;
    var $tEnd;
}

class TemplateST
{
    public function __construct() {}
    var $ADID;
    var $infoTimeLength;    
    var $arrayST=array();
}

?>