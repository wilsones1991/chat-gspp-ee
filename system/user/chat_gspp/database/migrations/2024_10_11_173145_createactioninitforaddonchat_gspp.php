<?php

use ExpressionEngine\Service\Migration\Migration;

class CreateactioninitforaddonchatGspp extends Migration
{
    /**
     * Execute the migration
     * @return void
     */
    public function up()
    {
        ee('Model')->make('Action', [
            'class' => 'Chat_gspp',
            'method' => 'Init',
            'csrf_exempt' => false,
        ])->save();
    }

    /**
     * Rollback the migration
     * @return void
     */
    public function down()
    {
        ee('Model')->get('Action')
            ->filter('class', 'Chat_gspp')
            ->filter('method', 'Init')
            ->delete();
    }
}
