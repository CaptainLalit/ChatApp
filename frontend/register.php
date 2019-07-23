<?php
include "index.php";
session_start();
$_SESSION['registration_process'] = true;
?>

<html>
<head>
    <title>Register</title>
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
    <form method="post" action="../backend/user_register.php">
        <div class="form-group mb-4">
            <h1 class="text-center">Register</h1>
            <hr />
        </div>
        <?php
        if(isset($_SESSION["user_already_exists_error"])) {
            echo '<div class="alert alert-danger alert-dismissible fade show">';
            echo '<span>User already exists with same Email Id!!</span>';
            echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            echo '</div>';
            unset($_SESSION["user_already_exists_error"]);
        }
        ?>
        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input
                type="text"
                class="form-control"
                id="full_name"
                name="full_name"
                placeholder="Enter full name"
                required
                value = <?php echo isset($_SESSION['full_name']) ? $_SESSION['full_name'] : '' ?>
            >
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input
                type="email"
                class="form-control"
                id="exampleInputEmail1"
                name="user_id"
                aria-describedby="emailHelp"
                placeholder="Enter email"
                required
            >
        </div>
        <div class="form-group">
            <label for="full_name">Phone No.</label>
            <input
                type="text"
                class="form-control"
                id="phone_no"
                name="phone_no"
                placeholder="Phone No."
                value = <?php echo isset($_SESSION['phone_no']) ? $_SESSION['phone_no'] : '' ?>
            >
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" minlength="6" required placeholder="Password">
        </div>
<!--        <div class="form-group">-->
<!--            <label for="confirm_password">Confirm Password</label>-->
<!--            <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="6" required placeholder="Confirm password">-->
<!--        </div>-->
        <button type="submit" class="btn btn-success submit-btn mt-4">Register</button>
        <div class="form-group muted mt-5 mb-1">
            <p class="text-muted text-center">Already Registered? <a href="login.php">Login</a></p>
        </div>
    </form>
</div>
</body>
</html>


