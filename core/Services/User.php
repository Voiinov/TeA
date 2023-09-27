<?php

namespace Core\Services;

use Core\Database;
use Core\Services\Sender;

class User
{
    private static function DB()
    {
        return Database::getInstance();
    }

    /**
     * Отримати всіх користувачів з бази даних
     */
    public function getAllUsers()
    {
        $sql = 'SELECT * FROM users';
        $result = self::DB()->query($sql);

        // Повернути результат запиту в масиві
        return $result->fetchAll();
    }

    public static function applyResetUserPassword(string $token)
    {
        $user = self::getUserByResetToken($token);
        //застосовуємо новий пароль для користувача
        self::DB()->query("UPDATE users SET password=:password WHERE email=:email",["password"=>$user[0]['new_password'],"email"=>$user[0]['email']]);
        // видаляємо запит на скидання паролю
        self::DB()->query("DELETE FROM users_reset_password WHERE email=?",[$user[0]['email']]);
        // повертаємо дані користувача
        return $user;
    }

    /**
     * Отримати користувача за ідентифікатором з бази даних
     * @param id користувача
     */
    public function getUserById($id)
    {
        $sql = "SELECT users.*,options.value AS postShortName,options.description AS postFullName FROM users";
        $sql .= " LEFT JOIN options ON (options.id=users.post AND options.option='post')";
        $sql .= " WHERE users.id = :id";
        $result = self::DB()->query($sql, [':id' => $id]);
        // Повернути результат запиту як об'єкт або null, якщо користувач не знайдений
        return $result->fetch();
    }

    public static function getUserByResetToken($token)
    {
        $sql = "SELECT users.id,users_reset_password.password AS new_password,users_reset_password.email,users.last_name,users.first_name";
        $sql .= " FROM users_reset_password";
        $sql .= " LEFT JOIN users ON users_reset_password.email=users.email";
        $sql .= " WHERE users_reset_password.token=:token LIMIT 1";
        $user = self::DB()->query($sql, ["token" => $token]);
        return $user->rowCount() > 0 ? $user->fetchAll() : false;
    }

    public static function checkEmail($email)
    {
        $user = self::DB()->query("SELECT id FROM users WHERE email=:email", ["email" => $email]);
        return $user->rowCount() > 0 ? $user->fetchAll() : false;
    }

    /**
     * @param string $email
     * @return void
     * @throws \Exception
     */
    public static function resetPassword(string $email)
    {
        if (self::checkEmail($email) && self::checkResetRequest($email)) {
            $token = bin2hex(random_bytes(32));
            $new_password = self::getRandomPassword();
            self::setReset($email, $token, $new_password);

            $sender = new Sender();

            $sender->sendEmail($email, "TeacherAssistant: скидання паролю!", self::resetPasswordInstructionText($new_password, $token));

        }
    }

    /**
     * @param string $password
     * @param string $token
     * @param bool $text
     * @return string
     */
    private static function resetPasswordInstructionText(string $password, string $token, bool $text = false): string
    {
        $link = APP_URL_F . "/reset?token=" . $token;

        if ($text)
            return "Ви надіслали запит на скидання паролю.\n Ваш новий пароль: $password \n Для підтвердження нового паролю перейдіть за посиланням {$link}.";

        return "<p>Ви надіслали запит на скидання паролю.</p><p><b>Ваш новий пароль:</b> $password </p><p>Для підтвердження нового паролю перейдіть за <a href='$link'>посиланням</a>.</p>";
    }

    private static function setReset($email, $token, $new_password)
    {
        $new_password = password_hash($new_password, PASSWORD_DEFAULT);
        self::DB()->query("INSERT INTO users_reset_password (email,token,password) VALUES(?,?,?)", [$email, $token, $new_password]);
    }

    private static function checkResetRequest($email)
    {

        self::DB()->query("DELETE FROM users_reset_password WHERE upd<(NOW() - INTERVAL 1 HOUR) AND email=:email", ["email" => $email]);

        $check = self::DB()->query("SELECT * FROM users_reset_password WHERE email=:email", ["email" => $email]);

        return !($check->rowCount() > 0);

    }

    private static function getRandomPassword($passwordLen = 8): string
    {
        $symbols = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $len = strlen($symbols) - 1; //put the length -1 in cache
        for ($i = 0; $i < $passwordLen; $i++) {
            $n = rand(0, $len);
            $pass[] = $symbols[$n];
        }
        return implode($pass); //turn the array into a string
    }

    /**
     * Створити нового користувача в базі даних
     */
    public function createUser($data)
    {
        $sql = 'INSERT INTO users (username, email, password) VALUES (:username, :email, :password)';
        $result = self::DB()->query($sql, $data);

        // Повернути ідентифікатор нового користувача
        return self::DB()->lastInsertId();
    }

    /**
     * Оновити існуючого користувача в базі даних за ідентифікатором
     *
     * @param id - ідектифікатор
     * @param data
     */
    public function updateUser($id, $data)
    {
        $sql = 'UPDATE users SET username = :username, email = :email, password = :password WHERE id = :id';
        $data['id'] = $id;
        $result = self::DB()->query($sql, $data);

        // Повернути кількість змінених рядків або false, якщо оновлення не вдалося
        return $result->rowCount();
    }

    /**
     * Видалити користувача з бази даних за ідентифікатором
     *
     * @param id ідентифікатор
     */
    public function deleteUser($id)
    {
        $sql = 'DELETE FROM users WHERE id = :id';
        $params = array(':id' => $id);
        $result = self::DB()->query($sql, $params);

        // Повернути кількість видалених рядків або false, якщо видалення не вдалося
        return $result->rowCount();
    }

    /**
     * Повертає посилання на логотип користувача
     */
    public static function avatar(int $uid = null, string $gender = null)
    {
        $path = "public/storage/avatars/u{$uid}.jpg";
        if (file_exists(APP_PATH . "/" . $path))
            return $path;

        return "public/storage/img/ava_{$gender}_user.jpg";
    }


    public static function name()
    {
        return $_COOKIE["username"] ?? "User";
    }


}

?>