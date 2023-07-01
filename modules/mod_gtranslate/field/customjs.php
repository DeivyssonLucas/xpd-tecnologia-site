<?php
/**
* @version   $Id$
* @package   GTranslate
* @copyright Copyright (C) 2008-2023 GTranslate Inc. All rights reserved.
* @license   GNU/GPL v3 http://www.gnu.org/licenses/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

use Joomla\CMS\Uri\Uri;

class JFormFieldCustomJS extends JFormField {

    protected $type = 'custom_js';

    public function getLabel() {
        return '';
    }

    public function getValue() {
        return '';
    }

    public function getInput() {

        $uri = Uri::getInstance();
        $domain = addslashes($uri->getHost());

        $custom_js = <<<EOM
<script>
// make sure original language is always on
jQuery('#jform_params_language,input[name="jform[params][languages][]"]').change(function() {
    var source_lang = jQuery('#jform_params_language').val();
    jQuery('input[name="jform[params][languages][]"][value="'+source_lang+'"]').prop('checked', true);
});

// alt flags checkbox control
var alt_flags_wrapper = jQuery('fieldset#jform_params_alt_flags');
alt_flags_wrapper.find('input[value="usa"],input[value="canada"]').attr('data-lang-group', 'en');
alt_flags_wrapper.find('input[value="brazil"]').attr('data-lang-group', 'pt');
alt_flags_wrapper.find('input[value="mexico"],input[value="argentina"],input[value="colombia"]').attr('data-lang-group', 'es');
alt_flags_wrapper.find('input[value="quebec"]').attr('data-lang-group', 'fr');

alt_flags_wrapper.find('input[name="jform[params][alt_flags][]"]').change(function() {
    if(jQuery(this).prop('checked')) {
        var lang_group = jQuery(this).attr('data-lang-group');

        // uncheck other items from lang group
        alt_flags_wrapper.find('input[name="jform[params][alt_flags][]"][data-lang-group="'+lang_group+'"]').prop('checked', false);
        jQuery(this).prop('checked', true);
    }
});

// custom domain sync
jQuery('input[name="jform[params][custom_domains]"]').change(function() {
    if(jQuery(this).prop('checked')) {
        jQuery.ajax({
            url: 'https://tdns.gtranslate.net/tdn-bin/load-custom-domains',
            type: 'GET',
            dataType: 'json',
            async: false,
            headers: {"X-GT-Domain": "$domain"},
            success: function(data) {
                if(data.err) {
                    console.log('Custom domains sync error: ', data.err);
                    return;
                }

                jQuery('#jform_params_custom_domains_config').val(JSON.stringify(data));
            },
            error: function(e) {
                alert('Cannot synchronize custom domains with GTranslate dashboard');
                console.log('Cannot sync data: ', e);
            }
        });
    }
});
</script>
EOM;
        return $custom_js;
    }
}