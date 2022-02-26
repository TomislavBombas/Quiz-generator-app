<?php
function parseArray($arr, $ind = "") {
    $indent = "&nbsp;&nbsp;&nbsp;&nbsp;";
    foreach($arr as $key => $value)  
    {
        if (!is_array($value)) {
            print_r($ind . $key . ": " . $value . '<br/> '); 
        } else {
            print_r($ind . $key . ": <br/>");
            parseArray($value, $ind . $indent);
        }
    }
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/style-ver-1.0.css">
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <header>
            <nav>
                <ul>
                    <li><a href="mailto:TomislavBombas@users.github.com">TomislavBombas Github</a></li>
                    <li><a href="./">Quiz</a></li>
                    <li><a href="./settings.php">Settings</a></li>
                </ul>
            </nav>
        </header>
        <section class="settings-options" style="padding-top:60px;">
            <div class="wrapper">
                <h3>Manage Quiz questions</h3>
                <ul>
                    <li><a href="./upload-questions.php" class="button">Upload CSV with questions</a> <a href="#" class="tooltip-questionmark">?</a></li>
                    <li><a href="./add-question.php" class="button">Add questions one by one</a> <a href="#" class="tooltip-questionmark">?</a></li>
                <ul>
            </div>
        </section>
        <section class="settings-options">
            <div class="wrapper">
                <form method="post" enctype="multipart/form-data">
                <?php   
                          $data = file_get_contents("./js/options-settings.json");  
                          $data = json_decode($data, true);  
                          parseArray($data);
                          ?> 
                    <input type="submit" value="Save settings" name="submit" />
                </form>
            </div>
        </section>
        <script src="" async defer></script>
    </body>
</html>