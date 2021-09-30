
<?php

include "includes/functions.php";

session_start();
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

    .panel > .panel-heading {
        background-image: none;
        background-color: skyblue;
        color: black;

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
                <h1 style="font-family: 'Courier New'; color: white;" class="login-panel text-center text-muted">
                    <span> <img style="height: 100px" src="img/cart.png"><b>Marketplace</b></span>
                </h1>

                <?php



                if (isset($_SESSION['sessionUser'])) {
                    $username = preg_split("/ /", $_SESSION['sessionUser'])[0];
                    echo "<h3 id='loginheader' class='text-center text-muted'> Welcome
                    $username</h3>";
                }


                ?>
                <hr/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <?php
                if (isset($_SESSION['userLogged'])) {
                        echo '<button class="btn btn-default" data-toggle="modal" data-target="#newItem"><i class="fa fa-photo"></i> New Item</button>
                              <a href="logout.php" class="btn btn-default pull-right"><i class="fa fa-sign-out"> </i> Logout</a>';
                    } else {
                        echo '<a href="#" class="btn btn-default pull-right" data-toggle="modal" data-target="#login"><i class="fa fa-sign-in"> </i> Login</a>
                              <a href="#" class="btn btn-default pull-right" data-toggle="modal" data-target="#signup"><i class="fa fa-user"> </i> Sign Up</a>';
                    }
                ?>
            </div>
        </div>
        <br>
        <?php
        if (isset($_COOKIE['error_message'])) {
            echo "<div class='row'>
            <div class='col-md-4 col-md-offset-4'><div class='alert alert-danger text-center'>";
            echo  $_COOKIE['error_message'] . "</div>
</div></div>";
            setcookie('error_message', null, time() - 3600);
        }
        ?>





        <div class="row">
            <?php
            $latestViewed = array_reverse(findAllRecentlyViewed($_COOKIE));
            if (!empty($latestViewed)) {
                echo '<div class="row">
            <div class="col-md-3">
                <h2 class="login-panel text-muted">
                Recently Viewed
                </h2>
                <hr/>
            </div>
        </div>';

            }


            if (isset($_SESSION['userLogged'])) {
                $allDownvotes = json_decode(getAllDownvotes($_SESSION['userEmail']));
            }

            foreach ($latestViewed as $latestID) {
                $productInfo = findProductGivenID($latestID);

                echo  '<div class="col-md-3">
                <div class="panel panel-info">
                    <div class="panel-heading">
                 ' . $productInfo['title'];

                    if (isset($_SESSION['userEmail']) && $_SESSION['userEmail'] == $productInfo['author_email']) {
                        echo '<span class="pull-right text-muted">
                            <a class="" href="delete.php?id=' . $productInfo['id'] . '" data-toggle="tooltip" title="Delete item">
                                <i class="fa fa-trash"></i>
                            </a>
                        </span>';
                    }
                        echo '
                    </div>
                    <div class="panel-body text-center">
                        <p>
                            <a href="product.php?id=' . $productInfo['id'] . '">
                                <img class="img-rounded img-thumbnail" src="products/' . $productInfo['picture'] . '"/>
                            </a>
                        </p>
                        <p class="text-muted text-justify">
                        </p>';
                    if (isset($_SESSION['userLogged'])) {
                        echo '
                        <a class="pull-left" href="downvote.php?id=' . $productInfo['id'] . '" data-toggle="tooltip" title="' . ((!in_array($productInfo['id'], $allDownvotes)) ? "Downvote" : "Upvote") . ' item">' . $productInfo['downvotes_count'] . '
                            <i class="fa fa-thumbs-' . ((!in_array($productInfo['id'], $allDownvotes)) ? "down" : "up") . '"></i>
                        </a>';
                    }

                    $user = json_encode($productInfo['author']);
                    echo '
                    </div>
                    <div class="panel-footer ">
                        <span> <a style="cursor: pointer" onclick="abc(' . $productInfo['id'] . ');" data-toggle="modal" data-target="#aCoolModal" data-toggle="tooltip" title="Email seller"><i class="fa fa-envelope"></i> ' . preg_split("/ /", $_SESSION['sessionUser'])[0] . '</a></span>
                        <span class="pull-right">' . $productInfo['price'] . '</span>
                    </div>
                </div>
            </div>';
            }
            ?>

        </div>

        <div class="row">
            <div class="col-md-3">
                <h2 style="color: white; font-family: 'Courier New'" class="login-panel text-muted">
                    Items For Sale
                </h2>
                <hr/>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                    <form class="form-inline" method="post" action="search.php">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                <input type="text" class="form-control" name="searchable" placeholder="Search"/>
                            </div>
                        </div>
                        <input type="submit"  class="btn btn-default" value="Search"/>
                    </form>
                <br>

                <button class="btn btn-default" onclick="copyClipboard()" data-toggle="tooltip" title="Shareable Link!"><i class="fa fa-share"></i></button>
                <input type="hidden" id="hiddenText">
                <br/>
            </div>

        </div>

        <br>
        <div class="row">



            <?php

            if (isset($_SESSION['userEmail'])) {
                $pinnedItems = getAllPinned($_SESSION['userEmail']);
                $deserializedPins = json_decode($pinnedItems);
                $downvotedItems = getAllDownvotes($_SESSION['userEmail']);
                $pinsArray = json_decode(getAllPinned($_SESSION['userEmail']));
            }

            if (isset($_GET['search'])) {

                $searchResult = preg_replace('/\s+/', ' ', $_GET['search']);
                $link = establishDBConnection();
                $products = getAllProducts();
                $foundSearch = [];


                foreach ($products as $product) {
                    if (strpos(strtolower($product['title']), $searchResult) !== false ||
                        strpos(strtolower($product['description']), $searchResult) !== false) {
                        $foundSearch[] = $product['id'];
                    }
                }


                $searchResults = $foundSearch;

                foreach ($searchResults as $id) {
                    echo '<div class="col-md-3">';
                    $product = findProductGivenID($id);
                    if (isset($_SESSION['userLogged'])) {
                        if (isset($deserializedPins) && in_array($product['id'], $deserializedPins)) {
                            echo '
                <div class="panel panel-warning">
                    <div class="panel-heading"><a class="" href="unpin.php?id=' . $product['id'] . '" data-toggle="tooltip" title="Unpin item">
                            <i class="fa fa-dot-circle-o"></i>
                        </a>';

                                } else {
                                    echo '
                <div class="panel panel-info">
                    <div class="panel-heading"><a class="" href="pin.php?id=' . $product['id'] . '" data-toggle="tooltip" title="Pin item">
                            <i class="fa fa-thumb-tack"></i>
                        </a>';
                                }
                            } else {
                                echo '<div class="panel panel-info"><div class="panel-heading">';
                            }

                            echo '<span>
                    ' . $product['title'] . '
                    </span>';

                            if (isset($_SESSION['userEmail']) && $_SESSION['userEmail'] == $product['author_email']) {
                                echo '<span class="pull-right text-muted">
                            <a class="" href="delete.php?id=' . $product['id'] . '&filename=' . $product['picture'] . '" data-toggle="tooltip" title="Delete item">
                                <i class="fa fa-trash"></i>
                            </a>
                        </span>';
                            }
                            echo '</div>
                    <div class="panel-body text-center">
                        <p>
                            <a href="product.php?id=' . $product['id'] . '">
                                <img class="img-rounded img-thumbnail" src="products/' . $product['picture'] . '"/>
                            </a>
                        </p>
                        <p class="text-muted text-justify">
                                ' . $product['description'] . '
                        </p>';
                            if (isset($_SESSION['userLogged'])) {
                                echo '
                        <a class="pull-left" href="downvote.php?id=' . $product['id'] . '" data-toggle="tooltip" title="' . ((!in_array($product['id'], $allDownvotes)) ? "Downvote" : "Upvote") . ' item">' . $product['downvotes_count'] . '
                            <i class="fa fa-thumbs-' . ((!in_array($product['id'], $allDownvotes)) ? "down" : "up") . '"></i>
                        </a>';
                            }
                            echo '
                    </div>
                    <div class="panel-footer ">
                        <span> <a style="cursor: pointer" data-toggle="modal" data-target="#newItem" data-toggle="tooltip" title="Email seller"><i class="fa fa-envelope"></i> ' . preg_split("/ /", $productInfo['author'])[0] . '</a></span>
                        <span class="pull-right">' . $product['price'] . '</span>
                    </div>
                </div>
            </div>';
                }


            } else {
                $products = getAllProducts();
                foreach ($products as $product) {
                    if ($product['downvotes_count'] <= 5) {

                            echo '<div class="col-md-3">';
                            if (isset($_SESSION['userLogged'])) {
                                if (isset($deserializedPins) && in_array($product['id'], $deserializedPins)) {
                                    echo '
                <div class="panel panel-warning">
                    <div class="panel-heading"><a class="" href="unpin.php?id=' . $product['id'] . '" data-toggle="tooltip" title="Unpin item">
                            <i class="fa fa-dot-circle-o"></i>
                        </a>';

                                } else {
                                    echo '
                <div class="panel panel-info">
                    <div class="panel-heading"><a class="" href="pin.php?id=' . $product['id'] . '" data-toggle="tooltip" title="Pin item">
                            <i class="fa fa-thumb-tack"></i>
                        </a>';
                                }
                            } else {
                                echo '<div class="panel panel-info"><div class="panel-heading">';
                            }

                            echo '<span>
                    ' . $product['title'] . '
                    </span>';

                            if (isset($_SESSION['userEmail']) && $_SESSION['userEmail'] == $product['author_email']) {
                                echo '<span class="pull-right text-muted">
                            <a class="" href="delete.php?id=' . $product['id'] . '&filename=' . $product['picture'] . '" data-toggle="tooltip" title="Delete item">
                                <i class="fa fa-trash"></i>
                            </a>
                        </span>';
                            }
                            echo '</div>
                    <div class="panel-body text-center">
                        <p>
                            <a href="product.php?id=' . $product['id'] . '">
                                <img class="img-rounded img-thumbnail" src="products/' . $product['picture'] . '"/>
                            </a>
                        </p>
                        <p  class="text-muted text-justify">
                                ' . $product['description'] . '
                        </p>
                        <p style="color: gray; font-size: 10px" class="text-muted text-justify">
                                Added On: ' . $product['time_added'] . '
                        </p>';
                            if (isset($_SESSION['userLogged'])) {
                                echo '
                        <a class="pull-left" href="downvote.php?id=' . $product['id'] . '" data-toggle="tooltip" title="' . ((!in_array($product['id'], $allDownvotes)) ? "Downvote" : "Upvote") . ' item">' . $product['downvotes_count'] . '
                            <i class="fa fa-thumbs-' . ((!in_array($product['id'], $allDownvotes)) ? "down" : "up") . '"></i>
                        </a>';
                            }
                            echo '
                    </div>
                    <div class="panel-footer ">
                        <span> <a style="cursor: pointer" onclick="abc(' . $product['id'] . ');" data-toggle="modal" data-target="#aCoolModal" data-toggle="tooltip" title="Email seller"><i class="fa fa-envelope"></i> ' . preg_split("/ /", $product['author'])[0] . '</a></span>
                        <span class="pull-right">' . $product['price'] . '</span>
                    </div>
                </div>
            </div>';

                        }
                }

            }

            ?>

        </div> <!-- END OF ROW -->




</div>


<div id="login" class="modal fade" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">
    <form role="form" method="post" action="login.php">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center">Login</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Email</label>
                    <input class="form-control" name="email_login" type="text">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input class="form-control" name="password_login" type="password">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="Login!"/>
            </div>
        </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div id="newItem" class="modal fade" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">
    <form role="form" method="post" action="new_item.php" enctype="multipart/form-data">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center">New Item</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Title</label>
                    <input class="form-control" name="title" type="text">
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input class="form-control" name="price" type="text">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <input class="form-control" name="description" type="text">
                </div>
                <div class="form-group">
                    <label>Picture</label>
                    <input class="form-control" name="picture" type="file">
                </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="Post Item!"/>
            </div>
            </div>

        </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div id="signup" class="modal fade" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">
    <form role="form" method="post" action="signup.php">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center">Sign Up</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>First Name</label>
                    <input class="form-control" name="first_name" type="text">
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input class="form-control" name="last_name" type="text">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input class="form-control" name="email" type="text">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input class="form-control" name="password" type="text">
                </div>
                <div class="form-group">
                    <label>Verify Password</label>
                    <input class="form-control" name="verify_pass" type="text">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="Sign Up!"/>
            </div>
        </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->





    <div id="aCoolModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form role="form" method="post" action="messenger.php">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">
                            Email Seller
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Product ID</label>
                            <input id="modal-id" name="prodID" class="form-control disabled" disabled>
                            <input id="productID" name="ID" value="" type="hidden">
                        </div>
                        <div class="form-group">
                            <label>Message:</label>
                            <textarea rows="5" name="message" class="form-control disabled"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Send Message!"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </form>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



</body>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>


    function copyClipboard() {
        var textCopy = document.createElement('textarea');
        textCopy.type = 'hidden';
        textCopy.value = window.location.href;
        document.body.appendChild(textCopy);

        textCopy.select();

        document.execCommand('copy');
        document.body.removeChild(textCopy);
        console.log(URL);
    }



    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })




    function abc(id) {
        // var id   = $("#"+id+"-id").text();
        // var name = $("#"+id+"-country-name").text();
        $("#productID").val(id);
        $("#modal-id").val(id);


        // $("#modal-country-name").val(name);

        // $('#aCoolModal').modal('toggle')
    }




</script>
</html>

<?php


?>