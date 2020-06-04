<?php

namespace SilverCommerce\Dashboard\Panel;

use SilverStripe\Forms\TextField;
use SilverStripe\View\Requirements;
use SilverStripe\Core\Injector\Injector;
use UncleCheese\Dashboard\DashboardPanel;
use SilverCommerce\OrdersAdmin\Model\Invoice;
use UncleCheese\Dashboard\DashboardPanelAction;
use SilverCommerce\OrdersAdmin\Admin\OrderAdmin;

class RecentOrdersListPanel extends DashboardPanel
{

    private static $db = array (
        'Count' => 'Int'
    );

    private static $defaults = array (
        'PanelSize' => "normal"
    );

    private static $icon = "silvercommerce/dashboard: client/dist/images/order_162.png";

    public function getLabel()
    {
        return _t(__CLASS__ . '.RecentOrdersList', 'Recent Orders List');
    }

    public function getDescription()
    {
        return _t(__CLASS__ . '.RecentOrdersListDescription', 'Shows a list of recent orders.');
    }

    /**
     * Generate a link to the order admin controller
     *
     * @return String
     */
    public function Orderslink()
    {
        return Injector::inst()->create(OrderAdmin::class)->Link();
    }

    public function PanelHolder()
    {
        Requirements::css("silvercommerce/dashboard: client/dist/css/dashboard.css");
        return parent::PanelHolder();
    }

    public function getConfiguration()
    {
        $fields = parent::getConfiguration();

        $fields->push(
            TextField::create(
                "Count",
                "Number of orders to show"
            )
        );

        return $fields;
    }

    /**
     * Add view all button to actions
     *
     * @return ArrayList
     */
    public function getSecondaryActions()
    {
        $actions = parent::getSecondaryActions();
        $actions->push(
            DashboardPanelAction::create(
                $this->OrdersLink(),
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
    public function Orders()
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