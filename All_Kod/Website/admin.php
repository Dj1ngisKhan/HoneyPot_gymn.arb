
<?php


    session_start();
    if(!isset($_SESSION["logged_in"])){
        header("Location: login.php");
        exit;
    }


                            /*  Lite viktiga variabler   */

    $conn = mysqli_connect("localhost", "root", "", "website");
    $error = "";
    $deleted = "";
    $delete = false;
    $search = false;
    $res = false;
    $lis = false;
    $_SERVER["delete"] = false;

    /*  Reseta allt i list databasen ifall man klickar på "search" eller "delete"  */


    if(isset($_POST["search"]) || isset($_POST["delete"])){
        mysqli_query($conn, "DELETE FROM list");
    }

    /*  Här updaterar vi listan med användare automatiskt  */

    if(isset($_POST["list"])){
        $name = $_POST["search-username"];
        $result = mysqli_query($conn, "SELECT * FROM list");
        $userr = mysqli_query($conn, "SELECT * FROM users WHERE username='$name'");

        if(mysqli_num_rows($result) && mysqli_num_rows($userr) != 1){
            $users = mysqli_query($conn, "SELECT id, username, password, mail, timestamp FROM list");
            $run = true;
            $lis = true;
            $res = true;
        }
    }

    /*  Här kollar vi ifall vi ändrar något värde efter att ha sökt efter en specifik användare  */
    

    if(isset($_POST["chg-timestamp"])){

        $id = $_POST["chg-id"];
        $time = $_POST["chg-timestamp"];
        $usr = $_POST["chg-username"];
        $pswd = $_POST["chg-password"];
        $mail = $_POST["chg-email"];

        if($usr == "thisistherealadmin"){
            header("Location: admin.php");
            exit;
        } else{
            $pswd = password_hash($pswd, PASSWORD_DEFAULT);
            $mysqli_qr = "UPDATE users SET timestamp='$time', password='$pswd', mail='$mail', username='$usr' WHERE id= '$id' ";;
            mysqli_query($conn, $mysqli_qr);
        }

    }


/*  If-statement för ifall vi hittar profilen vi söker efter, genom att försöka söka eller radera profilen  */

        if(isset($_POST["search-username"])){
            $username = $_POST["search-username"];

            $sql = "SELECT * FROM users WHERE username='$username'";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) === 1){
                $user = mysqli_fetch_assoc($result);
                $res = true;
                $id = $user["id"];
                $password = $user["password"];
                $timestamp = $user["timestamp"];
                $email = $user["mail"];
            } else {$error = "No user with that username";}



/*  If-statement för ifall vi lägger till en användare vi söker efter i listan, refreashas med nya användare 
varje gång  */

            if(isset($user) && isset($_POST["list"])){
                $lis = true;
                $res = true;
                $sql_query = "SELECT * FROM list WHERE username='$username'";
                $result = mysqli_query($conn, $sql_query);
                $us = mysqli_fetch_assoc($result);
                $users = mysqli_query($conn, "SELECT id, username, password, mail, timestamp FROM list");

                if(mysqli_num_rows($result) !== 1){
                    $sql_query = "INSERT INTO list(username, password, mail) VALUES('$user[username]', '$user[password]', '$user[mail]')";
                    $result = mysqli_query($conn, $sql_query);
                    if(!$result){
                        echo "Could not add profile to 'list' database!";
                    }
                    
                } 


            }



/*  If-statement för ifall vi söker eller raderar en användare i databasen, refreshas helt varje gång  */

            if(isset($user) && isset($_POST["delete"])){

                $sql = "DELETE FROM list";
                mysqli_query($conn, $sql);

                if($user["username"] === "thisistherealadmin" or $user["id"] === 2){
                    $error = "You dont have permissions to delete admin!";
                    $res = false;
                    $delete = true;
                    $delete_class_name = "warning-text";
                }
                if($res === true){
                    $sql = "DELETE FROM users WHERE username='$username' LIMIT 1";
                    $result = mysqli_query($conn, $sql);
                    if($result === true){
                        $delete = true;
                        $deleted = "User {$user["username"]} deleted";
                    }
                }
                
            } elseif(isset($user) && isset($_POST["search"])){ 
                $search = true;
                $sql = "DELETE FROM list";
                mysqli_query($conn, $sql);
            }

            
        }
    $userss = mysqli_query($conn, "SELECT id, username, password, mail, timestamp from users");



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<body>


