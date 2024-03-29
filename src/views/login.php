<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" href="src/css/home/main.css" />
  <link rel="stylesheet" href="src/css/login/main.css" />
</head>

<body>
  <div class="main">
    <div class="left">
      <form action="/postlogin" method="post">
        <div class="heading">Good to see you again!</div>
        <div class="subHeading">Login to continue</div>
        <input type="text" name="username" id="username" class="input" placeholder="username" />
        <input type="password" name="password" id="password" class="input" placeholder="password" />
        <div class="flex">
          <button type="submit" class="btn">Login</button>
          <button type="button" class="btn btn-none">Forgot Password</button>
        </div>
        <div class="flex column margin50">
          <div class="flex center bold">New Here?</div>
          <a class="flex center green" href="./register">Create New Account</a>
        </div>
      </form>
    </div>
    <div class="right">
      <img src="./assets/svgs/bigLogo.svg" class="bigLogo" alt="" />
    </div>
  </div>
</body>

</html>