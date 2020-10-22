<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 20.05.20
 * Time: 18:33
 */

namespace HelperManager\Helper;

/**
 * Class TranslitNameHelper
 * @package HelperManager\Helper
 */
class TranslitNameHelper
{
    /**
     * @param string $file
     * @return boolean
     */
    static function checkImageFile($file, $imageType)
    {
        if (!preg_match($imageType, $file)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * из полного имени файла вытаскивается только имя
     * @param string $file
     * @return string $filename
     */
    static function getBaseNameFile($file)
    {
        $fileName = substr($file, strrpos($file, '/', -1) + 1);

        return $fileName;
    }

    /**
     * из полного имени файла вытаскивается только имя, без расширения
     * @param string $file
     * @return string $filename
     */
    static function getNameFile($file)
    {
        $fileName = substr($file, 0, strrpos($file, '.'));

        return $fileName;
    }

    /**
     * из полного имени файла вытаскивается только расширения
     * @param string $file
     * @return string $ext
     */
    static function getExtFile($file)
    {
        $ext = substr($file, strrpos($file, '.') + 1);

        return $ext;
    }
    /**
     * @param $search
     * @param $replace
     * @param $subject
     * @return mixed
     */
    static public function strReplace($search, $replace, $subject)
    {
        $str = str_replace($search, $replace, $subject);

        return $str;
    }
    /**
     * првоеряет наличие кирилики в названии
     * @return bool
     */
    static public function isCyrillicSymbol($txt)
    {
        $cyrillicSet = '/\w*?[А-Яа-яЁё]\w*/iu';
        if (preg_match($cyrillicSet, $txt)) {

            return true;
        }

        return false;
    }
    /**
     * @param $str
     * @return mixed
     */
    static public function replaceLfCr($str)
    {
        $str = static::strReplace("\n", " ", $str);
        $str = static::strReplace("\r", " ", $str);

        return $str;
    }
    /**
     * @param $str
     * @return mixed
     */
    static public function leadZeroRemove($str)
    {
        $str = preg_replace("/(^0+)/i", "",$str);

        return $str;
    }
}