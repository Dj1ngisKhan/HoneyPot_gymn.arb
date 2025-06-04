
<?php

$blue_steel = "";
$current_page = basename($_SERVER["PHP_SELF"]);
if($current_page === "welcome.php"){
    $blue_steel = "Welcome";
} elseif($current_page === "login.php"){
    $blue_steel = "Login"; 
} elseif($current_page === "singup.php"){
    $blue_steel = "Signup";
} elseif($current_page === "contacts.php"){
    $blue_steel = "Contacts";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <navigation>
        <div class="navigation-square">
            <h1 class="company-text">© Local Admin</h1>
            <ul>
                <li><a class="ref admin" href="/bruh/website/login.php">Login</a></li>
                <li><a class="ref" href="welcome.php">Welcome</a></li>
                <li><a class="ref"href="/bruh/website/signup.php">Signup</a></li>
            </ul>
            <a class="ref profile"href="#"><?php echo $blue_steel; ?></a>
        </div>
    </navigation>
    
</body>
</html>




<style>

    html {
        font-size: 67.5%;
    }

    * {
       padding: 0;
       margin: 0; 
    }

    body {
        background-color: rgb(211, 211, 211);
    }

    .company-text{
        margin-right: auto;
        margin-left: 2rem;
        color: #fff;
        font-family: sans-serif;
        font-size: 1.8rem;
        font-weight: 750;
    }

    .company-text::first-letter{
        font-size: 1rem;
    }

    .profile{
        list-style-type: none;
        margin-left: auto;
        background-color: rgb(4, 172, 172);
        border-radius: 20px;
        padding: 0.7rem 2rem;
        margin-right: 1rem;
    }

    .navigation-square{
        max-width: 120rem;
        height: 5rem;
        background-color: rgb(113, 111, 111);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    ul {
        list-style-type: none;
        display: flex;
        gap: 4rem;
        justify-content: center;
        margin-right: auto;
        margin-left: 10rem;
    }

    .ref{
        text-decoration: none;
        line-height: 1.5;
        color: #fff;
        font-size: 1.6rem;
        font-weight: 600;
        font-family: sans-serif;
    }

    .ref:hover,
    .ref:active {
        color: rgb(206, 104, 104);
        transition: color 0.5s;
    }

    .admin{
        position: relative;
    }



</style>
