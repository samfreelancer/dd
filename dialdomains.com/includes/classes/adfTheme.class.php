<?php
class adfTheme {
    private static
      /*
       * Stores the paths for various template types
       */
      $data        = array(),
      /**
       * Stores the path to the template director
       */
      $basePath    = null,
      /**
       * The directory containing the templates
       */
      $templateDir = '/themes/',
      /**
       * Stores an instance of this class
       */
      $instance    = false;

    /**
     *
     * Constructor
     */
    private function __construct() {
        self::$instance = $this;
        self::$basePath = SITE_BASE_PATH . self::$templateDir;

        self::$data = array(
            'template'    => '',
            'form'        => 'blocks/forms/',
            'loop'        => 'blocks/loops/',
            'navigation'  => 'blocks/navigation/',
            'other'       => 'blocks/other/',
            'sidebar'     => 'blocks/sidebars/',
            'table'       => 'blocks/tables/',
            'templates'   => 'templates/',
            'page'        => 'pages/',
        );

        $this->setTemplate(THEME_DEFAULT);
    }

    /**
     *
     * Retrieves an instance of this class.  Shouldn't be needed.
     *
     * @return object An instance of this class.
     */
    public static function getInstance() {
        return (self::$instance === false) ? new adfTheme() : self::$instance;
    }

    /**
     *
     * Sets the folder name containing the template files
     *
     * @param string $template The template name
     */
    public function setTemplate($template) {
        self::$data['template'] = "$template/";

        adfMainNavigation::clearAllMenuItems();

        if (file_exists(self::$basePath . self::$data['template'] . 'navprofile.php')) {
            include self::$basePath . self::$data['template'] . 'navprofile.php';
        }
    }

    /**
     *
     * Returns the full path to a page based on the selected template
     *
     * @param string $location The folder and filename of the page
     * @return string The full path to the template page
     */
    public function page($location) {
        return self::$basePath . self::$data['template'] . self::$data['page'] . $location;
    }

    /**
     *
     * Returns the full path to a block based on the selected template
     *
     * @param $type string Must be one of form, loop, navigation, other, sidebar or table
     * @param $name string The name of the block
     * @return string The full path to the block
     */
    public function block($type, $name) {
        if (!array_key_exists($type, self::$data)) {
            trigger_error('Cannot include block, invalid type.', E_USER_WARNING);
        }

        return self::$basePath . self::$data['template'] . self::$data[$type] . $name;
    }

    /**
     *
     * Returns the full path to the header for the selected theme
     *
     * @param $name string The name of the header file
     * @return string The full path to the file
     */
    public function header($name = 'header.php') {
        return self::$basePath . self::$data['template'] . $name;
    }

    /**
     *
     * Returns the full path to the footer for the selected theme
     *
     * @param $name string The name of the footer file
     * @return string The full path to the file
     */
    public function footer($name = 'footer.php') {
        return self::$basePath . self::$data['template'] . $name;
    }

    /**
     *
     * Returns the path to the selected template
     *
     * @return string The path to the selected template
     */
    public function path() {
        return self::$templateDir . self::$data['template'];
    }

    public static function getThemes($exclude = array()) {
        $list = array();

        foreach ((array) scandir(adfRegistry::get('THEME_PATH')) as $theme) {
            if (!in_array($theme, array('.', '..')) && !in_array($theme, $exclude) && is_dir(adfRegistry::get('THEME_PATH') . $theme)) {
                $list[$theme] = ucwords($theme);
            }
        }

        ksort($list);

        return $list;
    }
}