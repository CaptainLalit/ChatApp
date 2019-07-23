<html lang="en">
<head>
    <?php include "index.php" ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <style>
        * {
            box-sizing: border-box;
        }
        .image-content {
            top: 40%;
            width: 100%;
            position: absolute;
            z-index: 2;
            color: white;
        }
        .image-content h1 {
            font-size: 58px;
        }
        .image-content button {
            font-size: 28px;
        }
        @media (max-width: 800px) {
            .image-content {
                top: 40%;
                width: 80%;
                left: 10%;
            }
            .image-content h1 {
                font-size: 28px;
            }
            .image-content button {
                font-size: 18px;
            }
        }
    </style>
</head>

<body class="text-center">
    <div style="position: fixed; overflow: auto; text-align: left; width: 100%; z-index: 999">
        <?php include "components/navbar.php" ?>
    </div>


    <div class="main">
        <img src="images/bg-image.jpg" width="100%" height="100%">
        <div class="image-content">
            <h1>Simple, Fast And Light Chatting Application</h1>
            <a href="login.php"><button class="btn btn-primary">Start Chatting</button></a>
        </div>
    </div>

    <?php include "components/footer.php" ?>
</body></html>