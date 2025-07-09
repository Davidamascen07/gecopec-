<?php
class Session {
    
    public static function start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null) {
        self::start();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }
    
    public static function destroy() {
        self::start();
        session_destroy();
        session_start();
        session_regenerate_id(true);
    }
    
    public static function isLoggedIn() {
        self::start();
        return isset($_SESSION['logado']) && $_SESSION['logado'] === true;
    }
    
    public static function getUserId() {
        return self::get('usuario_id');
    }
    
    public static function getUserType() {
        return self::get('usuario_tipo');
    }
}
?>
