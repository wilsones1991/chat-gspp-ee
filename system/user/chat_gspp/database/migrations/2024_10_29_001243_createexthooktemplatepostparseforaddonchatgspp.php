<?php

use ExpressionEngine\Service\Migration\Migration;

class CreateExtHookTemplatePostParseForAddonChatGspp extends Migration
{
    /**
     * Execute the migration
     * @return void
     */
    public function up()
    {
        $addon = ee('Addon')->get('chat_gspp');

        $ext = [
            'class' => $addon->getExtensionClass(),
            'method' => 'template_post_parse',
            'hook' => 'template_post_parse',
            'settings' => serialize([]),
            'priority' => 10,
            'version' => $addon->getVersion(),
            'enabled' => 'y'
        ];

        // If we didnt find a matching Extension, lets just insert it
        ee('Model')->make('Extension', $ext)->save();
    }

    /**
     * Rollback the migration
     * @return void
     */
    public function down()
    {
        $addon = ee('Addon')->get('chat_gspp');

        ee('Model')->get('Extension')
            ->filter('class', $addon->getExtensionClass())
            ->delete();
    }
}
