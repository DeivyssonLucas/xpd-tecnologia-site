<?php
/**
* @version   $Id$
* @package   GTranslate
* @copyright Copyright (C) 2008-2023 GTranslate Inc. All rights reserved.
* @license   GNU/GPL v3 http://www.gnu.org/licenses/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

class modGTranslateHelper {
    public static function getParams(&$params) {
        $params->def('look', 'float');
        $params->def('flag_size', 32);
        $params->def('flag_style', '2d');
        $params->def('globe_size', 60);
        $params->def('language', 'en');
        $params->def('url_structure', 'none');
        $params->def('native_language_names', 1);
        $params->def('detect_browser_language', 0);
        $params->def('enable_cdn', 1);
        $params->def('add_new_line', 1);
        $params->def('select_language_label', 'Select Language');
        $params->def('color_scheme', 'light');
        $params->def('alt_flags', array());
        $params->def('wrapper_selector', '.gtranslate_wrapper');
        $params->def('float_position', 'bottom-left');
        $params->def('float_switcher_open_direction', 'top');
        $params->def('position', 'inline');
        $params->def('switcher_open_direction', 'bottom');
        $params->def('custom_domains', 0);
        $params->def('custom_domains_config', '');
        $params->def('custom_css', '');
        $params->def('languages', array('en', 'es', 'de', 'it', 'fr'));

        return $params;
    }
}