<?php
class adfTemplate {
    public function __construct() {
        $this->theme = adfTheme::getInstance();
    }

    public static function getTemplatesByTheme($theme = THEME_DEFAULT) {
        $path       = adfRegistry::get('THEME_PATH') . $theme . '/templates/';
        $files      = array();
        $templates  = array();

        foreach ((array) scandir($path) as $file) {
            if (!in_array($file, array('.', '..')) && is_file($path . $file)) {
                $files[] = $file;
            }
        }

        if (count($files) < 1) {
            return false;
        }

        foreach ($files as $file) {
            $content = file_get_contents($path . $file);

            if (preg_match('#Template[\\t\\s]{1,5}Name:[\\t\\s]{0,5}(.*)#', $content, $match)) {
                $templates[$file] = $match[1];
            }
        }

        ksort($templates);

        return $templates;
    }
}