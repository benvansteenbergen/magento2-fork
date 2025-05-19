<?php
namespace Vendor\B2Discount\Model\Rule\Action\Discount;

use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\SalesRule\Model\Rule;
use Magento\SalesRule\Model\Rule\Action\Discount\AbstractDiscount;
use Magento\SalesRule\Model\Rule\Action\Discount\Data;

class BuyTwoDiscount extends AbstractDiscount
{
    /**
     * Apply 10% discount when buying pairs of the same product.
     *
     * @param Rule $rule
     * @param AbstractItem $item
     * @param float $qty
     * @return Data
     */
    public function calculate($rule, $item, $qty)
    {
        $discountData = $this->discountFactory->create();
        $productType = $item->getProduct()->getTypeId();
        if (!in_array($productType, ['simple', 'configurable'])) {
            return $discountData;
        }

        $pairQty = (int) floor($qty / 2);
        if ($pairQty <= 0) {
            return $discountData;
        }

        $itemPrice = $this->validator->getItemPrice($item);
        $baseItemPrice = $this->validator->getItemBasePrice($item);
        $itemOriginalPrice = $this->validator->getItemOriginalPrice($item);
        $baseItemOriginalPrice = $this->validator->getItemBaseOriginalPrice($item);

        $discountPerPair = ($itemPrice * 2) * 0.10;
        $baseDiscountPerPair = ($baseItemPrice * 2) * 0.10;
        $originalDiscountPerPair = ($itemOriginalPrice * 2) * 0.10;
        $baseOriginalDiscountPerPair = ($baseItemOriginalPrice * 2) * 0.10;

        $discountData->setAmount($pairQty * $discountPerPair);
        $discountData->setBaseAmount($pairQty * $baseDiscountPerPair);
        $discountData->setOriginalAmount($pairQty * $originalDiscountPerPair);
        $discountData->setBaseOriginalAmount($pairQty * $baseOriginalDiscountPerPair);

        return $discountData;
    }

    /**
     * Only whole pairs are discounted.
     *
     * @param float $qty
     * @param Rule $rule
     * @return float
     */
    public function fixQuantity($qty, $rule)
    {
        return floor($qty / 2) * 2;
    }
}

