<?php
defined('_JEXEC') or die;

class JFormFieldSubscriptionPaymentMethods extends JFormField
{
    protected $type = 'subscriptionpaymentmethods';

    public function getInput() {

        $model = F0FModel::getTmpInstance('Payments','J2StoreModel');
        $paymentlist = $model->getItemList();
        $attr = array();
        // Get the field options.
        // Initialize some field attributes.
        $attr['class']= !empty($this->class) ? $this->class: '';
        $attr ['size']= !empty($this->size) ? $this->size : '';
        $attr ['multiple']= $this->multiple ? 'multiple': '';
        $attr ['autofocus']= $this->autofocus ? 'autofocus' : '';
        // Initialize JavaScript field attributes.
        $attr ['onchange']= $this->onchange ?  $this->onchange : '';

        $app = JFactory::getApplication();
        //generate country filter list
        $subscription_payment_methods = array();
        foreach($paymentlist as $row) {
            $results = $app->triggerEvent("onJ2StoreAcceptSubscriptionPayment", array( $row->element) );
            if(!empty($results) && $results){
                $subscription_payment_methods[$row->element] = JText::_($row->name);
            }
        }
        $value = $this->value;

        return J2Html::select()->clearState()
            ->type('genericlist')
            ->name($this->name)
            ->attribs($attr)
            ->value($value)
            ->setPlaceHolders($subscription_payment_methods)
            ->getHtml();
    }

}