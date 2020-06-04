<?php

namespace SilverCommerce\Dashboard\Panel;

use UncleCheese\Dashboard\DashboardPanel;
use SilverCommerce\OrdersAdmin\Model\Invoice;

class OrderStatusPanel extends DashboardPanel
{
    private static $table_name = 'DashboardOrderStatusPanel';

    private static $icon = "silvercommerce/dashboard: client/dist/images/order.png";

	private static $defaults = array (
		"PanelSize" => "small"
    );

    private static $processing_statuses = [
        "paid",
        "processing",
        "ready"
    ];

    private static $shipped_statuses = [
        "dispatched",
        "collected"
    ];

    private static $unpaid_statuses = [
        "failed",
        "pending",
        "part-paid"
    ];
    
    public function getLabel()
    {
        return _t(__CLASS__ . '.Orders', 'Orders');
    }

    public function getDescription()
    {
        return _t(__CLASS__ . '.OrderStatusDescription', 'Overview of orders by current status.');
    }

    public function OrdersProcessing()
    {
        $orders = Invoice::get()
            ->filter(
                'Status', $this->config()->processing_statuses
            );
        
        return $orders->count();
    }

    public function OrdersShipped()
    {
        $orders = Invoice::get()
            ->filter(
                'Status', $this->config()->shipped_statuses
            );
        
        return $orders->count();
    }

    public function OrdersAwaitingPayment()
    {
        $orders = Invoice::get()
            ->filter(
                'Status', $this->config()->unpaid_statuses
            );
        
        return $orders->count();
    }
}