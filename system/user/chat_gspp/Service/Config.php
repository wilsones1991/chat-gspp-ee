<?php

namespace EricWilson\ChatGSPP\Service;

class Config
{

    public $chatGsppEnabled;
    public $chatGsppEndpoint;

    public  function __construct()
    {
        $this->chatGsppEnabled = in_array(ee()->config->item('chat_gspp_enabled'), ['yes', 'y']);
    }
}