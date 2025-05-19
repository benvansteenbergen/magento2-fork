<?php
namespace Vendor\B2Discount\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\SalesRule\Model\RuleFactory;
use Vendor\B2Discount\Model\Rule as B2Rule;

class InstallBuyTwoDiscountRule implements DataPatchInterface
{
    private $moduleDataSetup;
    private $ruleFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        RuleFactory $ruleFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->ruleFactory = $ruleFactory;
    }

    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $rule = $this->ruleFactory->create();
        $rule->load($rule->getResource()->getIdByCode('b2_discount_auto'));
        if (!$rule->getId()) {
            $rule->setName('Buy 2 Get 10% Off Same Product')
                ->setIsActive(1)
                ->setWebsiteIds([1])
                ->setCustomerGroupIds([0,1,2,3])
                ->setCouponType(\Magento\SalesRule\Model\Rule::COUPON_TYPE_NO_COUPON)
                ->setSimpleAction(B2Rule::BUY_TWO_DISCOUNT_ACTION)
                ->setDiscountAmount(10)
                ->setStopRulesProcessing(1)
                ->setSortOrder(0)
                ->setUseAutoGeneration(0)
                ->setApplyToShipping(0)
                ->setCode('b2_discount_auto');
            $rule->save();
        }

        $this->moduleDataSetup->endSetup();
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}

