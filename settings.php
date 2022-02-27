<?php
$setdata = file_get_contents("./js/options.json");  
$setdata = json_decode($setdata, true);
function parseArray($arr=[], $setdata = [], $ind = "") {
    $returnHtml = '';
    foreach($arr as $key => $value)  
    {
        if ($value['type'] != 'subsection'){
            $varName = $key;
            $returnHtml .= '<div id="' . $key . '" class="options-section">';
            $returnHtml .= '<h3 class="has-tooltip" tooltip="' . $value['description'] . '">' . $value['title'] . '</h3>';
            if ($value['type'] == 'input') $returnHtml .= '<input  name="'.$key.' type="number" id="timing" value="' . $setdata[$key] . '"/>';
            if ($value['type'] == 'colorPicker') $returnHtml .= '<input name="colorpicker" type="color" id="colorpicker" name="'.$key.'" value="' . $setdata[$key] . '">';
            if ($value['type'] == 'toggle') {
                $returnHtml .= '<label class="switch"><input type="checkbox" name="'.$key.'"';
                if ($setdata[$key]) $returnHtml .= ' checked ';
                $returnHtml .= '><span class="slider round"></span></label>';
            }
            if ($value['type'] == 'buttonGroup') {
                $returnHtml .= '<div class="button-group">';
                foreach($value['value'] as $elm) {
                    $returnHtml .= '<button class="btn';
                    if ($elm['value'] == $setdata[$key]) $returnHtml .= ' chosen';
                    $returnHtml .= '" data="' . $elm['value'] . '">' . $elm['title'] . '</button>';
                }
                $returnHtml .= '</div>';
            }
            if ($value['type'] == 'file') {
                $returnHtml .= '<form method="post" enctype="multipart/form-data"><input type="file" name="fileToUpload" id="fileToUpload" accept=".csv" ><input type="submit" value="Upload" name="submit"></form>';
            }
            $returnHtml .= '</div>';
        } else {
            $returnHtml .= '<div id="' . $key . '" class="options-subsection">';
            $returnHtml .= '<h3 class="has-tooltip" tooltip="' . $value['description'] . '" style="min-width: 100%;">' . $value['title'] . '</h3>';
            $returnHtml .= parseArray($value['values'], $setdata[$key]);
            $returnHtml .= '</div>';
        }
        
    }
    return $returnHtml;
}
?>

<?php
// SIMPLE SLUGIFY FUNCTION
function slugify($string){
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $string), '_'));
}
?>

<?php
// SIMPLE UPLOAD SCRIPT
function uploadFile () {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = slugify(pathinfo($target_file,PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    uploadFile ();
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
        <link rel="stylesheet" href="css/style-settings-ver-1.0.css">
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
                <?php echo '<h3>General options</h3>' ?>
                <form method="post" enctype="multipart/form-data" id="settings-form">
                    <?php   
                        $data = file_get_contents("./js/options-settings.json");  
                        $data = json_decode($data, true);
                        echo parseArray($data, $setdata);
                    ?> 
                    <input type="submit" value="Save settings" name="submit" />
                </form>
            </div>
        </section>
        <script src="js/main-settings.js" async defer></script>
    </body>
</html>