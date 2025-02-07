<?php

return [
    'name'              => 'Chat GSPP',
    'description'       => 'Generates a chat interface for PrivateGPT instance.',
    'version'           => '1.0.0',
    'author'            => 'Eric Wilson',
    'author_url'        => 'https://eric-wilson.net',
    'namespace'         => 'EricWilson\ChatGSPP',
    'settings_exist'    => true,
    'services.singletons' => [
        'LastUpdatedDate' => function () {
            return new EricWilson\ChatGSPP\Service\LastUpdatedDate();
        },
        'Config' => function () {
            return new EricWilson\ChatGSPP\Service\Config();
        }
    ]
];
