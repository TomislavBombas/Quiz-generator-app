<?php

?>
<!DOCTYPE html>

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
        <section>
            <div class="wrapper">
                <?php
                    var_dump ($data[0]);
                    foreach($data as $key => $question) {
                        //var_dump($question);
                    }
                ?>
            </div>
        </section>
        <script src="js/main-ver-1.0.js" async defer></script>
    </body>
</html>