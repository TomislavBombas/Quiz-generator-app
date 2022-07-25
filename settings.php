<?php
    if(isset($_POST["submitall"])) {
        print_r($_POST);
    }
    $uploaded_file ="";
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
    <head>
        <script>var uploadId = null;</script>
<?php
$setdata = file_get_contents("./js/options.json");  
$setdata = json_decode($setdata, true);
$uploadId = "";
$upId = "";
function parseArray($arr=[], $setdata = [], $ind = "") {
    $returnHtml = '';
    foreach($arr as $key => $value)  
    {
        if ($value['type'] != 'subsection'){
            $varName = $key;
            $returnHtml .= '<div id="' . $key . '" class="options-section"';
            if ($uploadId) $returnHtml .= ' value="' . $uploadId . '"';
            $returnHtml .= '>';
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
                if ($uploaded_file != "") {
                    $returnHtml .= '<div class="file-upload"><img src="' . $uploaded_file . '" style="max-height:100px; width: auto;"/></div>';
                } else {
                    $upId = $key;
                    $btnId = "uploadBtn" . (rand(0,100));
                    $returnHtml .= '<div class="file-upload"><input type="file" name="fileToUpload" id="fileToUpload" accept=".csv, .jpg, .png" ><input type="submit" value="Upload" name="submitfile" id="'.$btnId .'"></div>';
                }
                
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
    $uploaded_file = $target_file;
    $uploadOk = 1;
    $imageFileType = slugify(pathinfo($target_file,PATHINFO_EXTENSION));
    
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        //popupMessage ("File is an image - " . $check["mime"] . ".");
        $uploadOk = 1;
    } else {
        popupMessage ("File is not an image.");
        $uploadOk = 0;
    }
    if ($uploadOk == 0) {
        popupMessage ("Sorry, your file was not uploaded.");
      // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $imageFile = file ($target_file);

            $file = './images/'.$_FILES["fileToUpload"]["name"];
            
            popupMessage ("The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.", $_FILES["fileToUpload"]["name"], $file, $uploadId);
            file_put_contents($file, $imageFile);
        } else {
            popupMessage ("Sorry, there was an error uploading your file.");
        }
    }
}

// Check if image file is a actual image or fake image
if(isset($_POST["fileToUpload"])) {
    print_r($_POST["fileToUpload"]);
    uploadFile ();
}


// This is a simple popup message display, don't ovthink it
$openPopup = false;
function popupMessage ($message = 'No message here', $img = null, $id = null) {
    if (!$openPopup) {
        $openPopup = true;
    ?>
    <div id="popup-wrapper">
        <div id="popup-inner-wrapper">
            <div id="message-holder">
                <?php echo $message; ?>
                <a href="#" class="button btn" id="close-button">Ok</a>
            </div>
        </div>
        <script>
            <?php if ($id) {?> var id = "<?php echo $id; }?>";
            function setImageField(img) {
                if (id) {
                    console.log(id);
                    let uploadWrapper = document.getElementById(id);
                } 
            }
            var closeButton
            <?php if ($img) ?> setImageField("<?php echo $img; ?>");
            setTimeout(()=>{
                closeButton = document.getElementById("close-button");
                closeButton.addEventListener("click", (el)=>{
                    el.preventDefault();
                    el.stopPropagation();
                    let popup = document.getElementById('popup-wrapper');
                    popup.parentNode.removeChild(popup);
                });
            },150);
        </script>
    </div>
    <?php } else {
        $openPopup = false;
    }
}
?>

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
                <form method="post" enctype="multipart/form-data" id="settings-form" action="">
                    <!--    POCETAK FORME   -->
                    <?php
                        $data = file_get_contents("./js/options-settings.json");  
                        $data = json_decode($data, true);
                        echo parseArray($data, $setdata);
                    ?> 
                    <!--    KRAJ FORME   -->
                    <input type="submit" value="Save settings" name="submitall" />
                </form>
            </div>
        </section>
        <script src="js/main-settings.js" async defer></script>
    </body>
</html>