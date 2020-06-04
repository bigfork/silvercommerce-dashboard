<?php

namespace SilverCommerce\Dashboard\Panel;

use SilverStripe\Assets\File;
use SilverStripe\Security\Group;
use SilverStripe\Security\Member;
use SilverStripe\View\Requirements;
use ilateral\SilverStripe\Users\Users;
use SilverCommerce\CatalogueAdmin\Model\CatalogueCategory;
use SilverCommerce\CatalogueAdmin\Model\CatalogueProduct;
use UncleCheese\Dashboard\DashboardPanel;
use SilverStripe\CMS\Model\SiteTree;

class ContentSummaryPanel extends DashboardPanel
{
    private static $table_name = 'DashboardContentSummaryPanel';

    private static $icon = "silvercommerce/dashboard: client/dist/images/search.png";

	private static $defaults = array (
		"PanelSize" => "small"
	);

    public function getLabel()
    {
        return _t(__CLASS__ . '.SiteContentSummary', 'Site Content Summary');
    }


    public function getDescription()
    {
        return _t(__CLASS__ . '.SiteContentSummaryDescription', 'Show a summary of website content');
    }

    public function PanelHolder()
    {
        Requirements::css("silvercommerce/dashboard: client/dist/css/dashboard.css");
        return parent::PanelHolder();
    }

    /**
     * Get the total amount of products on this site
     *
     * @return Int
     */
    public function Products()
    {
        return CatalogueProduct::get()->count();
    }

    /**
     * Get the total amount of products on this site
     *
     * @return Int
     */
    public function Categories()
    {
        return CatalogueCategory::get()->count();
    }

    /**
     * Get the total amount of pages on this site
     *
     * @return Int
     */
    public function Pages()
    {
        if (class_exists(SiteTree::class)) {
            return SiteTree::get()->count();
        }
        return 0;
    }


    /**
     * Get the total amount of files on this site
     *
     * @return Int
     */
    public function Files()
    {
        return File::get()->count();
    }

    /**
     * Get a list of customers (users who have signed in via the registration form)
     *
     * @return Int
     */
    public function Customers()
    {
        $members = 0;
        
        $groups = Group::get()->filter(array(
            "Code" => Users::config()->new_user_groups
        ));

        if ($groups->exists()) {
            $members = Member::get()
                ->filter("Groups.ID", $groups->column("ID"))
                ->count();
        }

        return $members;
    }
}