<?php

namespace Core\Services\Auth;

use Core\Database;
use Core\Services\Cookie;
use Core\Validator\Validator;

class Auth
{

    private static bool $logedIn = false;

    private static array $user = [];

    public static function init()
    {
        if (self::$logedIn === false && self::currentUser()) {
            self::$logedIn = self::Cookie()::isCookie("session");
        }
    }

    private static function DB(): Database
    {
        return Database::getInstance();
    }

    protected static function Cookie(): Cookie
    {
        return Cookie::getInstance();
    }

    private static function getUserInfo()
    {
//        self::$db->query();
    }

    protected static function isAdmin()
    {
        return isset(self::$user["role"]) && self::$user["role"] == 'admin';
    }

    public static function userID(): int
    {
        return self::$user["id"];
    }

    public static function userName()
    {
        return self::$user['username'];
    }

    protected static function userStatus()
    {

    }

    protected static function currentSessionDelete()
    {
        self::DB()->query("DELETE FROM users_sessions WHERE id=:session", ["session" => self::Cookie()::getCookie("session")]);
    }

    protected static function userRole()
    {
        return self::$user["role"] ?? null;
    }

    public static function isLoggedIn(): bool
    {
        return self::$logedIn;
    }

    /**
     * Превірка відкритої сесії
     */
    private static function currentUser(): bool
    {
        $sql = "SELECT options.value AS role, users.id, users.username FROM users_sessions";
        $sql .= " LEFT JOIN users ON users.id=users_sessions.uid";
        $sql .= " LEFT JOIN options ON users.role=options.id";
        $sql .= " WHERE users_sessions.session=:session LIMIT 1";

        $data = self::DB()->query($sql, [":session" => self::Cookie()::getCookie("session")], true);
        self::$user = $data[0] ?? [];

        return isset($data[0]);

    }

    /**
     * Реєстрація користувача
     * @param array $data дані форми з $_POST
     */
    public static function register($data)
    {

        Register::registerUser($data);

    }

    public static function signIn($data)
    {

        $user = self::login($data["email"], $data['password']);
        if ($user) {

            $remember = $_POST['remember'] ?? false;

            self::DB()->query("UPDATE users SET last_login=now() WHERE id=:id",
                [
                    ":id" => $user['id']
                ]);
            $sessionData = [
                "session" => Session::start($user, $remember),
                "uid" => $user['id'],
                "start" => date('c'),
                "ip" => $_SERVER['REMOTE_ADDR'],
                "user_agent" => $_SERVER['HTTP_USER_AGENT'],
            ];

            $sql = "INSERT INTO users_sessions(session,uid,start,ip,user_agent) VALUES(:" . implode(",:", array_keys($sessionData)) . ")";
            $sql .= " ON DUPLICATE KEY UPDATE session=:session,user_agent=:user_agent,start=:start";

            self::DB()->query($sql, $sessionData);

//            self::DB()->insert("users_sessions", array_keys($sessionData), $sessionData);

            return true;

        } else {
            self::Cookie()::setCookie("errors", _("Invalid email or password"), time() + 3600);
            return false;
        }

    }

    /**
     * @param bool $lock true - заблокувати екран, false - вийти з акаунту
     */
    public static function signOut($lock = false)
    {
        Session::stop();
    }

    /**
     * Перевірка унікальності електронної пошти
     * @param string $email адреса електронної пошти
     */
    public static function uniqueEmailCheck(string $email): bool
    {

        $rqst = self::DB()->query(
            'SELECT COUNT(email) AS eniqueEmail FROM users WHERE email = :email'
            , [':email' => $email]);

        $result = $rqst->fetch(\PDO::FETCH_ASSOC);

        return $result['eniqueEmail'] == 0 ? true : false;

    }

    public static function login($email, $password)
    {

        $rqst = self::DB()->query(
            'SELECT * FROM users WHERE email=:email'
            , [":email" => $email]
        );

        $result = $rqst->fetch(\PDO::FETCH_ASSOC);

        return (isset($result['email']) && password_verify($password, $result['password'])) ? $result : false;

    }
}

?>