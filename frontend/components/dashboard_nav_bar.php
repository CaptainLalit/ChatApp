
<html>
    <head>
        <style>
            nav {
                position: relative;
                height: 6%;
            }

            .nav-left {
                display: inline-block;
                position: absolute;
                left: 10px;
                top: 10px;
            }
            .nav-left ul, li {
                display: inline-block;
            }
            .nav-right {
                display: inline-block;
                position: absolute;
                right: 10px;
                top: 10px;
            }
            .nav-right ul, li {
                display: inline-block;
            }

            /*@media(max-width: 768px) {*/
            /*    .nav-right {*/
            /*        display: block;*/
            /*        position: relative;*/
            /*    }*/
            /*    .nav-right ul, li {*/
            /*        display: block;*/
            /*    }*/
            /*}*/

        </style>
    </head>
    <body>
        <nav class="bg-dark">
            <div class="nav-left">
                <ul>
                    <li><h3 style="color: greenyellow;">Hello <?php echo $username ?></h3></li>
                </ul>
            </div>
            <div class="nav-right">
                <ul>
                    <li><input type="text" class="form-control" id="suggestion-input" onkeyup="getSuggestions(this.value)" onfocus="showSuggestionList(this.value)" onfocusout="hideSuggestionList()" placeholder="Find Friends"></li>
                    <li><h4><a class="text-danger ml-2" href='../backend/logout.php'>logout</a></h4></li>
                </ul>
            </div>
        </nav>
    </body>
</html>