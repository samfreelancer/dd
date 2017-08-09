<?php
class adfView {
    protected static
        $jsIncludes     = array(),
        $cssIncludes    = array();

    public static function addJS($path, $order = 50) {
        if (!isset(self::$jsIncludes[$order])) {
            self::$jsIncludes[$order] = array();
        }

        self::$jsIncludes[$order][] = $path;
    }

    public static function getJS() {
        ksort(self::$jsIncludes);
        $tracker = array();

        foreach (self::$jsIncludes as $scripts) {
            foreach ($scripts as $script) {
                if (in_array($script, $tracker)) {
                    continue;
                }

                echo '<script type="text/javascript" src="' . $script . '"></script>' . "\n";
                $tracker[] = $script;
            }
        }
    }

    public static function addCSS($path, $order = 50, $media = "") {
        if (!isset(self::$cssIncludes[$order])) {
            self::$cssIncludes[$order] = array();
        }

        self::$cssIncludes[$order][] = array('path' => $path, 'media' => $media);
    }

    public static function getCSS() {
        ksort(self::$cssIncludes);
        $tracker = array();

        foreach (self::$cssIncludes as $files) {
            foreach ($files as $file) {
                if (in_array($file['path'], $tracker)) {
                    continue;
                }

                if ($file['media'] != '') {
                    $media = ' media="' . $file['media'] . '" ';
                } else {
                    $media = '';
                }

                echo '<link href="' . $file['path'] . '" rel="stylesheet" type="text/css"' . $media .'/>';
                $tracker[] = $file['path'];
            }
        }
    }
}