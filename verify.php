
<!DOCTYPE html>
<html>
<head>
    <title>COMP 3015</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<div id="wrapper">

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <h1 class="login-panel text-center text-muted">
                    COMP 3015 Assignment 2
                </h1>

            </div>

        </div>
<!--        --><?php
//
//        if (isset($_COOKIE['error_message'])) {
//            $error_msg = $_COOKIE['error_message'];
//            echo "<div class='alert alert-danger text-center'>";
//            echo "$error_msg</div>";
//            setcookie('error_message', null, time() - 3600);
//        }
//        ?>


                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Verify Your Account</h3>
                    </div>
                    <div class="panel-body">
                        <form name="login" role="form" action="redirect.php?from=login" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control"
                                           value=""
                                           name="username"
                                           placeholder="Username"
                                           type="text"
                                           autofocus
                                    />
                                </div>
                                <div class="form-group">
                                    <input class="form-control"
                                           name="password"
                                           placeholder="Password"
                                           type="password"
                                    />
                                </div>
                                <input type="submit" class="btn btn-lg btn-success btn-block" value="Login"/>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <a class="btn btn-sm btn-default" href="signup.php">Sign Up</a>
            </div>
        </div>



<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
