<?php
class CalculateTimeCls
{
    
    public function stringToByteArray($str,$charset) {
    
        $str = iconv($charset,'UTF-16',$str);
        preg_match_all('/(.)/s',$str,$bytes);  //注：本文的盗版已经有了。不过，提示一下读者，这里的正则改了。
        $bytes=array_map('ord',$bytes[1]) ;
        return $bytes;
    
    }
    
    public function byteArrayToString($bytes,$charset) {
    
        $bytes=array_map('chr',$bytes);
        $str=implode('',$bytes);
        $str = iconv('UTF-16',$charset,$str);
        return $str;
    
    }
    
    public  function  Longtime($StayTimes,$Speed,$Cycle,$LastStop,$tStr,$displayModeID,$rollSpace,$lineSpace,$isForceTime,$left,$top,$typeid)
    {
        if ($Cycle == 0) { $Cycle = 1; }

        $timeL = 0;
        if ($isForceTime != 0) { $timeL = doubleval(number_format($StayTimes,2)); return timeL; }
        //$X2, $X1,$Space, $LeftLastSpace;

        $X1 = $left / 8; 
        $X2 = $top/ 8 - 1;

        $TL=0;
        $SL = 0;
        $t1=0;
        $t2=0;
        $SN=0;//屏数
        $SL = 0;//字符串总长度，单位字节
        
        if ($displayModeID == 1)
        {
        //左滚时，过滤回车，计算文本字节数
        $tStr = str_replace("\r", " ",$tStr);
        $tStr = str_replace("\n", " ",$tStr);
        $tStr = str_replace("\t", " ",$tStr);
        $tt = stringToByteArray($tStr,'utf-8');
        $SL = count($tt); 

        #region 左滚动
        $Space = intval($rollSpace);
        if ($SL % ($X2 - $X1 + 1) == 0)
        { $SN = $SL / ($X2 - $X1 + 1); }
        else
        { $SN = $SL / ($X2 - $X1 + 1) + 1; }
        if ($SL % ($X2 - $X1 + 1) == 0)
        { $LeftLastSpace = $X2 - $X1 + 1; }
        else
        { $LeftLastSpace = $SL % ($X2 - $X1 + 1); }
        if ($LastStop && $StayTimes == 0) $LastStop = false;

        #region 每屏停留
        if ($LastStop == false)
        {
            if ($StayTimes == 0)
            { $TL = $SL * $Cycle + $Space * ($Cycle - 1) + ($X2 - $X1 + 1); }
            else
            { $TL = $SN * ($X2 - $X1 + 1) * $Cycle + $LeftLastSpace; }
            
            $t1 = doubleval($TL) / 2;
            switch ($Speed)
            {
            case 1: $t1 = doubleval($t1 * 1000); break;
            case 2: $t1 = doubleval($t1 / 2 * 1000); break;
            case 3: $t1 = doubleval($t1 / 25 * 10 * 1000); break;
            case 4: $t1 = doubleval($t1 / 3 * 1000); break;
            case 5: $t1 = doubleval($t1 / 35 * 10 * 1000); break;
            case 6: $t1 = doubleval($t1 / 4 * 1000); break;
            case 7: $t1 = doubleval($t1 / 45 * 10 * 1000); break;
            case 8: $t1 = doubleval($t1 / 5 * 1000); break;
            case 9: $t1 = doubleval($t1 / 6 * 1000); break;
            case 10: $t1 = doubleval($t1 / 7 * 1000); break;
            }
            $t2 = doubleval($StayTimes) * $SN * $Cycle;
            }
            #endregion

            #region 最终停留
            else
            {
                    $TL = $SL * $Cycle + $Space * ($Cycle - 1);//speed改成space
                    $t1 = doubleval($TL) / 2;
                    switch ($Speed)
                    {
                    case 1: $t1 = doubleval($t1 * 1000); break;
                    case 2: $t1 = doubleval($t1 / 2 * 1000); break;
                    case 3: $t1 = doubleval($t1 / 25 * 10 * 1000); break;
                    case 4: $t1 = doubleval($t1 / 3 * 1000); break;
                    case 5: $t1 = doubleval($t1 / 35 * 10 * 1000); break;
                    case 6: $t1 = doubleval($t1 / 4 * 1000); break;
                    case 7: $t1 = doubleval($t1 / 45 * 10 * 1000); break;
                    case 8: $t1 = doubleval($t1 / 5 * 1000); break;
                    case 9: $t1 = doubleval($t1 / 6 * 1000); break;
                    case 10: $t1 = doubleval($t1 / 7 * 1000); break;
                    }
                    $t2 = doubleval($StayTimes);
                }
                    #endregion

                    $timeL = $t1 / 1000 + t2;
                    #endregion
                }
                else//上滚及其他显示方式
                {
                if ($typeid == 0)
                    {
                    #region 屏数

                        $L = 0;
                        $c;
                        $SN = 0;
                        for ($i = 0; $i < count($tStr); $i++)
                        {
                            $c = $tStr[$i];
        
                            if (($c == '\r') || ($c == '\n'))
                            {
                                if ($i != count($tStr) - 1)
                                {
                                    $c =  $tStr[$i + 1];
                                    if (($c == '\r') || ($c == '\n'))
                                    {
                                        $i =$i + 1;
                                    }
                                }
                                if ($L != 0)
                                {
                                    $SN = $SN + 1;
                                    $L = 0;
                                }
                            }
                            else
                            {
                                if (ord($c) >127)
                                { $L = $L + 2; }
                                else
                                { $L = $L + 1; }
                                if ($L > $X2 - $X1 + 1)
                                {
                                    $SN = $SN + 1;
                                    $L = 0;
                                    $i = $i - 1;
                                }
                                else if ($L == $X2 - $X1 + 1)
                                {
                                    $SN = $SN + 1;
                                    $L = 0;
                                }
                                else if ($L != $X2 - $X1 + 1 && $i == count($tStr) - 1)
                                { $SN = $SN + 1; }
                            }
                        }

                #endregion

                $timeL = $StayTimes * $Cycle * $SN;
                }
                else
                {
                    $timeL = $StayTimes * $Cycle;
                }
            }
            
            $timeL = doubleval(number_format($timeL,2));
            return timeL;
        }

}

?>