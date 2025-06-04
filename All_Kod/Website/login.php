<?php 
    include "together/functions.php";

    $conn = mysqli_connect("localhost", "root", "", "website");
    session_start();
    if(isset($_SESSION["logged_in"])){
        header("Location: admin.php");
    }

    if($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["username"])){
        $username = htmlspecialchars(trim($_GET["username"]));
        $password= htmlspecialchars(trim($_GET["password"]));
        $honeypot_username = "admin";
        $honeypot_password = "1qaz2wsx3edc";

        if($username == $honeypot_username && $password == $honeypot_password){
        $_SESSION["logged_in_pot"] = true;
        header("Location: /bruh/honeypot/admin.php");
        exit;
        }

    }

    $conn_honeypot = new PDO("mysql:host=localhost;dbname=honeypot", "root", "");
    $conn_honeypot->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["username"])){

        $username = $_GET["username"];
        $password = $_GET["password"];

        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
       
        try {
            $stmt = $conn_honeypot->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die("SQL error: " . $e->getMessage());
        }

        if(count($rows) > 0){
            var_dump($rows);
        }

        $username = htmlspecialchars(trim($_GET["username"]));
        $password= htmlspecialchars(trim($_GET["password"]));
        $pressed_count = 0;
        $btn_pressed = false;
        $admin_id = 2;

        $sql = "SELECT * FROM users WHERE id = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $admin_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        
        if(isset($user["username"]) && isset($user["password"])){
            if($password == $user["password"] && $username == $user["username"]){
                $_SESSION["logged_in"] = true;
                header("Location: admin.php");
                exit;
            } else{
                $wrong_pswd = "true";
                $error_msg = "Wrong password or username";
            }
        }

    } 


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="together/styles.css?v=<?php echo time(); ?>">
</head>
<body class="<?php echo (GetClassName("login"))?>">
    <div class="bg">
        <div>
            <?php  if(isset($wrong_pswd)): ?>
            <div class="wrong-header">
                <p><?php echo $error_msg; ?></p>
            </div>
            <?php endif;  ?>
        </div>
        <div class="boxes">
            <div class="box">
                <form method="GET" enctype="multipart/form-data">
                    <h2 class="login-heading">LOGGA IN</h2>
                    <div class="before usr">
                        <input placeholder="Enter Username" name="username" type="text" id="user" required>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="profile-icon">
                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                        </svg>


                    </div>
                    <!-- Samma sida för inloggning till admin -->
                    <div class="before pswd">
                        <input placeholder="Enter Password" name="password" type="password" id="pswd" required>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="password-icon">
                        <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
                        </svg>

                    </div>
                    <div class="keep-login-div">
                        <button type="button" class="keep-login-btn <?php echo (isset($btn_pressed) && $btn_pressed === true) ? "keep-login-btn-pressed" : "";  ?>" name="remember_btn"></button>
                    </div>
                    <input type="submit" value="Login" class="login-btn">
                    <!-- <a class="forgot-pswd" href="#">Forgot pswd?</a> -->
                </form>
            </div>
            <div class="welcome-text">
                <h2 class="welcome-head">Välkommen Tillbaks</h2>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing
                    elit. Necessitatibus consectetur est obcaecati voluptatum quidem maiores
                    amet consectetur adipisicing
                    elit. Necessitatibus consectetur est obcaecati voluptatum
                </p>
                <div class="page_links">
                    <a href="signup.php">Skapa konto</a>
                    <a href="/bruh/index.php">Hem</a>
                    <a class="login" href="login.php">Logga in</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
