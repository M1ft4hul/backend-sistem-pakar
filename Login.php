<?php
header('Content-type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        try {
            require 'DBConnect.php';
            // checking for valid user details 
            $SELECT__USER__DATA = "SELECT * FROM `login` WHERE login.email=:email";
            $select__user__statement = $con->prepare($SELECT__USER__DATA);
            $select__user__statement->bindParam(':email', $email, PDO::PARAM_STR);
            $select__user__statement->execute();
            $user__flag = $select__user__statement->rowCount();
            if ($user__flag > 0) {
                $user__data = $select__user__statement->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $user__data['password'])) {
                    $user__object = array(
                        "nama_lengkap" => $user__data['nama_lengkap'],
                        "hp" => $user__data['hp'],
                        "alamat" => $user__data['alamat'],
                        "email" => $user__data['email']
                    );
                    http_response_code(200);
                    $server__response__success = array(
                        "code" => http_response_code(200),
                        "status" => true,
                        "message" => "User Verified",
                        "userData" => $user__object
                    );
                    echo json_encode($server__response__success);
                } else {
                    http_response_code(404);
                    $server__response__error = array(
                        "code" => http_response_code(404),
                        "status" => false,
                        "message" => "Opps!! Incorrect Login Credentials"
                    );
                    echo json_encode($server__response__error);
                }
            } else {
                http_response_code(404);
                $server__response__error = array(
                    "code" => http_response_code(404),
                    "status" => false,
                    "message" => "Opps!! Incorrect Login Credentials"
                );
                echo json_encode($server__response__error);
            }
        } catch (Exception $ex) {
            http_response_code(404);
            $server__response__error = array(
                "code" => http_response_code(404),
                "status" => false,
                "message" => "Opps!! Something Went Wrong! " . $ex->getMessage()
            );
            echo json_encode($server__response__error);
        }
    } else {
        http_response_code(404);
        $server__response__error = array(
            "code" => http_response_code(404),
            "status" => false,
            "message" => "Invalid API parameters! Please contact the administrator or refer to the documentation for assistance."
        );
        echo json_encode($server__response__error);
    }
} else {
    http_response_code(404);
    $server__response__error = array(
        "code" => http_response_code(404),
        "status" => false,
        "message" => "Bad Request"
    );
    echo json_encode($server__response__error);
}
