<?php
namespace Backoffice\Core;

class I18n
{
    public static function init($lang = 'ca_ES_valencia')
    {
        $domain = 'messages';
        putenv("LANG=$lang");
        setlocale(LC_ALL, $lang);
        bindtextdomain($domain, '/locale');
        textdomain($domain);
    }
}
