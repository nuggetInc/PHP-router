<?php

declare(strict_types=1);

/**
 * Static class for all router related functions
 */
class Router
{
    /**
     * The path of the last required file.
     * 
     * @var array $path
     */
    private static $path;

    /**
     * The rest of the path.
     * Can be used to store get variables in the path.
     * 
     * @var array $next
     */
    private static $next;

    /**
     * The root of the project.
     * 
     * @var string $rootDir
     */
    private static $rootDir;

    /**
     * Starts the routing process.
     * 
     * @param string $path The path to route.
     * 
     * @param string $rootDir The root of the project.
     * 
     * @param string $default The default page if next is empty or invalid.
     * 
     * @return void
     */
    public static function start($path, $rootDir, $default)
    {
        self::$path = array();
        self::$next = preg_split("/\//", trim($path, "/"), -1, PREG_SPLIT_NO_EMPTY);
        self::$rootDir = $rootDir;

        self::next($default);
        self::all();
    }

    /**
     * Finish requiring all pages here.
     * Can be used to place all pages inside the current page.
     * 
     * @return void
     */
    public static function all()
    {
        while (!empty(self::$next)) {
            $next = array_shift(self::$next);

            $file = "./pages/" . implode("/", self::$path) . "/$next.php";
            $directory = "./pages/" . implode("/", self::$path) . "/$next/";

            if (file_exists($file) || file_exists($directory)) {
                array_push(self::$path, $next);
            } else {
                break;
            }

            if (file_exists($file))
                require($file);
        }
    }

    /**
     * Forcess the router to route to the next file.
     * Can be used to place a subpage inside its parent.
     * 
     * @param string $default The default page if next is empty or invalid.
     * 
     * @return string The subpage that was routed to.
     */
    public static function next($default)
    {
        if (!empty(self::$next)) {
            $next = $default;
        } else {
            $next = array_shift(self::$next);

            $file = "./pages/" . implode("/", self::$path) . "/$next.php";
            $directory = "./pages/" . implode("/", self::$path) . "/$next/";

            if (file_exists($file) || file_exists($directory)) {
                array_push(self::$path, $next);
            } else {
                $next = $default;
                self::$next = array();
            }
        }
        array_push(self::$path, $default);

        $file = "./pages/" . implode("/", self::$path) . ".php";
        require($file); // If this returns an error then you have set a default which doesn't exist.

        return $next;
    }
}
