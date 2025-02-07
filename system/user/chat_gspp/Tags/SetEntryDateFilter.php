<?php

namespace EricWilson\ChatGSPP\Tags;

use ExpressionEngine\Service\Addon\Controllers\Tag\AbstractRoute;

class SetEntryDateFilter extends AbstractRoute
{
    // Example tag: {exp:chat_gspp:set_entry_date_filter}
    public function process()
    {
        
        $lastUpdatedDate = ee('chat_gspp:LastUpdatedDate');
        
        $filter = ee()->TMPL->fetch_param('filter');

        $lastUpdatedDate->setFilter($filter);
        
        return "";
    }
}
