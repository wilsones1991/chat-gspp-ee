<?php

namespace EricWilson\ChatGSPP\Tags;

use ExpressionEngine\Service\Addon\Controllers\Tag\AbstractRoute;

class PageUpdatedDate extends AbstractRoute
{
    // Example tag: {exp:chat_gspp:page_updated_date}
    public function process()
    {
        return '<meta name="chat_gspp:last_updated_date" content="" />';

    }
}
