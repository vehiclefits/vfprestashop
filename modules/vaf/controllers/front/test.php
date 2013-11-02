<?php
class vaftestModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        // get link to this controller from the vaf.php module class with this code:
        // $this->context->link->getModuleLink('vaf', 'test')
        parent::initContent();
        $this->setTemplate('test.tpl');
    }
}