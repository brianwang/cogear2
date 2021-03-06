<?php

/**
 * Класс Url.
 *
 * Построитель ссылок.
 *
 * @author		Беляев Дмитрий <admin@cogear.ru>
 * @copyright		Copyright (c) 2010, Беляев Дмитрий
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 */
class Url {

    const SECURE = 's';

    /**
     * Построение ссылки
     *
     * @param	string	$url
     * @param	boolean	$absolute_flag
     * @param	string	$protocol
     * @return	string
     */
    public static function link($url = '', $absolute_flag = FALSE, $protocol = 'http') {
        $link = '';
        $cogear = getInstance();
        if (!$url) {
            return $protocol . '://' . SITE_URL . '/';
        } else if (TRUE === $url) {
            return l() . cogear()->router->getUri();
        }
        $url = parse_url($url);

        if ($absolute_flag) {
            $link .= $protocol . '://';
            $link .= SITE_URL;
        } elseif (defined('FOLDER')) {
            $link .= '/' . FOLDER;
        }
        isset($url['host']) && $link = $protocol . '://' . $url['host'];
        isset($url['path']) && $link .= '/' . ltrim($url['path'], '/');
        isset($url['query']) && $link .= '?' . $url['query'];
        isset($url['fragment']) && $link .= '#' . $url['fragment'];
        event('link', $link);
        if (cogear()->input->get('splash') === '') {
            $link .= e();
        }
        return $link;
    }

    /**
     * Построение безопасной ссылки
     *
     * @param string $url
     * @param boolean $absolute_flag
     * @param string $protocol
     * @return string
     */
    public static function slink($url = '', $absolute_flag = FALSE, $protocol = 'http') {
        $link = self::link($url, $absolute_flag, $protocol);
        $link .= '?' . self::SECURE . '=' . cogear()->secure->salt();
        return $link;
    }

    /**
     * Привод строки к виду url
     *
     * Transform text to url-compatible snippet
     *
     * @param   string  $text
     * @param   string  $separator
     * @param   int     $limit
     * @return  string
     */
    public static function name($text, $separator = '-', $limit = 40) {
        $text = cogear()->lang->transliterate($text);
        $text = preg_replace("/[^a-z0-9\_\-.]+/mi", "", $text);
        $text = preg_replace('#[\-]+#i', $separator, $text);
        $text = strtolower($text);

        if (strlen($text) > $limit) {
            $text = substr($text, 0, $limit);
            if (($temp_max = strrpos($text, '-')))
                $text = substr($text, 0, $temp_max);
        }

        return $text;
    }

    /**
     * Превращение пути к url
     *
     * @param string $path
     * @param string $replace_path
     * @param boolean $link
     * @return string
     */
    public static function toUri($path, $replace_path = NULL, $link = TRUE) {
        $replace_path OR $replace_path = ROOT;
        $path = str_replace(
                array($replace_path, DS), array('', '/'), $path);
        return $link ? self::link($path) : $path;
    }

    /**
     * Расширение существующего $_GET-запроса
     *
     * @param array $data
     * @return  string
     */
    public static function extendQuery($data = array(), $value = NULL) {
        if (!is_array($data)) {
            $data = array($data => $value);
        }
        if (!empty($_GET))
            $data = array_merge($_GET, $data);
        if ($data) {
            if ($q = http_build_query($data)) {
                if ($q[0] != '?') {
                    $q = '?' . $q;
                }
                return $q;
            }
        }
        return '';
    }

}

function l($url = '', $absolute_flag = FALSE, $protocol = 'http') {
    return Url::link($url, $absolute_flag, $protocol);
}

function s($url = '', $absolute_flag = FALSE, $protocol = 'http') {
    return Url::slink($url, $absolute_flag, $protocol);
}

function e($data = array(), $value = NULL) {
    return Url::extendQuery($data, $value);
}
function curl($options = array()){
    return new cURL($options);
}