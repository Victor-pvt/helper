<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 20.05.20
 * Time: 17:40
 */

namespace HelperManager\Helper;


use Symfony\Component\Filesystem\Filesystem;
use Exception;

class FoldersHelper
{
    /** @var null |Filesystem */
    protected static $fs=null;

    /**
     * @return null|Filesystem
     */
    protected static function getFs()
    {
        if(!static::$fs){
            $fs = new Filesystem();
            static::$fs = $fs;
        }

        return static::$fs;
    }

    /**
     * @param $folders
     * @return array
     */
    static public function mkDir($folders)
    {
        $fs = static::getFs();
        $out = [];
        if (is_array($folders)) {
            foreach ($folders as $key => $folder) {
                if (!self::existPathFile($folder)) {
                    $fs->mkdir($folder, 0777);
                    $out[] = $key;
                }
            }
        } else {
            if (!self::existPathFile($folders)) {
                $fs->mkdir($folders, 0777);
            }
        }

        return $out;
    }

    /**
     * @param $file
     * @return bool
     */
    static private function isFile($file)
    {
        return is_file($file);
    }

    /**
     * @param $folder
     * @return bool
     */
    static private function isFolder($folder)
    {
        return is_dir($folder);
    }
    /**
     * @param $pathFile
     * @return bool
     */
    static public function existPathFile($pathFile)
    {
        $fs = static::getFs();
        if ($pathFile and $fs->exists($pathFile) and static::isFile($pathFile)) {

            return true;
        }

        return false;
    }
    static public function existPathFolder($pathFile)
    {
        $fs = static::getFs();
        if ($pathFile and $fs->exists($pathFile) and static::isFolder($pathFile)) {

            return true;
        }

        return false;
    }

    /**
     * @param $fromFile
     * @param $toFile
     * @return bool
     */
    static public function renamePathFile($fromFile, $toFile)
    {
        $fs = static::getFs();
        if ($fromFile and static::existPathFile($fromFile) and static::isFile($fromFile)) {
            static::removePathFile($toFile);
            try{
                $fs->rename($fromFile, $toFile);
            }catch (Exception $e){

                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * @param $fromFile
     * @param $toFile
     * @return bool
     */
    static public function copyPathFile($fromFile, $toFile)
    {
        $fs = static::getFs();
        if ($fromFile and static::existPathFile($fromFile) and static::isFile($fromFile)) {
            self::removePathFile($toFile);
            $fs->copy($fromFile, $toFile);

            return true;
        }

        return false;
    }

    /**
     * @param $pathFile
     * @return bool
     */
    static public function removePathFile($pathFile)
    {
        $fs = static::getFs();
        if ($pathFile and static::existPathFile($pathFile) and static::isFile($pathFile)) {
            $fs->remove($pathFile);

            return true;
        }

        return false;
    }

    /**
     * @param $pathFile
     * @return resource
     */
    static public function openPath($pathFile)
    {
        static::removePathFile($pathFile);
        $fp = fopen($pathFile, 'w');

        return $fp;
    }

    /**
     * @param $fp
     */
    static public function closePath($fp)
    {
        fclose($fp);
    }

    /**
     * @param $pathFile
     * @return int|null
     */
    static public function filesizePath($pathFile)
    {
        if(static::existPathFile($pathFile)){
            $l = filesize($pathFile);
            if ($l > 0) {

                return $l;
            }
        }

        return null;
    }

    /**
     * @param $folder
     * @param bool $isExist
     * @param null $type
     * @param null $order
     * @return array
     */
    static public function scandirFolderFile($folder)
    {
        $paths = [];
        if (static::existPathFolder($folder)) {
            $sourceFiles = scandir($folder);
            foreach ($sourceFiles as $i => $filename) {
                $pathFile = $folder . '/' . $filename;
                if (static::existPathFile($pathFile)) {
                    $paths[] = $filename;
                }
            }
        }

        return $paths;
    }
    static public function scandirFolderFolder($folder)
    {
        $paths = [];
        if (static::existPathFolder($folder)) {
            $sourceFiles = scandir($folder);
            foreach ($sourceFiles as $i => $filename) {
                $pathFile = $folder . '/' . $filename;
                if(static::existPathFolder($pathFile)){
                    $paths[] = $filename;
                }
            }
        }

        return $paths;
    }
    /**
     * @param $from
     * @param $to
     * @return bool
     */
    static public function copyPathFolder($from, $to)
    {
        $fs = static::getFs();
        if ($from and static::existPathFile($from) and static::isFolder($from)) {
            $fs->mirror($from, $to);

            return true;
        }

        return false;
    }
    /**
     * @param $link
     * @return null
     */
    public static function checkHTTPLink($link)
    {
        if($link){
            try{
                $status=get_headers($link);
            }catch (Exception $e){

                return null;
            }
            if((in_array("HTTP/1.1 200 OK", $status) or in_array("HTTP/1.0 200 OK", $status)) and !in_array("HTTP/1.1 301 Moved Permanently", $status)){

                return true;
            }
        }

        return null;
    }
}