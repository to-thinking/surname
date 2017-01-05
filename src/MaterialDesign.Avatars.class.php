<?php

/*
 * Material-Design-Avatars
 * https://github.com/lincanbin/Material-Design-Avatars
 *
 * Copyright 2015 Canbin Lin (lincanbin@hotmail.com)
 * http://www.94cb.com/
 *
 * Licensed under the Apache License, Version 2.0:
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Create material deisgn avatars for users just like Gmail or Messager in Android.
 */



class MDAvatars
{
    public $Char;
    public $AvatarSize;
    public $Padding;
    public $Avatar;
    public $FontFile;
    public $IsNotLetter;
    public $LetterFont;
    public $AsianFont;
    public $EnableAsianChar;


    function __construct($Char, $AvatarSize = 256)
    {
        $this->Char            = strtoupper(mb_substr($Char, 0, 1, "UTF-8"));
        $this->AvatarSize      = $AvatarSize;
        $this->Padding         = 42 * ($this->AvatarSize / 256);
        $this->LetterFont      = dirname(__FILE__) . '/fonts/SourceCodePro-Light.ttf';
        $this->AsianFont       = dirname(__FILE__) . '/fonts/SourceHanSansCN-Normal.ttf';
        $this->EnableAsianChar = is_file($this->AsianFont);

        $CNChar = ord($this->Char);
        if (!$this->EnableAsianChar &&
            preg_match("/^[\x7f-\xff]/", $this->Char) &&
            !($CNChar >= ord("A") && $CNChar <= ord("z"))
        ) {
            //如果是中文，并且没有中文字体包，则按拼音首字母对其进行转换
            $CNByte = iconv("UTF-8", "gb2312", $this->Char);
            $Code   = ord($CNByte{0}) * 256 + ord($CNByte{1}) - 65536;//求其偏移量
            if ($Code >= -20319 and $Code <= -20284) $this->Char = "A";
            if ($Code >= -20283 and $Code <= -19776) $this->Char = "B";
            if ($Code >= -19775 and $Code <= -19219) $this->Char = "C";
            if ($Code >= -19218 and $Code <= -18711) $this->Char = "D";
            if ($Code >= -18710 and $Code <= -18527) $this->Char = "E";
            if ($Code >= -18526 and $Code <= -18240) $this->Char = "F";
            if ($Code >= -18239 and $Code <= -17923) $this->Char = "G";
            if ($Code >= -17922 and $Code <= -17418) $this->Char = "H";
            if ($Code >= -17417 and $Code <= -16475) $this->Char = "J";
            if ($Code >= -16474 and $Code <= -16213) $this->Char = "K";
            if ($Code >= -16212 and $Code <= -15641) $this->Char = "L";
            if ($Code >= -15640 and $Code <= -15166) $this->Char = "M";
            if ($Code >= -15165 and $Code <= -14923) $this->Char = "N";
            if ($Code >= -14922 and $Code <= -14915) $this->Char = "O";
            if ($Code >= -14914 and $Code <= -14631) $this->Char = "P";
            if ($Code >= -14630 and $Code <= -14150) $this->Char = "Q";
            if ($Code >= -14149 and $Code <= -14091) $this->Char = "R";
            if ($Code >= -14090 and $Code <= -13319) $this->Char = "S";
            if ($Code >= -13318 and $Code <= -12839) $this->Char = "T";
            if ($Code >= -12838 and $Code <= -12557) $this->Char = "W";
            if ($Code >= -12556 and $Code <= -11848) $this->Char = "X";
            if ($Code >= -11847 and $Code <= -11056) $this->Char = "Y";
            if ($Code >= -11055 and $Code <= -10247) $this->Char = "Z";
        }
        if (in_array($this->Char, str_split('QWERTYUIOPASDFGHJKLZXCVBNM0123456789', 1))) {
            $this->IsNotLetter = false;
            $this->FontFile    = $this->LetterFont;
        } else {
            $this->IsNotLetter = true;
            $this->FontFile    = $this->AsianFont;
        }
        $this->Initialize();
    }

