<?php
class DB {
    private static $instance = null;

    public static function conn() {
        if (self::$instance === null) {
            $config = [
                'host' => 'localhost',
                'dbname' => 'simple_blog',
                'user' => 'root',
                'pass' => '',
            ];
            try {
                self::$instance = new PDO(
                    "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8",
                    $config['user'],
                    $config['pass']
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Ошибка БД: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}