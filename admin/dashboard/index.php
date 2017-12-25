<?php
require_once '../../api/admin_login.php';

session_start();
if(!empty($_POST["login"])) {
	$objAdminLogin = new AdminLogin();
	$userValues = $objAdminLogin->login($_POST["admin_name"], $_POST["admin_password"]);
	if(count($userValues) > 0 && $userValues['status'] == "Success") {
			$_SESSION["admin_name"] = $userValues["user_id"];
			
			if (!empty($_POST["remember"])) {
				setcookie ("admin_name", $_POST["admin_name"], time()+ (10 * 365 * 24 * 60 * 60));
				setcookie ("admin_password", $_POST["admin_password"], time()+ (10 * 365 * 24 * 60 * 60));
			} else {
				if (isset($_COOKIE["admin_name"])) {
					setcookie ("admin_name", "");
				}
				if (isset($_COOKIE["admin_password"])) {
					setcookie ("admin_password", "");
				}
			}
			header( "Location: dashboard.php" ); die;
	} else {
		$_SESSION["admin_name"] = "";
		setcookie ("admin_name", "");
		setcookie ("admin_password", "");
		$message = "Invalid Login";
	}
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Vyngle Admin Panel">
    <meta name="author" content="Vyngle.com">
    <meta name="keyword" content="Vyngle.com">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>vYngle Wines</title>

    <!-- Bootstrap CSS -->    
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- bootstrap theme -->
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <!--external css-->
    <!-- font icon -->
    <link href="css/elegant-icons-style.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
    
    <style>
    .error-message {
		text-align:center;
		color:#FF0000;
	}
    </style>
    
</head>

  <body class="login-img3-body">

    <div class="container">
      <form class="login-form" method="post" action="">
      <div class="error-message"><?php if(isset($message)) { echo $message; } ?></div>
        <div class="login-wrap">
            <p class="login-img"><i class="icon_lock_alt"></i></p>
            <div class="input-group">
              <span class="input-group-addon"><i class="icon_profile"></i></span>
              <input type="text" class="form-control" name="admin_name" id="admin_name" placeholder="Username" autofocus value="<?php if(isset($_COOKIE["admin_name"])) { echo $_COOKIE["admin_name"]; } ?>">
            </div>
            <div class="input-group">
                <span class="input-group-addon"><i class="icon_key_alt"></i></span>
                <input type="password" class="form-control" name="admin_password" id="admin_password" placeholder="Password" value="<?php if(isset($_COOKIE["admin_password"])) { echo $_COOKIE["admin_password"]; } ?>">
            </div>
            <label class="checkbox">
                <input type="checkbox" name="remember" id="remember" <?php if(isset($_COOKIE["admin_name"])) { ?> checked <?php } ?> /> Remember me
            </label>
            <button class="btn btn-primary btn-lg btn-block" type="submit" name="login" value="Login">Login</button>
        </div>
      </form>
    </div>
  </body>
</html>
