<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "../../src/DAO/Comment.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "../../src/DAO/User.php";

session_start();
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Tweeter</title>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Tweeter</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        </ul>
        <span id="navLoggedOut" <?php if (isset($_SESSION["userid"])) { echo 'style="display:none";'; }?>>
                <button type="button" class="btn btn-primary mr-1" data-toggle="modal" data-target="#loginModal">
                    Log in
                </button>
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#registerModal">
                    Register
                </button>
            </span>
        <span id="navLoggedIn" <?php if (!isset($_SESSION["userid"])) { echo 'style="display:none";'; }?>>
                <button type="button" class="btn btn-primary mr-1" data-toggle="modal" data-target="#commentModal">
                    New comment
                </button>
                <button type="button" class="btn btn-danger mr-1" data-toggle="modal" id="logoutButton">
                    Log out
                </button>
            </span>
    </div>
</nav>

<!-- Login modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="loginForm" class="form my-2 my-lg-0">
                    <input required autocomplete="tweeter_email" id="loginEmail" class="form-control mr-sm-2" type="email" placeholder="Email" aria-label="Email">
                    <input required minlength="8" autocomplete="tweeter_password" id="loginPassword" class="form-control mt-2 mr-sm-2" type="password" placeholder="Password" aria-label="Password">
                </form>
                <div class="alert alert-info mt-2" style="display:none" role="alert">
                    Log in failed
                </div>
            </div>
            <div class="modal-footer">
                <button id="loginButton" type="button" class="btn btn-primary">Login</button>
            </div>
        </div>
    </div>
</div>

<!-- Register modal -->
<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Register</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="registerForm" class="form my-2 my-lg-0">
                    <input required id="registerEmail" class="form-control mr-sm-2" type="email" placeholder="Email" aria-label="Email">
                    <input required id="registerDisplayname" class="form-control mt-2 mr-sm-2" type="text" placeholder="Display name" aria-label="Display name">
                    <input required minlength="8" id="registerPassword1" class="form-control mt-2 mr-sm-2" type="password" placeholder="Password" aria-label="Password">
                    <input required minlength="8" id="registerPassword2" class="form-control mt-2 mr-sm-2" type="password" placeholder="Confirm password" aria-label="Password">
                </form>
                <div class="alert alert-info mt-2" style="display:none" role="alert">
                    Registration failed
                </div>
            </div>
            <div class="modal-footer">
                <button id="registerButton" type="button" class="btn btn-primary">Register</button>
            </div>
        </div>
    </div>
</div>

<!-- Comment modal -->
<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel">New comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="commentForm" class="form my-2 my-lg-0">
                    <textarea maxlength="255" class="form-control" id="commentText" rows="10"></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button id="commentButton" type="button" class="btn btn-primary">Post</button>
            </div>
        </div>
    </div>
</div>

<!-- Comments -->
<div id="commentsContainer" class="container">
    <?php
    $comments = Comment::fetch_chronological(10, 0);

    foreach ($comments as $c) {
        ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title"><?php echo User::fetch($c->user_id)->displayname ?></h5>
                <h6 class="card-subtitle mb-2 text-muted"><?php echo $c->date ?></h6>
            </div>
            <div class="card-body">
                <p class="card-text"><?php echo htmlspecialchars($c->content) ?></p>
            </div>
        </div>
        <?php
    }
    ?>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    "use strict;"

    /*
        Utility method for API calls
    */
    function postJson(data, callback) {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.open("POST", "/api/v1.php", true); // true for asynchronous
        xmlHttp.setRequestHeader("Content-Type", "application/json");
        xmlHttp.send(JSON.stringify(data));
        xmlHttp.onreadystatechange = () => {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                var jsonResponse;
                // Break if response is unparseable
                try {
                    jsonResponse = JSON.parse(xmlHttp.response);
                } catch (e) {
                    return;
                }

                callback(jsonResponse);
            }
        }
    }

    /*
        Login
    */
    function login(email, password) {
        postJson({
            "function": "verify_user",
            "email": email,
            "password": password
        }, (response) => {
            if (response["status"]) {
                document.querySelector("#loginModal > div > div > div.modal-header > button").click();

                document.querySelector("#navLoggedOut").style.display = "none";
                document.querySelector("#navLoggedIn").style.display = "block";
            }
            document.querySelector("#loginModal .alert").style.display = response["status"] ? "none" : "block";
        });
    }

    document.querySelector("#loginButton").onclick = () => {
        if (!document.querySelector("#loginForm").reportValidity()) return;

        var email = document.querySelector("#loginEmail").value;
        var password = document.querySelector("#loginPassword").value;

        login(email, password);
    }

    /*
        Register
    */
    document.querySelector("#registerButton").onclick = () => {
        if (!document.querySelector("#registerForm").reportValidity()) return;

        var email = document.querySelector("#registerEmail").value;
        var displayname = document.querySelector("#registerDisplayname").value;
        var password = document.querySelector("#registerPassword1").value;
        var password2 = document.querySelector("#registerPassword2").value;

        if (password != password2) {
            return;
        }

        postJson({
            "function": "create_user",
            "email": email,
            "displayname": displayname,
            "password": password
        }, (response) => {
            if (response["status"]) {
                document.querySelector("#registerModal > div > div > div.modal-header > button").click();
                login(email, password);
            }
            document.querySelector("#registerModal .alert").style.display = response["status"] ? "none" : "block";
        });
    }

    /*
        Logout
    */
    function logout() {
        postJson({
            "function": "logout_user"
        }, (response) => {
            if (response["status"]) {
                document.querySelector("#navLoggedIn").style.display = "none";
                document.querySelector("#navLoggedOut").style.display = "block";
            }
        });
    }

    document.querySelector("#logoutButton").onclick = () => {
        logout();
    }

    /*
        Post comment
    */
    document.querySelector("#commentButton").onclick = () => {
        var content = document.querySelector("#commentText").value;

        postJson({
            "function": "create_comment",
            "content": content
        }, (response) => {
            if (response["status"]) {
                document.querySelector("#commentModal > div > div > div.modal-header > button").click();
                fetchComments();
            }
            document.querySelector("#commentModal .alert").style.display = response["status"] ? "none" : "block";
        });
    }

    /*
        Fetch comments
    */
    function fetchComments(num, page) {
        postJson({
            "function": "fetch_comments"
        }, (response) => {
            var container = document.getElementById("commentsContainer");
            container.innerHTML = "";

            response.forEach((comment) => {
                container.innerHTML += getCommentCardHtml(comment);
            });
        });
    }

    function getCommentCardHtml(comment) {
        var html = '<div class="card mt-3"><div class="card-header"><h5 class="card-title">';
        html += comment["displayname"];
        html += '</h5><h6 class="card-subtitle mb-2 text-muted">';
        html += comment["date"];
        html += '</h6></div><div class="card-body"><p class="card-text">';
        html += comment["content"];
        html += '</p></div></div>';
        return html;
    }
</script>
</body>

</html>