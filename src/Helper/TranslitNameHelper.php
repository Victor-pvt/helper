<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 20.05.20
 * Time: 18:33
 */

namespace HelperManager\Helper;

use Exception;
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
    static public function translit($st)
    {
        $st = mb_strtolower($st, "utf-8");
        $st = str_replace([
            '?', '!', '.', ',', ':', ';', '*', '(', ')', '{', '}', '[', ']', '%', '#', '№', '@', '$', '^', '-', '+', '/', '\\', '=', '|', '"', '\'',
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'з', 'и', 'й', 'к',
            'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х',
            'ъ', 'ы', 'э', ' ', 'ж', 'ц', 'ч', 'ш', 'щ', 'ь', 'ю', 'я'
        ], [
            '_', '_', '.', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_',
            'a', 'b', 'v', 'g', 'd', 'e', 'e', 'z', 'i', 'y', 'k',
            'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h',
            'j', 'i', 'e', '_', 'zh', 'ts', 'ch', 'sh', 'shch',
            '', 'yu', 'ya'
        ], $st);
        $st = preg_replace("/[^a-z0-9_.]/", "", $st);
        $st = trim($st, '_');

        $prev_st = '';
        do {
            $prev_st = $st;
            $st = preg_replace("/_[a-z0-9]_/", "_", $st);
        } while ($st != $prev_st);

        $st = preg_replace("/_{2,}/", "_", $st);
        return $st;
    }
    static function isNeedleInStr($needle, $fullStr)
    {
        $pos = strpos($fullStr, $needle);
        if ($pos !== false) {

            return true;
        }

        return false;
    }
    static function jsonToArray($json)
    {
        $out = json_decode($json, true);
        
        return $out;
    }
    static function arrayToJson($array)
    {
        $out = json_encode($array, JSON_UNESCAPED_UNICODE);

        return $out;
    }
 static public function isAbsoluteImageType($fullname)
    {
        try {
            $isAccept = [IMAGETYPE_JPEG,IMAGETYPE_PNG, IMAGETYPE_TIFF_II, IMAGETYPE_BMP, IMAGETYPE_TIFF_MM];
            if(in_array(exif_imagetype($fullname), $isAccept)){

                return true;
            }

            return false;
        } catch (\Exception $e) {

            return false;
        }
    }

    /**
     * проверка ссылки на картинку
     * @param $link
     * @return bool
     */
    public static function checkHTTPLink($link)
    {
        if($link){
            try{
                $headerHttp = get_headers($link);
            }catch (Exception $e){

                return false;
            }
            $status = $headerHttp ;
            if((in_array("HTTP/1.1 200 OK", $status) or in_array("HTTP/1.0 200 OK", $status)) and !in_array("HTTP/1.1 301 Moved Permanently", $status)){

                return true;
            }
        }

        return false;
    }

    /**
     * проверка ссылки на ютуб
     * @param $link
     * @return bool
     */
    public static function checkYoutubeLink($link)
    {
        if($link){
            $link = 'https://www.youtube.com/oembed?format=json&url='.$link;
            try{
                $headerHttp = get_headers($link);
            }catch (Exception $e){

                return false;
            }
            if(is_array($headerHttp)) preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/', $headerHttp[0]);
            $errFlag = (strpos($headerHttp[0], '200') ? '200' : '404');
            if($errFlag==200){
                return true;
            }
        }

        return false;
    }
}
