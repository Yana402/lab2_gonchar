<?

namespace Utils;
class Database {
    static $hostname = '127.127.126.26';
    static $username = 'root';
    static $password = '';
    static $database = 'beauty';
    public static $connection;
    public static function connect(){
        static::$connection = mysqli_connect(static::$hostname, static::$username, static::$password, static::$database);    
    }
}