<!-- Navigation och sido-baren med en massa jävla ikoner -->

    <navigation class="navigation">
        <h2 class="admin-text">Admin</h2>
        <h1 class="headline">Great to have you back!</h1>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="nav-icon">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        </svg>
    </navigation>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="burger-menu">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
    <div class="flexing">

        <!-- If-statement för ifall vi raderar en användare, om den finns såkalart -->

        <div class="boxes">
            <?php if($res === true && $delete === true): ?>

                <div class="if-delete-username">
                    <h2><?php echo $deleted ?></h2>
                </div>

            <?php endif; ?>

            <?php if($res === false && $delete === true): ?>

                <div class="if-delete-username delete_class_name">
                    <h2><?php echo $error ?></h2>
                </div>

            <?php endif; ?>

            <!-- If-statement för ifall vi söker efter en användare eller har sökt -->

            <?php if($res === true && $search === true): ?>
                <div>
                    <table class="search-table">
                        <tr>
                            <th class="sea table-left"><p class="created">Created at</p></th>
                            <th class="sea table-second">Email</th>
                            <th class="sea table-third">Username</th>
                            <th class="sea table-right">Password</th>
                        </tr>
                        <tr>
                            <form method="post" enctype="multipart/form-data" onsubmit="return confirm('Är du säker på att du vill ändra detta?')">
                                <input type="hidden" name="chg-id" value="<?php echo $id; ?>">
                                <td><input type="text" name="chg-timestamp" value="<?php echo $timestamp; ?>"></td>
                                <td><input type="text" name="chg-email" value="<?php echo $email; ?>"></td>
                                <td><input class="short" type="text" name="chg-username" value="<?php echo $username; ?>"></td>
                                <td><input class="short" type="text" name="chg-password" value="<?php echo $password; ?>">
                                <input type="submit" name="change" value="change" class="list-btn edit-user"></td>
                            </form>
                        </tr>
                    </table>
                </div>

                <div class="if-delete-username">
                    <h2><?php echo $username?> found</h2>
                </div>

            <?php endif; ?>

            <!-- Här upprepas och kollar koden ifall vi har listat användare i våran lista.  -->

            <?php if($res === true && $lis === true): ?>
                <div>
                    <table class="search-table">
                        <tr>
                            <th class="search-table-left"><p class="text-left">Created at</p></th>
                            <th class="search-table-second"><p class="text-left email-text-left">Email</p></th>
                            <th class="search-table-third">Username</th>
                            <th class="search-table-right">Password</th>
                        </tr>
                        <?php if(!(isset($us["username"])) && !(isset($run))): ?>
                            <tr>
                                <td><p class="text-center text-left"><?php echo $user["timestamp"]; ?></p></td>
                                <td><p class="text-center"><?php echo $user["mail"]; ?></p></td>
                                <td><p class="text-center"><?php echo $user["username"]; ?></p></td>
                                <td><p class="text-center"><?php echo $user["password"]; ?></p></td>
                            </tr>
                        <?php endif; ?>
                        <?php while($user = mysqli_fetch_assoc($users)): ?>
                        <tr>
                            <td><p class="text-center text-left"><?php echo $user["timestamp"]; ?></p></td>
                            <td><p class="text-center"><?php echo $user["mail"]; ?></p></td>
                            <td><p class="text-center"><?php echo $user["username"]; ?></p></td>
                            <td><p class="text-center"><?php echo $user["password"]; ?></p></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
            </div>
            
            <?php endif; ?>


            <!-- Här är vårat form med input för text och knapparna för att radera och lista och söka -->

            <div class="search-bar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="search-icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>

                <form method="POST" enctype="multipart/form-data">
                    <input name="search-username" type="text" class="search-user" placeholder="Search User">
                    <div class="edit-buttons">
                        <input class="list-btn btn" type="submit" value="List" name="list">
                        <input class="delete-btn btn" type="submit" value="Delete" name="delete">
                        <input class="search-btn btn" type="submit" value="Search" name="search">
                    </div>
                </form>
            </div>

            
            <!-- Lite information om servern som uppdateras hela tiden, 3 headers. -->


            <p class="quote-server">Your Secure Server!</p>
            <h1 class="server-info-headline">Server Information</h1>
            <div class="info-boxes">
                <div class="data-box left">
                    <h2>Number of Users</h2>
                    <h1>13</h1>
                    <p class="left-p">!This is the amount of users on your server!
                    You currently have 13 active users that has signed up to your website.</p>
                </div>
                <div class="data-box middle">
                    <h2>Database Storage</h2>
                    <h1>150GB</h1>
                    <p>!The amount of storage your server has!
                        It currently has 150GB of free storage to use.
                    </p>
                </div>
                <div class="data-box right">
                    <h2>Max User Amount</h2>
                    <h1>50.000</h1>
                    <p>!This shows the maximum amount of users your server can contain!

                        It can currently contain a maximum of 50K users. 
                    </p>
                </div>
            </div>
            <div class="logs"></div>
        </div>
    </div>



    <!-- Börja här jobba med en liten bar med loggotyper från olika företag -->

    <div class="logo-background">
        <h2>Trusted by</h2>
        <div class="logo-images">
            <div class="sub-logo-images">
                <img src="bilder/meta_text.png" alt="meta company text" class="meta-logo"/>
                <img src="bilder/microsoft_text.png" alt="microsoft company text" class="microsoft-logo"/>
                <img src="bilder/apple_text.png" alt="apple company text" class="apple-logo"/>
            </div>
        </div>
    </div>


    <!-- Sidans sista funktion, här skall det vara en while loop med alla användare som man kan se -->

    <div class="dashboard">
    <div class="table-container">
        <table>
            <tr class="top-part-table">
                <th class="user-management"><h3>User Management</h3></th>
                <th></th>
                <th></th>
                <th><button><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="add-user-icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
