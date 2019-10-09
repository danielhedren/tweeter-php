<?php

require_once $_SERVER['DOCUMENT_ROOT']."/../src/database.php";
require_once $_SERVER['DOCUMENT_ROOT']."../../src/DAO/User.php";

$json = json_decode($_POST["data"]);

if ($json["function"] == "fetch_user") {
    if (filter_var($json["user_id"], FILTER_VALIDATE_INT)) {
        echo json_encode(User::fetch($json["user_id"]));
    }
} else if ($json["function"] == "create_user") {
    $email = filter_var($json["emaiL"], FILTER_VALIDATE_EMAIL);
} else if ($json["function"] == "verify_user") {
    $email = filter_var($json["email"], FILTER_VALIDATE_EMAIL);
    $password = $json["password"];

    if ($email && $password) {
        $user = User::fetch_by_email($email);
        if ($user && $user->verify($password)) {
            echo json_encode(["status" => true]);
        } else {
            echo json_encode(["status" => false]);
        }
    } else {
        echo json_encode(["status" => false]);
    }
}