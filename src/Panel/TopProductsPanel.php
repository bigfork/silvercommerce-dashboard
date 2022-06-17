<?php

namespace SilverCommerce\Dashboard\Panel;

use DateTime;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Core\Injector\Injector;
use SilverCommerce\OrdersAdmin\Model\Invoice;
use SilverCommerce\Reports\ItemsOrderedReport;
use ilateral\SilverStripe\Dashboard\Panels\DashboardPanel;
use ilateral\SilverStripe\Dashboard\Components\DashboardPanelAction;

class TopProductsPanel extends DashboardPanel
{
    private static $table_name = 'DashboardTopProductsPanel';

    private static $db = array (
        'Count' => 'Int'
    );

	private static $defaults = array (
		'Count' => "5",
		'PanelSize' => "small"
	);

    private static $icon = "silvercommerce/dashboard: client/dist/images/top.png";

    public function getLabel(): string
    {
        return _t(__CLASS__ . '.TopProducts', 'Top Products');
    }

    public function getDescription(): string
    {
        return _t(__CLASS__ . '.TopProductsDescription' ,'Shows top selling products this month.');
    }

    /**
     * Return a link to the "items ordered" report 
     *
     * @return string
     */
    public function getReportLink(): string
    {
        if (class_exists(ItemsOrderedReport::class)) {
            return Injector::inst()->create(ItemsOrderedReport::class)->getLink();
        }

        return "";
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
                "Number of products to show"
            )
        );

        return $fields;
    }

    public function getSecondaryActions(): ArrayList
    {
		$actions = parent::getSecondaryActions();
		$actions->push(DashboardPanelAction::create(
            $this->getReportLink(),
            _t("SilverCommerce.ViewAll", "View All")
        ));
			
		return $actions;
	}

    /**
     * Return a list of top products for the template
     *
     * @return ArrayList
     */
    public function getProducts()
    {
        $return = ArrayList::create();

        $start_date = new DateTime();
        $start_date->modify("-1 month");

        $end_date = new DateTime();

        // Get all orders in the date range
        $orders = Invoice::get()
            ->filter(array(
                "Created:GreaterThan" => $start_date->format('Y-m-d H:i:s'),
                "Created:LessThan" => $end_date->format('Y-m-d H:i:s')
            ));

        // Loop through orders, find all items and add to a tally
        foreach ($orders as $order) {
            foreach ($order->Items() as $order_item) {
                if ($order_item->StockID) {
                    if ($list_item = $return->find("StockID", $order_item->StockID)) {
                        $list_item->Quantity = $list_item->Quantity + $order_item->Quantity;
                    } else {
                        $return->add(ArrayData::create(array(
                            "StockID" => $order_item->StockID,
                            "Title" => $order_item->Title,
                            "Quantity" => $order_item->Quantity
                        )));
                    }
                }
            }
        }

        return $return;
    }
}