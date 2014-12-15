<?php
/**
 * Quick server-side way of grabbing +1
 */
$url = isset($_GET['url']) ?  $_GET['url'] : null;

if($url !== null)
{
    $raw_share_button = file_get_contents('https://plusone.google.com/_/+1/fastbutton?url=' . urlencode($url));

    if($raw_share_button)
    {
        $share_count = array();
        /**
         * http://stackoverflow.com/questions/15367687/how-to-get-the-1-count-in-google-plus-using-any-api
         * http://stackoverflow.com/questions/21524077/getting-google-1-page-shares-via-ajax-hidden-api
         */
        preg_match('/.*\.__SSR.*{c:\s*([0-9]*)\..*/', $raw_share_button, $share_count);

        if(sizeof($share_count) === 2)
            echo $share_count[1];
        else
            echo 0;
    }
    else
        echo 0;
}
else
    echo 0;