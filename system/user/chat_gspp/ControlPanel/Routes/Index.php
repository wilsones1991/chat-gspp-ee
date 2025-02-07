<?php

namespace EricWilson\ChatGSPP\ControlPanel\Routes;

use ExpressionEngine\Service\Addon\Controllers\Mcp\AbstractRoute;

class Index extends AbstractRoute
{
    /**
     * @var string
     */
    protected $route_path = 'index';

    /**
     * @var string
     */
    protected $cp_page_title = 'Settings';

    /**
     * @param false $id
     * @return AbstractRoute
     */
    public function process($id = false)
    {
        $this->addBreadcrumb('index', 'Settings');
        
        // call our getForm() method to get
        // our array
        $form = $this->getForm();

        // store our form in our $variables array
        // to be passed into our view
        $variables = [
            'form'  => $form
        ];

        $this->setBody('Index', $variables);

        return $this;
    }

    private function getForm()
    {
        $form = ee('CP/Form');
        $form->asTab();
        $form->asFileUpload();
        $field_group = $form->getGroup('header 1');
        $field_set = $field_group->getFieldSet('first_name');
        $field_set->getField('first_name', 'text')
                    ->setDisabled(true)
                    ->setValue('Eric');

        return $form->toArray();
    }

}
