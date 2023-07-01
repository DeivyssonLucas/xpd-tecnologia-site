<?php
/**
* @version   $Id$
* @package   GTranslate
* @copyright Copyright (C) 2008-2023 GTranslate Inc. All rights reserved.
* @license   GNU/GPL v3 http://www.gnu.org/licenses/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;

$lang_array = array('en'=>'English','ar'=>'Arabic','bg'=>'Bulgarian','zh-CN'=>'Chinese (Simplified)','zh-TW'=>'Chinese (Traditional)','hr'=>'Croatian','cs'=>'Czech','da'=>'Danish','nl'=>'Dutch','fi'=>'Finnish','fr'=>'French','de'=>'German','el'=>'Greek','hi'=>'Hindi','it'=>'Italian','ja'=>'Japanese','ko'=>'Korean','no'=>'Norwegian','pl'=>'Polish','pt'=>'Portuguese','ro'=>'Romanian','ru'=>'Russian','es'=>'Spanish','sv'=>'Swedish','ca'=>'Catalan','tl'=>'Filipino','iw'=>'Hebrew','id'=>'Indonesian','lv'=>'Latvian','lt'=>'Lithuanian','sr'=>'Serbian','sk'=>'Slovak','sl'=>'Slovenian','uk'=>'Ukrainian','vi'=>'Vietnamese','sq'=>'Albanian','et'=>'Estonian','gl'=>'Galician','hu'=>'Hungarian','mt'=>'Maltese','th'=>'Thai','tr'=>'Turkish','fa'=>'Persian','af'=>'Afrikaans','ms'=>'Malay','sw'=>'Swahili','ga'=>'Irish','cy'=>'Welsh','be'=>'Belarusian','is'=>'Icelandic','mk'=>'Macedonian','yi'=>'Yiddish','hy'=>'Armenian','az'=>'Azerbaijani','eu'=>'Basque','ka'=>'Georgian','ht'=>'Haitian Creole','ur'=>'Urdu','bn' => 'Bengali','bs' => 'Bosnian','ceb' => 'Cebuano','eo' => 'Esperanto','gu' => 'Gujarati','ha' => 'Hausa','hmn' => 'Hmong','ig' => 'Igbo','jw' => 'Javanese','kn' => 'Kannada','km' => 'Khmer','lo' => 'Lao','la' => 'Latin','mi' => 'Maori','mr' => 'Marathi','mn' => 'Mongolian','ne' => 'Nepali','pa' => 'Punjabi','so' => 'Somali','ta' => 'Tamil','te' => 'Telugu','yo' => 'Yoruba','zu' => 'Zulu','my' => 'Myanmar (Burmese)','ny' => 'Chichewa','kk' => 'Kazakh','mg' => 'Malagasy','ml' => 'Malayalam','si' => 'Sinhala','st' => 'Sesotho','su' => 'Sudanese','tg' => 'Tajik','uz' => 'Uzbek','am' => 'Amharic','co' => 'Corsican','haw' => 'Hawaiian','ku' => 'Kurdish (Kurmanji)','ky' => 'Kyrgyz','lb' => 'Luxembourgish','ps' => 'Pashto','sm' => 'Samoan','gd' => 'Scottish Gaelic','sn' => 'Shona','sd' => 'Sindhi','fy' => 'Frisian','xh' => 'Xhosa');

$settings = $params->toArray();

// Move the default language to the first position
$lang_array = array_merge(array($settings['language'] => $lang_array[$settings['language']]), $lang_array);

$gt_settings = array(
    'default_language' => $settings['language'],
    'languages' => $settings['languages'],
    'url_structure' => $settings['url_structure'],
    'wrapper_selector' => $settings['wrapper_selector'],
    'globe_size' => $settings['globe_size'],
    'flag_size' => $settings['flag_size'],
    'flag_style' => $settings['flag_style'],
    'custom_domains' => $settings['custom_domains'] ? $settings['custom_domains_config'] : null,
    'float_switcher_open_direction' => $settings['float_switcher_open_direction'],
    'switcher_open_direction' => $settings['switcher_open_direction'],
    'native_language_names' => $settings['native_language_names'],
    'add_new_line' => $settings['add_new_line'],
    'select_language_label' => $settings['select_language_label'],
    'detect_browser_language' => $settings['detect_browser_language'],
    'custom_css' => $settings['custom_css'],
);

$alt_flags = array();
$raw_alt_flags = $settings['alt_flags']; // example raw_alt_flags: ['usa', 'mexico']
foreach($raw_alt_flags as $country) {
    if($country == 'usa' or $country == 'canada')
        $alt_flags['en'] = $country;
    elseif($country == 'brazil')
        $alt_flags['pt'] = $country;
    elseif($country == 'mexico' or $country == 'argentina' or $country == 'colombia')
        $alt_flags['es'] = $country;
    elseif($country == 'quebec')
        $alt_flags['fr'] = $country;
}
$gt_settings['alt_flags'] = $alt_flags;

$float_position = $settings['float_position'];
if($float_position == 'inline')
    $switcher_horizontal_position = $switcher_vertical_position = 'inline';
else
    list($switcher_vertical_position, $switcher_horizontal_position) = explode('-', $float_position);
$gt_settings['switcher_horizontal_position'] = $switcher_horizontal_position;
$gt_settings['switcher_vertical_position'] = $switcher_vertical_position;

$position = $settings['position'];
if($position == 'inline')
    $horizontal_position = $vertical_position = 'inline';
else
    list($vertical_position, $horizontal_position) = explode('-', $position);
$gt_settings['horizontal_position'] = $horizontal_position;
$gt_settings['vertical_position'] = $vertical_position;

$color_scheme = $settings['color_scheme'];
if($settings['look'] == 'dropdown_with_flags' and $color_scheme == 'dark') {
    $gt_settings['switcher_text_color'] = '#f7f7f7';
    $gt_settings['switcher_arrow_color'] = '#f2f2f2';
    $gt_settings['switcher_border_color'] = '#161616';
    $gt_settings['switcher_background_color'] = '#303030';
    $gt_settings['switcher_background_shadow_color'] = '#474747';
    $gt_settings['switcher_background_hover_color'] = '#3a3a3a';
    $gt_settings['dropdown_text_color'] = '#eaeaea';
    $gt_settings['dropdown_hover_color'] = '#748393';
    $gt_settings['dropdown_background_color'] = '#474747';
}

switch($settings['look']) {

    case 'float': {
        $widget_code = '';
        if($gt_settings['wrapper_selector'] == '.gtranslate_wrapper') {
            $gt_settings['wrapper_selector'] = '#gt-wrapper-' . $module->id;
            $widget_code .= '<div class="gtranslate_wrapper" id="gt-wrapper-' . $module->id . '"></div>';
        }

        if(!empty($gt_settings['custom_domains']))
            $gt_settings['custom_domains'] = json_decode($gt_settings['custom_domains']);

        $uri = Uri::getInstance();
        $document = Factory::getDocument();

        $orig_url = $uri->toString(array('path', 'query'));
        $orig_domain = $uri->getHost();

        if($settings['enable_cdn']) {
            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript('https://cdn.gtranslate.net/widgets/latest/float.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        } else {
            $base_path = JURI::root() . 'media/mod_gtranslate';
            $gt_settings['flags_location'] = $base_path . '/flags/';

            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript($base_path.'/js/float.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        }

        echo $widget_code;
    }; break;

    case 'dropdown_with_flags': {
        $widget_code = '';
        if($gt_settings['wrapper_selector'] == '.gtranslate_wrapper') {
            $gt_settings['wrapper_selector'] = '#gt-wrapper-' . $module->id;
            $widget_code .= '<div class="gtranslate_wrapper" id="gt-wrapper-' . $module->id . '"></div>';
        }

        if(!empty($gt_settings['custom_domains']))
            $gt_settings['custom_domains'] = json_decode($gt_settings['custom_domains']);

        $uri = Uri::getInstance();
        $document = Factory::getDocument();

        $orig_url = $uri->toString(array('path', 'query'));
        $orig_domain = $uri->getHost();

        if($settings['enable_cdn']) {
            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript('https://cdn.gtranslate.net/widgets/latest/dwf.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        } else {
            $base_path = JURI::root() . 'media/mod_gtranslate';
            $gt_settings['flags_location'] = $base_path . '/flags/';

            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript($base_path.'/js/dwf.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        }

        echo $widget_code;
    }; break;

    case 'both': // same as flags_dropdown
    case 'flags_dropdown': {
        $widget_code = '';
        if($gt_settings['wrapper_selector'] == '.gtranslate_wrapper') {
            $gt_settings['wrapper_selector'] = '#gt-wrapper-' . $module->id;
            $widget_code .= '<div class="gtranslate_wrapper" id="gt-wrapper-' . $module->id . '"></div>';
        }

        if(!empty($gt_settings['custom_domains']))
            $gt_settings['custom_domains'] = json_decode($gt_settings['custom_domains']);

        $uri = Uri::getInstance();
        $document = Factory::getDocument();

        $orig_url = $uri->toString(array('path', 'query'));
        $orig_domain = $uri->getHost();

        if($settings['enable_cdn']) {
            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript('https://cdn.gtranslate.net/widgets/latest/fd.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        } else {
            $base_path = JURI::root() . 'media/mod_gtranslate';
            $gt_settings['flags_location'] = $base_path . '/flags/';

            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript($base_path.'/js/fd.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        }

        echo $widget_code;
    }; break;

    case 'flags': {
        $widget_code = '';
        if($gt_settings['wrapper_selector'] == '.gtranslate_wrapper') {
            $gt_settings['wrapper_selector'] = '#gt-wrapper-' . $module->id;
            $widget_code .= '<div class="gtranslate_wrapper" id="gt-wrapper-' . $module->id . '"></div>';
        }

        if(!empty($gt_settings['custom_domains']))
            $gt_settings['custom_domains'] = json_decode($gt_settings['custom_domains']);

        $uri = Uri::getInstance();
        $document = Factory::getDocument();

        $orig_url = $uri->toString(array('path', 'query'));
        $orig_domain = $uri->getHost();

        if($settings['enable_cdn']) {
            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript('https://cdn.gtranslate.net/widgets/latest/flags.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        } else {
            $base_path = JURI::root() . 'media/mod_gtranslate';
            $gt_settings['flags_location'] = $base_path . '/flags/';

            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript($base_path.'/js/flags.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        }

        echo $widget_code;
    }; break;

    case 'dropdown': {
        $widget_code = '';
        if($gt_settings['wrapper_selector'] == '.gtranslate_wrapper') {
            $gt_settings['wrapper_selector'] = '#gt-wrapper-' . $module->id;
            $widget_code .= '<div class="gtranslate_wrapper" id="gt-wrapper-' . $module->id . '"></div>';
        }

        if(!empty($gt_settings['custom_domains']))
            $gt_settings['custom_domains'] = json_decode($gt_settings['custom_domains']);

        $uri = Uri::getInstance();
        $document = Factory::getDocument();

        $orig_url = $uri->toString(array('path', 'query'));
        $orig_domain = $uri->getHost();

        if($settings['enable_cdn']) {
            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript('https://cdn.gtranslate.net/widgets/latest/dropdown.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        } else {
            $base_path = JURI::root() . 'media/mod_gtranslate';

            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript($base_path.'/js/dropdown.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        }

        echo $widget_code;
    }; break;

    case 'flags_name': {
        $widget_code = '';
        if($gt_settings['wrapper_selector'] == '.gtranslate_wrapper') {
            $gt_settings['wrapper_selector'] = '#gt-wrapper-' . $module->id;
            $widget_code .= '<div class="gtranslate_wrapper" id="gt-wrapper-' . $module->id . '"></div>';
        }

        if(!empty($gt_settings['custom_domains']))
            $gt_settings['custom_domains'] = json_decode($gt_settings['custom_domains']);

        $uri = Uri::getInstance();
        $document = Factory::getDocument();

        $orig_url = $uri->toString(array('path', 'query'));
        $orig_domain = $uri->getHost();

        if($settings['enable_cdn']) {
            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript('https://cdn.gtranslate.net/widgets/latest/fn.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        } else {
            $base_path = JURI::root() . 'media/mod_gtranslate';
            $gt_settings['flags_location'] = $base_path . '/flags/';

            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript($base_path.'/js/fn.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        }

        echo $widget_code;
    }; break;

    case 'flags_code': {
        $widget_code = '';
        if($gt_settings['wrapper_selector'] == '.gtranslate_wrapper') {
            $gt_settings['wrapper_selector'] = '#gt-wrapper-' . $module->id;
            $widget_code .= '<div class="gtranslate_wrapper" id="gt-wrapper-' . $module->id . '"></div>';
        }

        if(!empty($gt_settings['custom_domains']))
            $gt_settings['custom_domains'] = json_decode($gt_settings['custom_domains']);

        $uri = Uri::getInstance();
        $document = Factory::getDocument();

        $orig_url = $uri->toString(array('path', 'query'));
        $orig_domain = $uri->getHost();

        if($settings['enable_cdn']) {
            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript('https://cdn.gtranslate.net/widgets/latest/fc.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        } else {
            $base_path = JURI::root() . 'media/mod_gtranslate';
            $gt_settings['flags_location'] = $base_path . '/flags/';

            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript($base_path.'/js/fc.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        }

        echo $widget_code;
    }; break;

    case 'lang_names': {
        $widget_code = '';
        if($gt_settings['wrapper_selector'] == '.gtranslate_wrapper') {
            $gt_settings['wrapper_selector'] = '#gt-wrapper-' . $module->id;
            $widget_code .= '<div class="gtranslate_wrapper" id="gt-wrapper-' . $module->id . '"></div>';
        }

        if(!empty($gt_settings['custom_domains']))
            $gt_settings['custom_domains'] = json_decode($gt_settings['custom_domains']);

        $uri = Uri::getInstance();
        $document = Factory::getDocument();

        $orig_url = $uri->toString(array('path', 'query'));
        $orig_domain = $uri->getHost();

        if($settings['enable_cdn']) {
            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript('https://cdn.gtranslate.net/widgets/latest/ln.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        } else {
            $base_path = JURI::root() . 'media/mod_gtranslate';

            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript($base_path.'/js/ln.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        }

        echo $widget_code;
    }; break;

    case 'lang_codes': {
        $widget_code = '';
        if($gt_settings['wrapper_selector'] == '.gtranslate_wrapper') {
            $gt_settings['wrapper_selector'] = '#gt-wrapper-' . $module->id;
            $widget_code .= '<div class="gtranslate_wrapper" id="gt-wrapper-' . $module->id . '"></div>';
        }

        if(!empty($gt_settings['custom_domains']))
            $gt_settings['custom_domains'] = json_decode($gt_settings['custom_domains']);

        $uri = Uri::getInstance();
        $document = Factory::getDocument();

        $orig_url = $uri->toString(array('path', 'query'));
        $orig_domain = $uri->getHost();

        if($settings['enable_cdn']) {
            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript('https://cdn.gtranslate.net/widgets/latest/lc.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        } else {
            $base_path = JURI::root() . 'media/mod_gtranslate';

            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript($base_path.'/js/lc.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        }

        echo $widget_code;
    }; break;

    case 'globe': {
        $widget_code = '';
        if($gt_settings['wrapper_selector'] == '.gtranslate_wrapper') {
            $gt_settings['wrapper_selector'] = '#gt-wrapper-' . $module->id;
            $widget_code .= '<div class="gtranslate_wrapper" id="gt-wrapper-' . $module->id . '"></div>';
        }

        if(!empty($gt_settings['custom_domains']))
            $gt_settings['custom_domains'] = json_decode($gt_settings['custom_domains']);

        $uri = Uri::getInstance();
        $document = Factory::getDocument();

        $orig_url = $uri->toString(array('path', 'query'));
        $orig_domain = $uri->getHost();

        if($settings['enable_cdn']) {
            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript('https://cdn.gtranslate.net/widgets/latest/globe.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        } else {
            $base_path = JURI::root() . 'media/mod_gtranslate';
            $gt_settings['flags_location'] = $base_path . '/flags/svg/';

            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript($base_path.'/js/globe.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        }

        echo $widget_code;
    }; break;

    case 'popup': {
        $widget_code = '';
        if($gt_settings['wrapper_selector'] == '.gtranslate_wrapper') {
            $gt_settings['wrapper_selector'] = '#gt-wrapper-' . $module->id;
            $widget_code .= '<div class="gtranslate_wrapper" id="gt-wrapper-' . $module->id . '"></div>';
        }

        if(!empty($gt_settings['custom_domains']))
            $gt_settings['custom_domains'] = json_decode($gt_settings['custom_domains']);

        $uri = Uri::getInstance();
        $document = Factory::getDocument();

        $orig_url = $uri->toString(array('path', 'query'));
        $orig_domain = $uri->getHost();

        if($settings['enable_cdn']) {
            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript('https://cdn.gtranslate.net/widgets/latest/popup.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        } else {
            $base_path = JURI::root() . 'media/mod_gtranslate';
            $gt_settings['flags_location'] = $base_path . '/flags/';

            $document->addScriptDeclaration("window.gtranslateSettings = window.gtranslateSettings || {};window.gtranslateSettings['" . $module->id . "'] = " . json_encode($gt_settings) . ";");
            $document->addScript($base_path.'/js/popup.js', array(), array('data-gt-orig-url' => $orig_url, 'data-gt-orig-domain' => $orig_domain, 'data-gt-widget-id' => $module->id, 'defer' => 'defer'));
        }

        echo $widget_code;
    }; break;

    default: echo 'unknown widget look'; break;
}
