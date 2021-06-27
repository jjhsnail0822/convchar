<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>CONVERTER</title>
    <link href="stylesheet.css" rel="stylesheet">
</head>
<body>
    <div class="title">
        <h2>CONVERTER</h2>
    </div>
    <div class="input">
        <?php
            $default_fontSize=48;
            $data_array=[];
            $engcode="";

            $datafile=fopen("data.txt","r");
            if(!$datafile)
            {
                echo("Data File Does Not Exist!");
                exit();
            }
            else
            {
                while(!feof($datafile))
                {
                    $line=fgets($datafile);
                    $tmp_array=explode("\t",str_replace("\r\n","",$line));
                    array_push($data_array,$tmp_array);
                }
                fclose($datafile);
            }

            $fontSize=$_POST['fontSize'];
            if(!$fontSize)
            {
                $fontSize=$default_fontSize;
            }

            $vertical=(int)$_POST['vertical'];
            if(!$vertical)
            {
                $vertical=0;
            }
            
            if($vertical==1)
            {
                $verticalText="writing-mode:tb-rl;";
            }
            else
            {
                $verticalText="";
            }

            $textColorR=$_POST['textColorR'];
            $textColorG=$_POST['textColorG'];
            $textColorB=$_POST['textColorB'];
            if((!isset($_POST['textColorR']))||(!isset($_POST['textColorG']))||(!isset($_POST['textColorB'])))
            {
                $textColorR=$textColorG=$textColorB=0;
            }
            
            $bgColorR=$_POST['bgColorR'];
            $bgColorG=$_POST['bgColorG'];
            $bgColorB=$_POST['bgColorB'];
            if((!isset($_POST['bgColorR']))||(!isset($_POST['bgColorG']))||(!isset($_POST['bgColorB'])))
            {
                $bgColorR=$bgColorG=$bgColorB=255;
            }            
        ?>
        <p>
            <form method="post" action="index.php">
                <input type="radio" name="vertical" value="0" <?php if($vertical==0){echo "checked";} ?>> Horizontal<br>
                <input type="radio" name="vertical" value="1" <?php if($vertical==1){echo "checked";} ?>> Vertical<br><br>
                Font Size : <input type="number" name="fontSize" value=<?php echo "\"".$fontSize."\""; ?> min="1" max="512" required> px<br><br>
                R <input type="number" name="textColorR" value=<?php echo "\"".$textColorR."\""; ?> min="0" max="255" required>
                G <input type="number" name="textColorG" value=<?php echo "\"".$textColorG."\""; ?> min="0" max="255" required>
                B <input type="number" name="textColorB" value=<?php echo "\"".$textColorB."\""; ?> min="0" max="255" required>&nbsp;&nbsp;[Color - Text]<br>
                R <input type="number" name="bgColorR" value=<?php echo "\"".$bgColorR."\""; ?> min="0" max="255" required>
                G <input type="number" name="bgColorG" value=<?php echo "\"".$bgColorG."\""; ?> min="0" max="255" required>
                B <input type="number" name="bgColorB" value=<?php echo "\"".$bgColorB."\""; ?> min="0" max="255" required>&nbsp;&nbsp;[Color - Background]<br><br>
                <a href="data.php">[Valid input words]</a><br><br>                
                INPUT<br><br>
                <textarea name="inputText" style="width:75%;" rows="8"><?php
                        $inputText=$_POST['inputText'];
                        echo $inputText;
                    ?></textarea>
                <input type="submit" value="CONVERT">
            </form>
        </p>
    </div>
    <?php
        function findData($letter)
        {
            global $data_array;
            $findNum=-1;
            for($i=0;$i<count($data_array);$i++)
            {
                for($j=0;$j<count($data_array[$i]);$j++)
                {
                    if(strcasecmp($letter,$data_array[$i][$j])==0)
                    {
                        $findNum=$i;
                        break 2;
                    }
                }
            }
            return $findNum;
        }
        
        function getString($num)
        {
            $str="&#";
            $str.=(string)(57344+$num);
            $str.=";";
            return $str;
        }

        function convLetter($letter,$last)
        {
            global $engcode;
            
            if(ctype_alpha($letter))
            {
                $engcode=$engcode.$letter;
                if($last)
                {
                    $findNum=findData($engcode);
                    if($findNum==-1)
                    {
                        return $engcode;
                    }
                    else
                    {
                        return getString($findNum);
                    }
                }
                else
                {
                    return "";
                }
            }

            if(!empty($engcode))
            {
                $findNum=findData($engcode);
                if($findNum==-1)
                {
                    $result=$engcode.$letter;
                    $engcode="";
                    return $result;
                }
                else
                {
                    $engcode="";
                    if($letter=="\n")
                    {
                        return getString($findNum).$letter;
                    }
                    else
                    {
                        return getString($findNum);
                    }
                }
            }

            if($letter=="\n")
            {
                return "<br>";
            }

            $findNum=findData($letter);
            if($findNum==-1)
            {
                return $letter;
            }
            else
            {
                return getString($findNum);
            }
        }
    ?>
    <div class="page" style="font-family: 'SnailCharacter'; font-weight: normal; font-style: normal;">
        <p class="text" style="font-size: <?php echo $fontSize ?>px;<?php echo $verticalText;?>
        color:rgb(<?php echo $textColorR ?>,<?php echo $textColorG ?>,<?php echo $textColorB ?>);
        background-color:rgb(<?php echo $bgColorR ?>,<?php echo $bgColorG ?>,<?php echo $bgColorB ?>);">
            <?php
                $mbLen=mb_strlen($inputText);
                $lastLetter=false;
                for($i=0;$i<$mbLen;$i++)
                {
                    if($mbLen-$i==1)
                    {
                        $lastLetter=true;
                    }
                    $echotext = convLetter(mb_substr($inputText,$i,1),$lastLetter);
                    if(!empty($echotext))
                    {
                        echo $echotext;
                    }
                }
            ?>
        </p>
    </div>
</body>
</html>