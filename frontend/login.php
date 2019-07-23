<?php
    include "index.php";
?>

<html>
    <head>
        <title>Login</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <style>
            body {
                padding: 0px;
                margin: 0px;
                background-image: url("images/bg-image.jpg");
                background-position: top center;
            }
            .container {
                width: 60%;
                max-width: 480px;
                margin: 100px auto 0px auto;
                border: 1px solid gray;
                -webkit-border-radius: 10px;
                -moz-border-radius: 10px;
                border-radius: 10px;
                background-color: white;
                /*color: white;*/
            }
            .submit-btn {
                width: 100%;
            }

            @media(max-width: 576px) {
                .container {
                    width: 90%;
                    margin: auto;
                    margin-top: 20px;
                    border: 1px solid gray;
                    -webkit-border-radius: 10px;
                    -moz-border-radius: 10px;
                    border-radius: 10px;
                }
            }
        </style>
    </head>

    <body>
        <?php include "components/navbar.php" ?>

        <div class="container pb-3 pt-5 pl-5 pr-5">
            <form method="post" action="../backend/user_login.php">
                <div class="form-group mb-5">
                    <h1 class="text-center">Login</h1>
                    <hr />
                </div>
                <?php
                    session_start();
                    if(isset($_SESSION["user_notfound_error"])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show">';
                        echo '<span>User ID is incorrect!</span>';
                        echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                        echo '</div>';
                        $_SESSION['destroy'] = true;
                    }
                    if(isset($_SESSION['user_password_error'])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show">';
                        echo '<span>Password is incorrect!</span>';
                        echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                        echo '</div>';
                        $_SESSION['destroy'] = true;
                    }
                ?>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input
                            type="email"
                            class="form-control"
                            id="exampleInputEmail1"
                            name="user_id"
                            aria-describedby="emailHelp"
                            placeholder="Enter email"
                            <?php

                                if (isset($_SESSION['user_password_error'])) {
                                    $user_id = $_SESSION['user_password_error'];
                                    echo "value=$user_id";
                                } else if (isset($_SESSION['user_id_registration_success'])) {
                                    $user_id = $_SESSION['user_id_registration_success'];
                                    echo "value=$user_id";
                                }

                            ?>
                            required
                    >
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password" required placeholder="Password">
                </div>
                <button type="submit" class="btn btn-success submit-btn mt-4">Submit</button>
                <div class="form-group muted mt-5 mb-1">
                    <a href="register.php"><p class="text-muted text-center">Sign Up</p></a>
                </div>
            </form>
        </div>
    </body>
    <?php
        if (isset($_SESSION['destroy'])) {
            session_destroy();
        }
    ?>
</html>


