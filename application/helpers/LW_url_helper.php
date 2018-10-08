<?php
if ( ! function_exists('site_url'))
{
    /**
     * Site URL
     *
     *
     * @param	string	$uri
     * @param	string	$protocol
     * @return	string
     */
    function site_url($uri = '', $protocol = NULL)
    {

        if(!IS_REWRITE){
            return get_instance()->config->site_url($uri, $protocol);
        }
        $strUrl = get_instance()->config->site_url($uri, $protocol);
        return str_replace('/index.php', '', $strUrl);
    }
}