    private function Initialize()
    {
        //extension_loaded('gd')
        $Width        = $this->AvatarSize;//Width of avatar
        $Height       = $this->AvatarSize;//Height of avatar
        $Padding      = $this->Padding;
        $this->Avatar = imagecreatetruecolor($Width, $Height);

        //$this->Avatar = new gd_gradient_fill(400,200,'ellipse','#f00','#000',0);
        //header('Content-Type: image/png');
        //return imagepng($this->Avatar);exit;
        //全透明背景
        // imageSaveAlpha($this->Avatar, true);
        // $BackgroundAlpha = imagecolorallocatealpha($this->Avatar, 213, 0, 0, 0);
        // imagefill($this->Avatar, 0, 0, $BackgroundAlpha);
        //抗锯齿

        //Material Design参考颜色
        //http://www.google.com/design/spec/style/color.html#color-color-palette
        $MaterialDesignColor  = [
            // [[167,55,55],[120,40,40]],
            // [[52,138,199],[116,116,191]],
            // [[72,85,99],[41,50,60]],
            // [[238,168,73],[244,107,69]],
            // [[229,57,53],[227,93,91]],
            // [[0,92,151],[54,55,149]],
            [[ 98, 155, 241] , [ 51, 97, 222]] ,
            [[ 102, 201, 247],[ 53, 152, 236]],
            [[ 127, 206, 181],[ 72, 160, 126]],
            [[64, 159, 250],[ 30, 102 ,242]],
            [[ 61 ,219 ,167],[ 28, 181, 111]],
            [[100, 128, 151],[ 52, 73, 94]],
            [[66, 186, 211],[ 31 ,132, 168]],
            [[130, 168, 230],[ 75, 111, 201]],
            [[176, 214 ,80],[ 120, 172, 40]],
            [[44 ,135 ,177],[ 20, 79, 121]],
            [[ 100, 150 ,134],[ 52, 93, 78]],
            [[ 37 ,195, 201],[16, 144, 153]],
            [[ 0 ,178 ,173],[ 0, 123 ,117]],
            [[ 89, 101, 149],[ 45 ,53 ,92]],
            [[ 163 ,189 ,136],[ 106, 136, 80]],
            [[ 86, 187, 100],[ 43, 133 ,52]],
        ];

        $steps = 127;
        $x1= 0;$x2 = $Width;$y1 = 0;$y2 = $Height;
        $Index = mt_rand(0, count($MaterialDesignColor) - 1);
        for($i = 0; $i < $steps; $i ++)
        {
            $alpha = imagecolorallocatealpha(
                $this->Avatar,
                $MaterialDesignColor[$Index][0][0]-floor($i*($MaterialDesignColor[$Index][0][0]-$MaterialDesignColor[$Index][1][0])/$steps),
                $MaterialDesignColor[$Index][0][1]-floor($i*($MaterialDesignColor[$Index][0][1]-$MaterialDesignColor[$Index][1][1])/$steps),
                $MaterialDesignColor[$Index][0][2]-floor($i*($MaterialDesignColor[$Index][0][2]-$MaterialDesignColor[$Index][1][2])/$steps),
                0
            );
            imagefilledrectangle($this->Avatar, $y1,$i,$y2 ,$i , $alpha);
        }

        //exit;
        if (function_exists('imageantialias')) {
            imageantialias($this->Avatar, true);
        }
        //画一个居中圆形
        // imagefilledellipse($this->Avatar,
        //     $Width / 2,
        //     $Height / 2,
        //     $Width,
        //     $Height,
        //     imagecolorallocate($this->Avatar, 0, 0, 0)
        // );

        //字体
        $FontColor = imagecolorallocate($this->Avatar, 255, 255, 255);
        if ($this->IsNotLetter) {
            //中文字符偏移
            $FontSize = 40;
            $X        = 33;
            $Y        = 79;
        } else {
            $FontSize = $Width - $Padding * 2;
            $X        = $Padding + (20 / 196) * $FontSize;
            $Y        = $Height - $Padding - (13 / 196) * $FontSize;
        }
        // 在圆正中央填入字符
        imagettftext($this->Avatar,
            $FontSize,
            0,
            $X,
            $Y,
            $FontColor,
            $this->FontFile,
            $this->Char
        );
    }


    private function Resize($TargetSize)
    {
        if (isset($this->Avatar)) {
            if ($this->AvatarSize > $TargetSize) {
                $Percent         = $TargetSize / $this->AvatarSize;
                $TargetWidth     = round($this->AvatarSize * $Percent);
                $TargetHeight    = round($this->AvatarSize * $Percent);
                $TargetImageData = imagecreatetruecolor($TargetWidth, $TargetHeight);
                //全透明背景
                imageSaveAlpha($TargetImageData, true);
                $BackgroundAlpha = imagecolorallocatealpha($TargetImageData, 255, 255, 255, 127);
                imagefill($TargetImageData, 0, 0, $BackgroundAlpha);
                imagecopyresampled($TargetImageData, $this->Avatar, 0, 0, 0, 0, $TargetWidth, $TargetHeight, $this->AvatarSize, $this->AvatarSize);
                return $TargetImageData;
            } else {
                return $this->Avatar;
            }
        } else {
            return false;
        }
    }

    public function Free()
    {
        imagedestroy($this->Avatar);
    }

    public function Output2Browser($AvatarSize = 0)
    {
        if (!$AvatarSize) {
            $AvatarSize = $this->AvatarSize;
        }
        header('Content-Type: image/png');
        return imagepng($this->Resize($AvatarSize));
    }

    public function Save($Path, $AvatarSize = 0)
    {
        if (!$AvatarSize) {
            $AvatarSize = $this->AvatarSize;
        }
        return imagepng($this->Resize($AvatarSize), $Path);
    }
}