<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>CONVERTER</title>
</head>
<body>
    <?php
        $datafile=fopen("data.txt","r");
        if(!$datafile)
        {
            echo("Data File Does Not Exist!");
            exit();
        }
        else
        {
            echo "<pre>";
            while(!feof($datafile))
            {
                $line=fgets($datafile);
                echo $line;
            }
            echo "</pre>";
            fclose($datafile);
        }
    ?>
</body>
</html>