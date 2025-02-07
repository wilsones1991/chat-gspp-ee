<?php

namespace EricWilson\ChatGSPP\Tags;

use ExpressionEngine\Service\Addon\Controllers\Tag\AbstractRoute;

class IncludeEntryDate extends AbstractRoute
{
    // Example tag: {exp:chat_gspp:include_entry_date}
    public function process()
    {
        
        $timestamp = trim(ee()->TMPL->tagdata);
        
        if (!$this->validateTimestamp($timestamp)) {
            return '';
        }

        file_put_contents('test_updated_date.log', 'Did we do it: '.$timestamp.PHP_EOL, FILE_APPEND);
        $lastUpdatedDate = ee('chat_gspp:LastUpdatedDate');
        $lastUpdatedDate->addEditDate($timestamp, 'include');
        
        return '';
    }

    // Method to validate timestamp
    private function validateTimestamp($timestamp)
    {
        return preg_match('/^\d{10}$/', $timestamp);
    }
}
