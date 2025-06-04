
<?php

    include "website/together/navigation.php";

    if(isset($_COOKIE["logged_in"]) && $_COOKIE["logged_in"] == "true"){
        $headline = "Admin";
    } else { $headline = "New User";}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <?php


    ?>

    <div class="boxes">
        <p class="box blue">W</p>
        <p class="box blue-two">E</p>
        <p class="box blue-three">L</p>
        <p class="box blue-four">C</p>
        <p class="box blue-five">O</p>
        <p class="box blue-six">M</p>
        <p class="box blue-seven">E</p>
    </div>
    <div class="welcome-header">
        <h1 class="tx"><?php echo $headline; ?></h1>
        <p><aside class="quote">"Håll dig aktiv och i form!"</aside></p>
    </div>
        <div class="bg"></div>
    
</body>
</html>
<style>

    .boxes{
        display: flex;
        gap: 0;
        justify-content: center;
        margin-top: 5rem;
        margin-bottom: 5rem;
    }

    .box{
        text-align: center;
        justify-content: center;
        line-height: 8rem;
        font-size: 4rem;
        font-family: sans-serif;
        font-weight: 600;
        width: 8rem;
        height: 8rem;
        background-color: blue;
    }

    .blue {
        background-color: rgb(0, 179, 255);
        color: #fff;
    }

    .blue-two {
        background-color: rgb(45, 192, 255);
        color: rgb(196, 237, 255);

    }

    .blue-three {
        background-color: rgb(107, 209, 253);
        color: rgb(158, 226, 255);
    }

    .blue-four {
        background-color: rgb(126, 216, 255);
        color:rgb(0, 0, 0);
    }

    .blue-five {
        background-color: rgb(158, 226, 255);
        color:rgb(107, 209, 253);
    }

    .blue-six {
        background-color: rgb(196, 237, 255);
        color:rgb(45, 192, 255);
    }

    .blue-seven {
        background-color: rgb(255, 255, 255);
        color: rgb(0, 179, 255);
    }

    .welcome-header{
        color: #fff;
        padding-top: 2rem;
        padding-bottom: 2rem;
        background-color: rgb(35, 35, 35);
        display: flex;
        flex-direction: column;
        gap: 1.4rem;
        align-items: center;
        justify-content: center;
    }

    .tx {
        font-size: 3rem;
        font-weight: 700;
        font-family: sans-serif;
        text-align: center;
    }

    .quote {
        font-size: 1.4rem;
        font-weight: 540;
        font-family: sans-serif;
        text-align: center;
        font-style: italic;

    }

    .bg{
        background-color: rgb(35, 35, 35);
        display: block;
        width: 100vw;
        height: 32vh;
    }

</style>
