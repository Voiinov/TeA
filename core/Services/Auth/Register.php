<?php

namespace Core\Services\Auth;

class Register extends Auth
{

    protected $username;

    protected static function getUserName($lastName, $firstName, $middleName)
    {

        $username = $lastName;
        $username .= " " . mb_substr($firstName, 0, 1) . ".";
        $username .= mb_substr($middleName, 0, 1) . ".";

        return $username;

    }

    static function getRequest($data)
    {

        extract($data);

        $password = password_hash($password, PASSWORD_DEFAULT);

        return [
            "sql" => "INSERT INTO users (email, password,username,last_name,first_name,middle_name) VALUES (:email,:password,:username,:last_name,:first_name,:middle_name)"
            , "params" => [
                ":email" => $email,
                ":password" => $password,
                ":username" => self::getUserName($last_name, $first_name, $middle_name),
                ":last_name" => $last_name,
                ":first_name" => $first_name,
                ":middle_name" => $middle_name
            ]
        ];
    }

    public static function registerUser($data)
    {

        if (parent::uniqueEmailCheck($data['email'])) {
            if (parent::DB()->queryArray(self::getRequest($data))) {
                return ["newuser" => [
                    "id" => parent::DB()->lastInsertId(),
                    $data["first_name"],
                ]
                ];
            }
        } else {
            parent::Cookie()::setCookie("errors", json_encode(["email" => _("Email already taken!")]), time() + 3600);
        }

        return false;

    }

}
