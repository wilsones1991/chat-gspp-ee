<?php

namespace EricWilson\ChatGSPP\Extensions;

use ExpressionEngine\Service\Addon\Controllers\Extension\AbstractRoute;

class ChannelEntriesTagdataEnd extends AbstractRoute
{
    private const ENTRY_DATE_FILTER_PATTERN = '/\{chat_gspp:entry_date_filter:([^\}]+)\}/';

    public function process($tagdata, $row) {
        
        // play nice with other extensions on this hook
        if (isset(ee()->extensions->last_call) && ee()->extensions->last_call)
        {
            $tagdata = ee()->extensions->last_call;
        }
        
        $lastUpdatedDate = ee('chat_gspp:LastUpdatedDate');
        $lastUpdatedDate->addEditDate($row['edit_date'], $this->extractEntryDateFilter($tagdata));

        // Strip the EE comment from the tagdata
        $tagdata = $this->stripEntryDateFilterComment($tagdata);

        return $tagdata;
    }

    private function extractEntryDateFilter($tagdata) {
        // Use the class constant for the regular expression pattern
        $pattern = self::ENTRY_DATE_FILTER_PATTERN;

        if (preg_match($pattern, $tagdata, $matches)) {

            return $matches[1];
        }

        return null;
    }

    private function stripEntryDateFilterComment($tagdata) {

        $pattern = self::ENTRY_DATE_FILTER_PATTERN;

        return preg_replace($pattern, '', $tagdata);
    }
}
