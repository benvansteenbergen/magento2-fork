<?php
namespace Vendor\B2Discount\Plugin;

use Magento\SalesRule\Model\Rule\Action\SimpleActionOptionsProvider;
use Vendor\B2Discount\Model\Rule as B2Rule;

class AddSimpleActionOption
{
    public function afterToOptionArray(SimpleActionOptionsProvider $subject, array $result)
    {
        $result[] = [
            'label' => __('Buy 2 get 10% off same product'),
            'value' => B2Rule::BUY_TWO_DISCOUNT_ACTION
        ];
        return $result;
    }
}

