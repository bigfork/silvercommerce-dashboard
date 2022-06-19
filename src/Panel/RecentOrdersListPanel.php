<?php

namespace SilverCommerce\Dashboard\Panel;

use SilverStripe\ORM\DataList;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Core\Injector\Injector;
use SilverCommerce\OrdersAdmin\Model\Invoice;
use SilverCommerce\OrdersAdmin\Admin\OrderAdmin;
use ilateral\SilverStripe\Dashboard\Panels\DashboardPanel;
use ilateral\SilverStripe\Dashboard\Components\DashboardPanelAction;

class RecentOrdersListPanel extends DashboardPanel
{
    private static $table_name = 'DashboardRecentOrdersListPanel';

    private static $db = array (
        'Count' => 'Int'
    );

    private static $defaults = array (
        'PanelSize' => "normal"
    );

    private static $icon = "silvercommerce/dashboard: client/dist/images/order_162.png";

    public function getLabel(): string
    {
        return _t(__CLASS__ . '.RecentOrdersList', 'Recent Orders List');
    }

    public function getDescription(): string
    {
        return _t(__CLASS__ . '.RecentOrdersListDescription', 'Shows a list of recent orders.');
    }

    /**
     * Generate a link to the order admin controller
     *
     * @return String
     */
    public function getOrderslink()
    {
        return Injector::inst()->create(OrderAdmin::class)->Link();
    }

    public function getPanelHolder(): string
    {
        return parent::getPanelHolder();
    }

    public function getConfigurationFields(): FieldList
    {
        $fields = parent::getConfigurationFields();

        $fields->push(
            TextField::create(
                "Count",
                "Number of orders to show"
            )
        );

        return $fields;
    }

    public function getSecondaryActions(): ArrayList
    {
        $actions = parent::getSecondaryActions();
        $actions->push(
            DashboardPanelAction::create(
                $this->getOrdersLink(),
                _t("SilverCommerce.ViewAll", "View All")
            )
        );
            
        return $actions;
    }

    /**
     * Return a full list of orders for the template
     *
     * @return DataList
     */
    public function getOrders(): DataList
    {
        $count = ($this->Count) ? $this->Count : 7;
        $status = Invoice::config()->incomplete_status;

        return Invoice::get()
            ->filter(
                array(
                    "Status:not" => $status
                )
            )->sort("Created DESC")
            ->limit($count);
    }
}