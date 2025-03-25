
<?php

  if(isset($pswd_err)){
    echo "Passwords not matching!";
  }

  $error = "";
  session_start();
  $connection = mysqli_connect("localhost", "root", "","login_db");

  $error = ($connection) ? "" : "Error, program not able to connect to database>>" . mysqli_connect_error();


  if($_SERVER["REQUEST_METHOD"] == "POST" && (strlen($error) < 10)){
    
    function error_handling(){
      $pswd = "";
      $username = $_POST["Susername"];
      $email = $_POST["Semail"];
      $password = $_POST["Spassword"];
      $confirm_password = $_POST["SCpassword"];

      if($password !== $confirm_password){
        $pswd_err = "";
        header("Location: login.php");
        exit;
      } 
      
      $pswd = $password;

      $user = ["username" => $username, "email" => $email, "password" => $pswd];

      foreach($user as $key => $value){
        $user[$key] = htmlspecialchars(trim($value));
      }
      
      return $user;

    }

    $user = error_handling();

    $sql = "INSERT INTO users(username, password, email) VALUES('" . $user["username"] . "', '" . $user["password"] . "', '" . $user["email"] . "')";

    if(mysqli_query($connection, $sql)){
      echo "ACCOUNT CREATED";
      header("Location: main.php");
    } else {
      echo "Error sending data to mysql database >>" . mysqli_error($connection);
    }

  } else { $error = "Server Request Error!";}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="login.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <header class="container">
      <section class="box red-box">
        <div class="text">
          <h1>WELCOME BACK</h1>
          <quote>Your personal and secure storage</quote>
          <h3>DOCKING</h3>
        </div>
        <div class="whitebox left">
          <div class="grid-left">
            <form class="login-form" method="POST" enctype="multipart/form-data">
              <input name="username" type="text" class="input-username" placeholder="username" onfocus="this.type='text'; this.value='';"> 

              <input name="password" type="text" class="input-password" value="password"
              onfocus="this.type='password'; this.value='';"
              onblur="if(this.value===''){ this.type='text'; this.value='password'; }">

              <p class="forgot-password">Forgot Password?</p>
              <input class="login-btn" type="submit">
            </form>
            <!-- <div class="btn">
              <button class="login-btn">Login</button>
            </div> -->
            <div class="social-media-header">
              <p>Sign in with social media</p>
            </div>
            <div class="social-logos">
              <img src="apple_icon.png" alt="apple logo" class="apple" />
              <img src="google_icon.png" alt="Google logo" />
              <img src="microsoft_icon.png" alt="Microsoft logo" class="micr" />
            </div>
          </div>
        </div>
      </section>

      <section class="box blue-box">
        <div class="text">
          <h1 class="rubrik-right">CREATE YOUR ACCOUNT</h1>
          <h3 class="docking-right">DOCKING</h3>
        </div>
        <div class="whitebox right">
          <div class="grid-left">
            <form class="sign-form" method="POST" enctype="multipart/form-data">
              <input name="Susername" type="text" class="sign-username" placeholder="username" required>
              <input name="Semail" type="text" class="sign-email" placeholder="email" required>

              <input required name="Spassword" type="text" class="sign-password" value="password"
              onfocus="this.type='password'; this.value='';"
              onblur="if(this.value===''){ this.type='text'; this.value='password'; }">

              <input required name="SCpassword" type="text" class="sign-confirm-password" value="confirm-password"
              onfocus="this.type='password'; this.value='';"
              onblur="if(this.value===''){ this.type='text'; this.value='password'; }">
              <input type="submit" class="sign-btn">
            </form>
            <p class="contact">Contact</p>
          </div>
        </div>
      </section>
    </header>
  </body>
</html>
