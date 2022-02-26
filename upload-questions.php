<?php

function slugify($string){
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $string), '_'));
}
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
      // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
            $csvFile = file ($target_file);

            $file = './data/questions.csv';

            file_put_contents($file, $csvFile);
            echo '<div id="popupOuterWrapper"><div id="popupWrapper">New list of questions have been saved,<br/>please wait you will be returned to settings.</div></div>';
            echo '<script>setTimeout(()=>{document.location = "./";},1000);</script>';
        } else {
            echo "Sorry, there was an error uploading your file.";
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
        <section>
            <div class="wrapper">
                <p style="margin-top:15px; margin-bottom: 15px;">CSF formating instructions:
                    <ul class="requirements">
                        <li>Required fields are:
                            <ol>
                                <li>question</li>
                                <li>answer</li>
                                <li>answer</li>
                                <li>answer</li>
                                <li>answer</li>
                                <li>answer</li>
                                <li>"correct answer"</li>
                                <li>image</li>
                            </ol>
                        </li>
                    </ul>
                </p>
                <p style="margin-top:15px;">All fields should be "quoted", meanimng put in between the quotes</p>
                <h3 style="margin-top:65px; margin-bottom: 15px;">Please upload CSV file with questions</h3>
                <form method="post" enctype="multipart/form-data">
                    <input type="file" name="fileToUpload" id="fileToUpload" accept=".csv" >
                    <input type="submit" value="Upload" name="submit">
                </form>
            </div>
        </section>
        <script src="" async defer></script>
    </body>
</html>