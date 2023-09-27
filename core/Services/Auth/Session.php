<?php 
namespace Core\Services\Auth;

class Session extends Auth{
    
    protected static function start($data,$remember){
        
        session_start();
        
        $sessionLifeTime = $remember ? APP_SESSION_REMEMBER_TIME : APP_SESSION_TIME ;

        $sessionID = bin2hex(random_bytes(32));

        parent::Cookie()::setCookie('username', $data['username'], time() + $sessionLifeTime);
        parent::Cookie()::setCookie('uid', $data['id'], time() + $sessionLifeTime);
        parent::Cookie()::setCookie('session',$sessionID, time() + $sessionLifeTime);

        return $sessionID;

    }

    public static function stop() {
        // Завершуємо сеанс та видаляємо дані користувача
        parent::currentSessionDelete();
        session_unset();
        session_destroy();
    }

}
?>