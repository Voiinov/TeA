<?php 
namespace Core\Services\Auth;

use Core\Database;
use Core\Services\Cookie;
use Core\Validator\Validator;

class Auth{
    
    protected $user;
    public static $db;
    public static $cookie;
    protected static $validator;
    private static $Role;
    private static $Status;
    
    public function __construct(){

        self::$db = Database::getInstance();
        self::$cookie = Cookie::getInstance();
        self::$validator = new Validator();
        
    }

    protected static function userStatus(){
        
    }

    protected static function userRole(){
        
    }


    public static function isLoggedIn(){
        
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
            return true;

        if($token = self::$cookie::getCookie("token"))
            return self::sessionTokenCheck($token);
        
        return false;
        
    }

    public static function DB(){
        return self::$db;
    }

    public static function Cookie(){
        return self::$cookie;
    }

    /**
     * Парсинг і перевірка токену з cookie
     */
    private static function sessionTokenCheck($token){
        $check = self::DB()->query("SELECT token FROM users WHERE token=:token",[":token"=>$token]);
        
        $result = $check->fetch(\PDO::FETCH_ASSOC);

        return isset($result['token']);
    }

    /**
     * Реєстрація користувача
     * @param array $data дані форми з $_POST
     */
    public static function register($data){

        Register::registerUser($data);

    }

    public static function signIn($data){

        $user = self::login($data["email"],$data['password']);

        if($user){
            $remember = $_POST['remember'] ?? false;
            $sessionToken = Session::start($user,$remember);

           self::DB()->query("UPDATE users SET token=:token, last_login=now() WHERE id=:id",
        [
            ":token"=>$sessionToken,
            ":id"=>$user['id']
        ]);

        }else{
            self::cookie()::setCookie("errors",_("Invalid email or password"), time()+3600);
        }
        
    }

    /**
     * @param bool $lock true - заблокувати екран, false - вийти з акаунту
     */
    public static function signOut($lock=false){
        Session::stop();
    }

    /**
     * Перевірка унікальності електронної пошти
     * @param string $email адреса електронної пошти
     */
    public static function uniqueEmailCheck(string $email): bool{

        $rqst = self::DB()->query(
            'SELECT COUNT(email) AS eniqueEmail FROM users WHERE email = :email'
            ,[':email' => $email]);

        $result = $rqst->fetch(\PDO::FETCH_ASSOC);

        return $result['eniqueEmail']==0 ? true : false;

    }

    public static function login($email,$password){

        $rqst = self::DB()->query(
            'SELECT * FROM users WHERE email=:email'
            ,[":email" => $email]
        );

        $result = $rqst->fetch(\PDO::FETCH_ASSOC);
        
        return (isset($result['email']) && password_verify($password, $result['password'])) ? $result : false;

    }
}

?>