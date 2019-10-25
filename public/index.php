<?php

namespace tweeter;

require_once $_SERVER["DOCUMENT_ROOT"]."/../src/autoloader.php";

use tweeter\DAO\DisplayComment;

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

<!-- Comment card template -->
<template id="comment-card-template">
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title"><slot name="displayname">displayname</slot></h5>
            <h6 class="card-subtitle mb-2 text-muted">@<slot name="user_id">user_id</slot> | <slot name="date">date</slot></h6>
        </div>
        <div class="card-body">
            <p class="card-text"><slot name="content">content</slot></p>
        </div>
        <div class="card-footer">
            <button type="button" class="btn btn-primary btn-sm btn-like">
                Like <span class="badge badge-light"><slot name="votes">0</slot></span>
            </button>
            <button type="button" class="btn btn-primary btn-sm btn-reply">
                Reply
            </button>
        </div>
    </div>
</template>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Tweeter</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        </ul>
        <span id="navLoggedOut" <?php if (isset($_SESSION["userid"])) {
            echo 'style="display:none";';
        } ?>>
                <button type="button" class="btn btn-primary mr-1" data-toggle="modal" data-target="#loginModal">
                    Log in
                </button>
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#registerModal">
                    Register
                </button>
            </span>
        <span id="navLoggedIn" <?php if (!isset($_SESSION["userid"])) {
            echo 'style="display:none";';
        } ?>>
                <button id="newCommentBtn" type="button" class="btn btn-primary mr-1" data-toggle="modal" data-target="#commentModal">
                    New comment
                </button>
                <button type="button" class="btn btn-danger mr-1" data-toggle="modal" id="logoutButton">
                    Log out
                </button>
            </span>

        <button id="newReplyBtn" type="button" class="d-none" data-toggle="modal" data-target="#replyModal"></button>
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

<!-- Reply modal -->
<div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replyModalLabel">New reply</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="replyForm" class="form my-2 my-lg-0">
                    <input type="hidden" name="replyParentId" value="0">
                    <textarea maxlength="255" class="form-control" id="replyText" rows="10"></textarea>
                </form>
                <div class="alert alert-info mt-2" style="display:none" role="alert">
                    Post failed
                </div>
            </div>
            <div class="modal-footer">
                <button id="replyButton" type="button" class="btn btn-primary">Post</button>
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
                <div class="alert alert-info mt-2" style="display:none" role="alert">
                    Post failed
                </div>
            </div>
            <div class="modal-footer">
                <button id="commentButton" type="button" class="btn btn-primary">Post</button>
            </div>
        </div>
    </div>
</div>

<!-- Comments -->
<div id="commentsContainer" class="container"></div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="/js/tweeter.js"></script>
</body>

</html>