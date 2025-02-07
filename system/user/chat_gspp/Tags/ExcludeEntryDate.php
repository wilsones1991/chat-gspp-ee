<?php

namespace EricWilson\ChatGSPP\Tags;

use ExpressionEngine\Service\Addon\Controllers\Tag\AbstractRoute;

class ExcludeEntryDate extends AbstractRoute
{
    // Example tag: {exp:chat_gspp:include_entry_date}
    public function process()
    {
        
        $timestamp = trim(ee()->TMPL->tagdata);
        
        if (!$this->validateTimestamp(ee()->TMPL->tagdata)) {
            return '';
        }

        $lastUpdatedDate = ee('chat_gspp:LastUpdatedDate');
        $lastUpdatedDate->addEditDate($timestamp, 'exclude');
        
        return '';
    }

    // Method to validate timestamp
    private function validateTimestamp($timestamp)
    {
        return preg_match('/^\d{10}$/', $timestamp);
    }
}
