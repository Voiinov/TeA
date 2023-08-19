<?php 
namespace Core\Services\Auth;

class Session extends Auth{
    
    protected static function start($data,$remember){
        
        session_start();
        
        $sessionLifeTime = $remember ? APP_SESSION_REMEMBER_TIME : APP_SESSION_TIME ;

        $_SESSION["logged_in"] = true;

        parent::Cookie()::setCookie('username', $data['username'], time() + $sessionLifeTime);
        parent::Cookie()::setCookie('uid', $data['id'], time() + $sessionLifeTime);
        
        $token = bin2hex(random_bytes(32));
        self::setSessionCookie($data['username'], $token, $sessionLifeTime);

        return $token;

    }
    
    private static function setSessionCookie($username, $token, $sessionLifeTime) {
        $expire = time() + $sessionLifeTime;
        parent::Cookie()::setCookie('token', $token, $expire);
    }

    public static function stop() {
        // Завершуємо сеанс та видаляємо дані користувача
        session_unset();
        session_destroy();
    }
}
?>