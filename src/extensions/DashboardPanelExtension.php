<?php

namespace SilverCommerce\Dashboard\Extension;

use SilverStripe\ORM\DB;
use SilverStripe\ORM\DataExtension;
use SilverStripe\SiteConfig\SiteConfig;
use SilverCommerce\Dashboard\Panel\LowStockPanel;
use SilverCommerce\Dashboard\Panel\TopProductsPanel;
use SilverCommerce\Dashboard\Panel\NewCustomersPanel;
use SilverCommerce\Dashboard\Panel\ContentSummaryPanel;
use SilverCommerce\Dashboard\Panel\RecentOrdersListPanel;
use SilverCommerce\Dashboard\Panel\RecentOrdersChartPanel;

/**
 * Setup default admin panels for a SilverCommerce install
 */
class DashboardPanelExtension extends DataExtension
{
    public function requireDefaultRecords()
    {
        $config = SiteConfig::current_site_config();

        if (!$config->DashboardPanels()->exists()) {
            // Add chart panel
            $panel = RecentOrdersChartPanel::create();
            $panel->Title = $panel->getLabel();
            $panel->SiteConfigID = $config->ID;
            $panel->SortOrder = 1;
            $panel->write();

            // Add content summary panel
            $panel = ContentSummaryPanel::create();
            $panel->Title = $panel->getLabel();
            $panel->SiteConfigID = $config->ID;
            $panel->SortOrder = 2;
            $panel->write();

            // Add orders list panel
            $panel = RecentOrdersListPanel::create();
            $panel->Title = $panel->getLabel();
            $panel->SiteConfigID = $config->ID;
            $panel->SortOrder = 3;
            $panel->write();

            // Add low stock items panel
            $panel = LowStockPanel::create();
            $panel->Title = $panel->getLabel();
            $panel->SiteConfigID = $config->ID;
            $panel->SortOrder = 4;
            $panel->write();

            // Add top products panel
            $panel = TopProductsPanel::create();
            $panel->Title = $panel->getLabel();
            $panel->SiteConfigID = $config->ID;
            $panel->SortOrder = 5;
            $panel->write();

            // Add new customer panel
            $panel = NewCustomersPanel::create();
            $panel->Title = $panel->getLabel();
            $panel->SiteConfigID = $config->ID;
            $panel->SortOrder = 6;
            $panel->write();
            
            DB::alteration_message('Created default commerce dashboard', 'created');
        }
    }
}
