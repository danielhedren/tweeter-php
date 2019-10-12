<?php

namespace tweeter\api;

require_once $_SERVER["DOCUMENT_ROOT"]."/../src/autoloader.php";

use tweeter\DAO\{User, Comment, DisplayComment};

session_start();

$json = json_decode(file_get_contents("php://input"));

if ($json->function == "fetch_user") {
    if (filter_var($json->user_id, FILTER_VALIDATE_INT)) {
        echo json_encode(User::fetch($json->user_id));
    }
} else if ($json->function == "create_user") {
    $email = filter_var($json->email, FILTER_VALIDATE_EMAIL);
    $displayname = $json->displayname;
    $password = $json->password;

    if ($email && $displayname && $password) {
        $new_user = new User();
        $new_user->email = $email;
        $new_user->displayname = $displayname;
        if (!$new_user->set_password($password)) {
            echo json_encode(["status" => false, "message" => "bad password"]);
            return;
        }
        try {
            $new_user->save();
        } catch (\Exception $e) {
            //TODO: User friendly error messages
            echo json_encode(["status" => false, "message" => $e->getMessage()]);
            return;
        }

        echo json_encode(["status" => true]);
    } else {
        echo json_encode(["status" => false]);
    }
} else if ($json->function == "verify_user") {
    $email = filter_var($json->email, FILTER_VALIDATE_EMAIL);
    $password = $json->password;

    if ($email && $password) {
        $user = User::fetch_by_email($email);
        if ($user && $user->verify($password)) {
            echo json_encode(["status" => true]);

            $_SESSION["userid"] = $user->get_id();
        } else {
            echo json_encode(["status" => false]);
        }
    } else {
        echo json_encode(["status" => false]);
    }
} else if ($json->function == "logout_user") {
    if (isset($_SESSION["userid"])) {
        unset($_SESSION["userid"]);
        echo json_encode(["status" => true]);
    } else {
        echo json_encode(["status" => false]);
    }
} else if ($json->function == "create_comment") {
    if (isset($_SESSION["userid"])) {
        $comment = new Comment();
        $comment->user_id = $_SESSION["userid"];
        if (property_exists($json, "parent_id")) $comment->parent_id = $json->parent_id;
        $comment->content = $json->content;

        try {
            $comment->save();
            echo json_encode(["status" => true]);
        } catch (\Exception $e) {
            echo json_encode(["status" => false, "message" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => false, "message" => "No valid session"]);
    }
} else if ($json->function == "fetch_comments") {
    $comments = DisplayComment::fetch_chronological(10, 0);
    echo json_encode($comments);
}