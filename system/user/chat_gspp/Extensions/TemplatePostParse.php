<?php

namespace EricWilson\ChatGSPP\Extensions;

use ExpressionEngine\Service\Addon\Controllers\Extension\AbstractRoute;

class TemplatePostParse extends AbstractRoute
{
    public function process($final_template, $is_partial, $site_id, $currentTemplateInfo)
    {

        // play nice with other extensions on this hook
        if (isset(ee()->extensions->last_call) && ee()->extensions->last_call)
        {
            $final_template = ee()->extensions->last_call;
        }

        $lastUpdatedDate = ee('chat_gspp:LastUpdatedDate');
        $date = $lastUpdatedDate->getLastUpdatedDate();

        $metaTag = '<meta name="chat_gspp:last_updated_date" content="" />';

        // Find meta tag in final_template and replace with date
        $final_template = str_replace($metaTag, '<meta name="chat_gspp:last_updated_date" content="' . $date . '" />', $final_template);
        
        return $final_template;
    }
}
