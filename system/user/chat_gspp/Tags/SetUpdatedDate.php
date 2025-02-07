<?php

namespace EricWilson\ChatGSPP\Tags;

use ExpressionEngine\Service\Addon\Controllers\Tag\AbstractRoute;

class SetUpdatedDate extends AbstractRoute
{
    // Example tag: {exp:chat_gspp:set_updated_date}
    public function process()
    {
        
        $timestamp = trim(ee()->TMPL->tagdata);

        if (!$this->validateTimestamp($timestamp)) {
            return '';
        }

        $lastUpdatedDate = ee('chat_gspp:LastUpdatedDate');
        $lastUpdatedDate->setLastUpdatedDate($timestamp);
        
        return "";
    }

        // Method to validate timestamp
        private function validateTimestamp($timestamp)
        {
            return preg_match('/^\d{10}$/', $timestamp);
        }
}
