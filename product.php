<?php


include "includes/functions.php";

session_start();

$productDetails = findProductGivenID($_GET['id']);

$latestViewed = findAllRecentlyViewed($_COOKIE);

$cookies = findAllRecentlyViewed($_COOKIE);




if (count($cookies) == 4 && !in_array($_GET['id'], $latestViewed)) {
    setcookie('recents' . $cookies[0] , null, -3600);
}

setcookie('recents'.$_GET['id'], $_GET['id'], time() + 3600);

?>




<!DOCTYPE html>
<html>
<head>
    <title>COMP 3015</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet">
</head>

<style>
    #loginheader {
        color: white;
        font-family: "Courier New";

    }


</style>


<body>

<div id="wrapper">

    <div class="container">

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <h1 id="loginheader" class="login-panel text-center text-muted">
                    Marketplace
                </h1>
                <hr/>
            </div>
        </div>

        <div class="row">
            <div class="col-md-offset-3 col-md-6">
                <div>
                    <p>
                        <a class="btn btn-default" href="index.php">
                            <i class="fa fa-arrow-left"></i>
                        </a>
                    </p>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <?php echo $productDetails['title'] ;?>
                    </div>
                    <div class="panel-body text-center">
                        <p>
                            <img class="img-rounded img-thumbnail" src="products/<?php echo $productDetails['picture'] ;?>"/>
                        </p>
                        <p style="color: gray; font-size: 10px" class="text-muted text-justify">
                            Added On: <?php echo $productDetails['time_added'] ?>
                        </p>'
                        <p class="text-muted text-justify">
                            <?php echo $productDetails['description'] ;?>
                        </p>
                    </div>


                    <div class="panel-footer ">
                        <span><a href="mailto:<?php echo $productDetails['author_email'] ;?>"><i class="fa fa-envelope"></i> <?php echo $productDetails['author'] ;?></a></span>
                        <span class="pull-right"> <?php echo $productDetails['price'] ;?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="newPost" class="modal fade" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">
    <form role="form" method="post" action="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New Profile</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Username</label>
                    <input class="form-control disabled" disabled>
                </div>
                <div class="form-group">
                    <label>Profile Picture</label>
                    <input class="form-control" type="file" name="picture">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="Submit!"/>
            </div>
        </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>
