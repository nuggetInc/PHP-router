<?php

declare(strict_types=1);

class Router
{
    private static array $path;
    private static array $next;
    private static string $rootDir;

    public static function start(string $path, string $rootDir = "")
    {
        self::$path = array();
        self::$next = explode("/", trim($path, "/"));
        self::$rootDir = $rootDir;

        while (!empty(self::$next)) {
            $next = array_shift(self::$next);

            $file = "./pages/" . implode("/", self::$path) . "/$next.php";
            $directory = "./pages/" . implode("/", self::$path) . "/$next/";

            if (file_exists($file) || file_exists($directory)) {
                array_push(self::$path, $next);
            } else {
                self::$next = array();
            }

            if (file_exists($file))
                require($file);
        }
    }

    public static function next(string $default)
    {
        $next = array_shift(self::$next);

        if ($next === null) {
            array_push(self::$path, $default);
        } else {
            $file = "./pages/" . implode("/", self::$path) . "/$next.php";
            $directory = "./pages/" . implode("/", self::$path) . "/$next/";

            if (file_exists($file) || file_exists($directory))
                array_push(self::$path, $next);
            else array_push(self::$path, $default);
        }

        $file = "./pages/" . implode("/", self::$path) . ".php";
        require($file); // If this returns an error then you have set a default which doesn't exist.
    }
}
