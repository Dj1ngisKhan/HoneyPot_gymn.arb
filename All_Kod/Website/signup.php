<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
</head>
<body>


<?php

    $conn = mysqli_connect("localhost", "root", "", "website");

    $account_created = false;

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        if(isset($_POST["signup"])){

            $username = $_POST["username"];
            $password = $_POST["password"];
            $verify_password = $_POST["verify-password"];
            $email = $_POST["email"];
            $name = $_POST["name"];

            if(!($password === $verify_password)){
                $error = "Passwords not matching!";                
            } else{

                function user_alread_exists($conn, $user_creds, $sql_query, $type){
                    
                    $stm = mysqli_prepare($conn, $sql_query);
                    $result = mysqli_stmt_bind_param($stm, "s", $user_creds);
                    mysqli_stmt_execute($stm);
                    $result = mysqli_stmt_get_result($stm);
                    
                    if(mysqli_num_rows($result) > 0 && $type == "u"){
                        $error_msg = "Username already taken";
                    } elseif(mysqli_num_rows($result) > 0 && $type = "e"){
                        $error_msg = "Email already in use";
                    }

                    if(isset($error_msg)){
                        return $error_msg;
                    } else{ return "1";}
                }

                $sql_query = "SELECT * FROM users WHERE username=?";
                $check_username = user_alread_exists($conn, $username, $sql_query, "u");

                $sql_query = "SELECT * FROM users WHERE mail=?";
                $check_email = user_alread_exists($conn, $email, $sql_query, "e");

                if(strlen($check_email) > 2){
                    $error = $check_email;
                } elseif(strlen($check_username) > 2){
                    $error = $check_username;
                }

                function create_account($conn, $username, $password, $email){
                    $username = htmlspecialchars(trim($username));
                    $password = htmlspecialchars(trim($password));
                    $email = htmlspecialchars(trim($email));

                    $hash_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    $sql_string = "INSERT INTO users(username, password, mail) VALUES(?, ?, ?)";
                    $stm = mysqli_prepare($conn, $sql_string);
                    mysqli_stmt_bind_param($stm, "sss", $username, $hash_password, $email);
                    mysqli_stmt_execute($stm);

                    mysqli_stmt_close($stm);
                }
                
                if(!(isset($error))){
                    $account_created = true;
                    create_account($conn, $username, $password, $email);
                } elseif(strlen($error) <= 2){
                    create_account($conn, $username, $password, $email);
                }
            }
        }

    }



?>

    <div class="links-pages">
        <a href="login.php">Logga in</a>
        <a href="/bruh/index.php">Hem</a>
    </div>

    <?php if(isset($error) && (strlen($error) > 2)): ?>
        <div class="error-container"><p><?php echo $error ?></p></div>
    <?php elseif($account_created == true): ?>
        <div class="user-created"><p><?php echo "Account Created" ?></p></div>
    <?php endif; ?>

    <div class="box">
        <div><h2 class="signup-header">Skapa konto</h2></div>
        <div class="input-boxes">
            <form method="post">
                <div class="names-div">
                    <input type="text" name="name" placeholder="Namn" required>
                    <input type="text" name="username" placeholder="Användarnamn" required>
                </div>
                <div class="authentication-div">
                    <input type="text" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Lösenord" required>
                    <input type="password" name="verify-password" placeholder="Konfiguera Lösenord" required>
                </div>
                <div class="birth-date">
                    <input type="text" name="year" value="2007">
                    <input type="text" name="month" value="04">
                    <input type="text" name="day" value="18"> 
                </div>
                <div class="gender">
                    <div><p>Man</p> <button class="gender-button"></button></div>
                    <div><p>Kvinna</p> <button class="gender-button"></button></div>
                </div>
                <div class="signup-button">
                    <input type="submit" name="signup" value="Sign Up"> 
                </div>

            </form>
        </div>
    </div>

    
</body>
</html>