<p>Export Excel</p></button></th>
                <th><button><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="add-user-icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                </svg>
                <p>Add User</p></button></th>
            </tr>
            <tr>
                <th>Username</th>
                <th>Created</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php while($user = mysqli_fetch_assoc($userss)): ?>
            <tr>
                <td class="username"><?php echo $user["username"];?></td>
                <td class="time"><?php echo $user["timestamp"]; ?></td>
                <td class="role"><?php echo ($user["username"] === "thisistherealadmin" ? "Admin" : "Publisher"); ?></td>
                <td class="status-roll">Active</td>
                <td><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="only-edit edit-delete-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="only-delete edit-delete-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>


<!-- Längst, ned en grå bakgrund med lite länkar och sånt bara, som de flesta sidor har -->

<div class="bottom-bg">
    <a href="welcome.php">welcome</a>
    <a href="login.php">login</a>
    <a href="signup.php">signup</a>
</div>


</body>
</html>


<style>

    html{
        font-size: 62.5%
    }

    body{
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
    }

    .navigation{
        background-color: rgb(82, 82, 82);
        max-width: 100%;
        height: 7rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color:rgb(255, 255, 255);
        stroke:rgb(255, 255, 255);
    }

    .search-table input{
        border: none;
        outline: none;
        text-align: left;
    }

    .search-table .text-center{
        text-align: center;
        color: rgb(45, 45, 45);
        margin-bottom: 0;
        margin-top: 0;
    }

    .search-table-left{
        width: 22rem;
        margin-left: 1rem;
    }

    .search-table .search-table-third{
        padding: 0 3rem;
    }

    .search-table .search-table-right{
        padding: 0 3rem;
    }

    .nav-icon{
        display: block;
        width: 3.4rem;
        margin-left: 2rem;
        margin-right: 1.8rem;
    }

    .headline{
        margin-right: 8rem;
        font-size: 3rem;
        font-family: sans-serif;
        font-weight: 750;
    }

    .navigation h2{
        font-size: 1.8rem;
        font-family: sans-serif;
        margin-right: 8rem;
        margin-left: 4rem;
        position: relative;
    }

    .burger-menu{
        top: 2.3rem;
        left: 0.5rem;
        position: absolute;
        display: inline;
        width: 2.4rem;
        stroke: #fff;
    }

    .flexing{
        display: grid;
    }

    /* Jag använder inte .bars, har bara tagit bort bakgrunden - fast allt annat är aktivt */



    table{
        margin-top: 1rem;
        border-collapse: collapse;
        border: 1px solid rgba(0, 0, 0, 0.8);
    }

    .search-table .sea{
        text-align: left;
    }

    .search-table .table-left{
        width: 20rem;
        text-align: left;
        margin-left: 1rem;
    }

    .search-table .created{
        margin-left: 4rem;
        padding: 0;
        margin-top: 0;
        margin-bottom: 0;
    }

    .search-table .table-second .search-table-second{
        width: 12rem;
    }

    .search-table .table-third .search-third-third{
        width: 10rem;
    }

    .search-table .table-right .search-table-right{
        width: 8rem;
    }

    .search-table th{
        background-color: #04AA6D;
        margin-top: 1.8rem;
        font-size: 1.4rem;
        font-family: sans-serif;
        color:#fff;
        padding: 0.4rem;
        margin-left: 0;
        margin-right: 0;
        height: 2.7rem;
    }

    .edit-user{
        background-color:rgb(46, 46, 46);
        color: #fff;
        border: black 1px solid;
        border-radius: 4px;
        padding: 0.5rem 1rem;
    }

    .search-table td{
        font-size: 1.4rem;
        font-family: sans-serif;
        color:#2e2e2e;
        padding: 0.4rem;
        height: 2.4rem;
        text-align: right;
    }

    .admin-conf-text{
        align-self: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 2.5rem;
        text-align: center;
        align-items: center;
        gap: 3rem;
    }

    .admin-conf-text p{
        font-size: 1.4rem;
        color:rgb(255, 255, 255);
        line-height: 3rem;
        text-align: center;
        text-justify: center;
        font-family: sans-serif;
        margin-bottom: 1rem;
        margin-top: 0;
        font-weight: 450;
        margin-top: 0.8rem;
    }

    .admin-conf-text p:first-child{
        margin-top: 3rem;
    }

    .admin-conf-text div{
        display: flex;
        gap: 1rem;
        width: 8rem;
        height: 4rem;
        align-items: center;
        justify-content: center;
    }

    .admin-conf-text div:hover{
        background-color: rgb(113, 113, 113);
        width: 10rem;
        height: 4rem;
        border-radius: 4px;
        transition: background-color 0.5s, width 0.5s;
        cursor: pointer;
    }

    .admin-conf-text .side-bar-btns{
        width: 2rem;
        stroke: #fff;
        margin-bottom: 0.7rem;
        justify-self: flex-start;
        margin-top: 0.8rem;
    }

    .admin-conf-text .edit-conf-text{
        margin-right: 2.4rem;
    }
    .admin-conf-text .edit-conf-btn{
        margin-right: 0.4rem;
    }

    .admin-conf-text .side-bar-logout-btn{
        margin-right: 0.4rem;
    }

    .admin-conf-text .side-bar-logout-text{
        margin-right: 0.6rem;
    }

    .admin-links{
        color:rgb(56, 179, 255);
        font-size: 1.4rem;
        display: flex;
        flex-direction: column;
        border-style: none;
        outline: none;
        margin-bottom: 6rem;
        text-align: center;
        gap: 2rem;
        font-family: sans-serif;
        border: none;
        text-decoration: none;
    }

    .admin-links a{
        text-decoration: none;
         color: #fff;
    }

    .boxes{
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 4rem;
    }

    .if-delete-username{
        position: fixed;
        top: 50%;
        left: 0;
        width: 100vw;
        height: 6rem;
        background-color:rgb(63, 166, 250);
        z-index: 9999;
        animation: fadeOut 4s forwards;
        pointer-events: none;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .delete_class_name {
        background-color:rgb(253, 51, 51);
        animation: fadeOut 6s forwards;
    }

    @keyframes fadeOut{
        0% {
            opacity: 1;
        }
        100% {
            opacity: 0;
        }
    }

    .if-delete-username h2{
        font-size: 2.6rem;
        font-family: sans-serif;
        color:rgb(255, 255, 255);
        margin-left: 3rem;
    }

    .search-bar{
        margin-top: 3rem;
        margin-bottom: 4rem;
        width: 70vw;
        height: 6rem;
        background-color: white;
        border: 1px solid black;
        border-radius: 2px;
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .search-icon{
        display: inline;
        width: 2.4rem;
        margin-left: 1.5rem;
    }

    form{
        display: flex;
        width: 80vw;
        align-items: center;
    }

    .search-user{
        height: 5rem;
        width: 25rem;
        border: none;
        margin-left: 5rem;
        border-style: none;
        outline: none;
        font-size: 1.5rem;
    }

    form div{
        margin-left: auto;
    }

    .edit-buttons{
        display: flex;
        gap: 1.5rem;
    }

    input.btn{
        display: block;
        width: 7rem;
        height: 3rem;
        text-align: center;
        font-family: sans-serif;
        font-weight: 550;
        font-size: 1rem;
        text-transform: uppercase;
        color: #fff;
        background-color: #2e2e2e;
        border: none;
        border-radius: 4px;
    }


    .search-btn{
        margin-right: 5.5rem;
    }

    .quote-server{
        font-size: 1.8rem;
        font-weight: 600;
        font-family: sans-serif;
        color:rgba(45, 45, 45, 0.8);
        margin-bottom: 0;
    }

    .server-info-headline{
        margin-top: 0;
        font-size: 3rem;
        font-weight: 800;
        font-family: sans-serif;
        color:rgb(45, 45, 45);
        margin-bottom: 2rem;
    }

    .info-boxes{
        display: flex;
        justify-content: center;
        gap: 3.5rem;
        width: 70vw;
        height: 30rem;
    }

    .data-box{
        display: flex;
        flex-direction: column;
        text-align: center;
        gap: 2rem;
        width: 20rem;
        height: 25rem;
        border: 1px rgba(0, 0, 0, 0.3) solid;
        color:rgb(45, 45, 45);
    }

    .data-box.left{
        color: #fff;
        background-color:rgb(56, 179, 255);
        border: none;
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.5);
        border-radius: 2px;
    }

    .data-box.middle{
        border-radius: 1px;
    }

    .data-box.right{
        border-radius: 1px;
    }

    .data-box h2{
        margin-top: 4rem;
        margin-bottom: 0;
        justify-self: flex-start;
        font-family: sans-serif;
        font-size: 1.8rem;
        font-weight: 650;
        margin-bottom: 1.5rem;
    }

    .data-box h1 {
        margin-top: 2rem;
        font-size: 2.5rem;
        font-family: sans-serif;
        font-weight: 750;
        margin-bottom: 0;
    }

    .data-box p{
        margin-top: 0.5rem;
        font-size:  1rem;
        font-family: sans-serif;
        font-weight: 500;
        color:rgba(45, 45, 45, 0.7);
        margin-right: 1rem;
        margin-left: 1rem;
        margin-top: 1rem;
    }

    .data-box p.left-p{
        color: rgba(255, 255, 255, 0.9);
    }




    .logo-background{
        display: flex;
        flex-direction: column;
        position: relative;
        max-width: 100%;
    }

    .logo-background h2{
        margin-left: 2rem;
        font-family: sans-serif;
        font-size: 2.8rem;
        align-self: center;
        justify-self: center;
        text-transform: uppercase;
        font-weight: 800;
        color: #2e2e2e;
        border-right: 1px solid black;
        border-left: 1px solid black;
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }

    .logo-images{
        display: flex;
        align-items: center;
        justify-content: center;
        max-width: 100%;
        height: 10rem;
        background-color: #2e2e2e;
    }

    .sub-logo-images{
        display: flex;
        gap: 1.5rem;
        color: #fff;
    }

    .logo-background .microsoft-logo{
        width: 19rem;
        stroke: #fff;
    }

    .logo-background .apple-logo{
        margin-top: 1.2rem;
        width: 14rem;
        height: 7rem;
    }

    .logo-background .meta-logo{
        margin-top: 0.4rem;
        width: 15rem;
        height: 8.5rem;
    }





    /*   Här är koden för user-management table i botten */


    .dashboard {
        margin-top: 4rem;
        display: flex;
        justify-content: center;   
        padding: 2rem;
    }

    .table-container {
        max-height: 80rem;          
        overflow-y: auto;          
        width: 100%;                 
        max-width: 80rem;            
    }

    .dashboard table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        border: solid rgba(0, 0, 0, 0.2) 1px;
    }

    .top-part-table{
        background-color:rgb(56, 179, 255);
        color: #fff;
        font-size: 1.5rem;
        font-family: sans-serif;
    }

    .add-user-icon{
        width: 1.7rem;
        height: 2rem;
    }

    .top-part-table th{
        padding: 0;
        margin: 0;
        width: 8rem;
    }

    .top-part-table .user-management{
        width: 10rem;
        padding-left: 2rem;
        color: #fff;
        font-size: 1.5rem;
    }

    .top-part-table button{
        background-color: #fff;
        color:rgb(45, 45, 45);
        font-family: sans-serif;
        border: none;
        border-radius: 2px;
        padding: 0.1rem 2rem;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.4rem;
        gap: 0.5rem;
    }

    .top-part-table button p{
        margin: 0;
    }

    th, td {
        padding: 2rem;
        text-align: left;
        font-family: sans-serif;
        font-size: 1.2rem;
        color: #2e2e2e;
        border-bottom: solid black 1px;
        padding-right: 0;
    }

    .table-container .username{
        width: 25rem;
    }

    .table-container .time{
        padding-right: 2rem;
        padding-left: 0;
    }

    .table-container .status-roll{
        position: relative;
        z-index: 0;
    }

    .table-container .status-roll::after{
        content: "";
        display: inline;
        background-color: #04AA6D;
        width: 1rem;
        height: 1rem;
        position: absolute;
        border-radius: 10rem;
        border: none;
        left: 0.2rem;
        top: 2.5rem;
        z-index: 1;
    }

    .table-container .edit-delete-icon{
        width: 2rem;
        height: 2rem;
    }

    .table-container .only-edit{
        stroke: rgb(32, 168, 253);
    }

    .table-container .only-delete{
        stroke: rgb(235, 69, 69);
    }





    /*  Det sista CSS för allt i botten av sidan    */

    .bottom-bg{
        background-color: #2e2e2e;
        width: 100%;
        height: 5rem;
        margin-top: 5rem;
        display: flex;
        gap: 2rem;
        justify-content: center;
        align-items: center;
    }

    .bottom-bg a{
        font-size: 1.5rem;
        color: #fff;
        font-family: sans-serif;
        margin-left: 1rem;
    }



    /*  Saker jag ändrat i efterhand med nya klasser som jag fan inte orkar leta efter    */


    .search-table .text-left{
        text-align: left;
        margin-left: 1.5rem;
        margin-bottom: 0;
        margin-top: 0;
    }

    .search-table .email-text-left{
        text-align: center;
        margin: 0;
    }

</style>
