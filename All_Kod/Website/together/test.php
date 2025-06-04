<?php
  $result = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Test</title>
  <style>
    body {
      margin: 0;
      padding: 2rem;
      background-color: #fff;
      font-family: sans-serif;
    }

    .delete-user {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 3rem;
      background-color: blue;
      color: black;
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      animation: fadeOut 3s forwards;
      pointer-events: none; /* gör så att den inte blockerar klick */
    }

    @keyframes fadeOut {
      0% {
        opacity: 1;
      }
      100% {
        opacity: 0;
        visibility: hidden;
      }
    }
  </style>
</head>
<body>

  <h2>Welcome Back!</h2>

  <?php if($result === true): ?>
    <div class="delete-user">
        <h2>User deleted success</h2>
    </div>
  <?php endif; ?>

  <p>Your daily best and most shitty website test code ever, feel free to die 😎</p>

</body>
</html>
