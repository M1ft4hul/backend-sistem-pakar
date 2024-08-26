<?php
header('Content-type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !empty($_POST['nama_lengkap']) && !empty($_POST['email']) && !empty($_POST['hp'])
        && !empty($_POST['alamat']) && !empty($_POST['password'])
    ) {
        $nama_lengkap = $_POST['nama_lengkap'];
        $email = $_POST['email'];
        $hp = $_POST['hp'];
        $alamat = $_POST['alamat'];
        $password = $_POST['password'];
        try {
            require 'DBConnect.php';
            // check for duplicate user 
            // here I am check for user email id for the same 
            $SELECT__USER__SQL = "SELECT * FROM `login` WHERE login.email=:email;";
            $duplicate__user__statement = $con->prepare($SELECT__USER__SQL);
            $duplicate__user__statement->bindParam(':email', $email, PDO::PARAM_STR);
            $duplicate__user__statement->execute();
            $duplicate__user__flag = $duplicate__user__statement->rowCount();
            if ($duplicate__user__flag > 0) {
                http_response_code(404);
                $server__response__error = array(
                    "code" => http_response_code(404),
                    "status" => false,
                    "message" => "This user is already registered."
                );
                echo json_encode($server__response__error);
            } else {
                // insert/add new user details
                // encrypt user password 
                $password__hash = password_hash($password, PASSWORD_DEFAULT);
                $data__parameters = [
                    "nama_lengkap" => $_POST['nama_lengkap'],
                    "email" => $_POST['email'],
                    "hp" => $_POST['hp'],
                    "alamat" => $_POST['alamat'],
                    "password" => $password__hash
                ];
                // insert data into the database
                $SQL__INSERT__QUERY = "INSERT INTO `login`(
                                                        `nama_lengkap`,
                                                        `email`,
                                                        `hp`,
                                                        `alamat`,
                                                        `password`
                                                    )
                                                    VALUES(
                                                        :nama_lengkap,
                                                        :email,
                                                        :hp,
                                                        :alamat,
                                                        :password
                                                    );";
                $insert__data__statement = $con->prepare($SQL__INSERT__QUERY);
                $insert__data__statement->execute($data__parameters);
                $insert__record__flag = $insert__data__statement->rowCount();
                if ($insert__record__flag > 0) {
                    $server__response__success = array(
                        "code" => http_response_code(200),
                        "status" => true,
                        "message" => "User successfully created."
                    );
                    echo json_encode($server__response__success);
                } else {
                    http_response_code(404);
                    $server__response__error = array(
                        "code" => http_response_code(404),
                        "status" => false,
                        "message" => "Failed to create user. Please try again."
                    );
                    echo json_encode($server__response__error);
                }
            }
        } catch (Exception $ex) {
            http_response_code(404);
            $server__response__error = array(
                "code" => http_response_code(404),
                "status" => false,
                "message" => "Opps!! Something Went Wrong! " . $ex->getMessage()
            );
            echo json_encode($server__response__error);
        } // end of try/catch
    } else {
        http_response_code(404);
        $server__response__error = array(
            "code" => http_response_code(404),
            "status" => false,
            "message" => "Invalid API parameters! Please contact the administrator or refer to the documentation for assistance."
        );
        echo json_encode($server__response__error);
    } // end of Parameters IF Condition
} else {
    http_response_code(404);
    $server__response__error = array(
        "code" => http_response_code(404),
        "status" => false,
        "message" => "Bad Request"
    );
    echo json_encode($server__response__error);
}