<style>


    html{
        font-size: 62.5%
    }

    *{
        margin: 0;
        padding: 0;
    }

    body {
    font-family: sans-serif;
    position: relative;
    margin: 0;
    padding: 0;
    z-index: 0;
    }

    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background-image: url("bilder/login_pic.png");
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
        background-position: center;
        filter: blur(4px) brightness(0.5);
        z-index: -1;
    }

    .links-pages a{
        font-size: 1.8rem;
        color:rgb(245, 245, 245);
        font-weight: 600;
        text-decoration: none;
        margin-left: 1rem;
        margin-top: 1.5rem;
    }

    .user-created{
        position: fixed;
        top: 40%;
        pointer-events: none;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10;
        width: 100vw;
        height: 5rem;
        font-size: 2rem;
        font-weight: 650;
        background-color:rgb(255, 255, 255);
        color:rgb(87, 87, 87);
        animation: fadeOut 5s forwards;
    }

    @keyframes fadeOut{
        0% {
            opacity: 1;
        }
        100% {
            opacity: 0;
        }
    }

    .error-container{
        position: fixed;
        top: 2%;
        left: 25%;
        pointer-events: none;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10;
        width: 50vw;
        height: 7rem;
        font-size: 2rem;
        font-weight: 650;
        background-color:rgb(58, 58, 58);
        color:rgb(255, 255, 255);
    }


    .box{
        width: 35rem;
        height: 42rem;
        margin-top: 10rem;
        margin-right: auto;
        margin-left: auto;
        border: 1px solid rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        background-color: rgba(255, 255, 255, 1);
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.5);
    }

    .signup-header{
        margin-left: 0.5rem;
        margin-top: 1rem;
        font-size: 2.5rem;
        color: rgb(58, 58, 58);
        justify-self: left;
        align-self: left;
        margin-bottom: 1.5rem;
        position: relative;
        margin-bottom: 5rem;
    }

    .signup-header::after{
        position: absolute;
        content: "Skapa ditt konto på sekunder!";
        color: rgba(58,58,58, 0.6);
        font-size: 1.3rem;
        left: 0rem;
        top: 2.8rem;
        width: 25rem;
    }

    .box .names-div{
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .box .names-div input{
        padding-left: 0.2rem;
        height: 3rem;
        background-color: rgb(248, 248, 248);
        border: 1px solid rgb(180, 180, 180);
        color: rgb(179, 179, 179);
        border-radius: 2px;
    }

    .box .authentication-div{
        margin-top: 1rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: center;
        justify-content: center;
    }

    .box .authentication-div input{
        padding-left: 0.5rem;
        height: 3rem;
        width: 94%;
        background-color: rgb(248, 248, 248);
        border: 1px solid rgb(180, 180, 180);
        color: rgb(179, 179, 179);
        border-radius: 2px;
    }

    .box .birth-date{
        margin-top: 2.4rem;
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        position: relative;
    }

    .box .birth-date::after{
        content: "Födelsedag";
        position: absolute;
        width: 20rem;
        top: -1.3rem;
        left: 0.7rem;
        font-size: 1.2rem;
        color:rgb(58, 58, 58, 0.8)
    }

    .box .birth-date input{
        height: 3rem;
        background-color: rgb(248, 248, 248);
        border: 1px solid rgb(180, 180, 180);
        color: rgb(179, 179, 179);
        border-radius: 2px;
        width: 30.5%;
    }

    .box .gender{
        display: flex;
        gap: 0.8rem;
        justify-content: center;
        position: relative;
    }

    .box .gender div{
        margin-top: 2rem;
        height: 3rem;
        background-color: rgb(248, 248, 248);
        border: 1px solid rgb(180, 180, 180);
        color: rgb(179, 179, 179);
        border-radius: 2px;
        width: 32%;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.4rem;
    }

    .box .gender::after{
        content: "Kön";
        position: absolute;
        font-size: 1.2rem;
        top: 0.8rem;
        left: 6rem;
        color:rgb(58, 58, 58, 0.8)
    }

    .box .gender div button{
        border-radius: 2.4rem;
        background-color: none;
        border: 1px solid rgba(58,58,58);
        width: 1.5rem;
        height: 1.5rem;
        cursor: pointer;
    }

    .box .gender div p{
        color: rgba(58,58,58, 0.8);
        font-size: 1.3rem;
    }

    .gender .gender-button:active{
        background-color:#04AA6D;
    }

    .signup-button{
        display: flex;
        justify-content: center;
        text-align: center;
        position: relative;
    }

    .signup-button input{
        margin-top: 2rem;
        color: #fff;
        background-color: #04AA6D;
        border-radius: 0.4rem;
        font-weight: 750;
        font-size: 1.4rem;
        text-align: center;
        width: 12rem;
        height: 3rem;
        border: none;
        cursor: pointer;
    }


</style>
