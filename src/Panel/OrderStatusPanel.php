<?php

namespace SilverCommerce\Dashboard\Panel;

use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\Core\Config\Config;
use SilverCommerce\OrdersAdmin\Model\Invoice;
use ilateral\SilverStripe\Dashboard\Panels\DashboardPanel;

class OrderStatusPanel extends DashboardPanel
{
    private static $table_name = 'DashboardOrderStatusPanel';

    private static $font_icon = "book-open";

	private static $defaults = array (
		"PanelSize" => "small"
    );

    /**
     * Base classname to use for to retrieve statuses
     *
     * @var string
     */
    private static $order_class = Invoice::class;

    /**
     * Config variable used to store statuses list
     *
     * @var string
     */
    private static $statuses_config = 'statuses';

    /**
     * List of statuses that can be ignored
     *
     * @var array
     */
    private static $ignore_statuses = [
        "dispatched",
        "collected",
        "refunded"
    ];

    public function getLabel(): string
    {
        return _t(__CLASS__ . '.Orders', 'Orders');
    }

    public function getDescription(): string
    {
        return _t(__CLASS__ . '.OrderStatusDescription', 'Overview of orders by current status.');
    }

    public function getStatusCount()
    {
        $class = $this->config()->order_class;
        $param = $this->config()->statuses_config;
        $ignore = $this->config()->ignore_statuses;
        $statuses = Config::inst()->get($class, $param);
        $list = ArrayList::create();

        foreach ($statuses as $key => $name) {
            if (in_array($key, $ignore)) {
                continue;
            }

            $list->add(ArrayData::create([
                'Status' => $name,
                'Count' => $class::get()->filter('Status', $key)->count()
            ]));
        }

        return $list;
    }
}
