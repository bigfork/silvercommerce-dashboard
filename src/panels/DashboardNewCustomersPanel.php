<?php

namespace SilverCommerce\Dashboard\Panel;

use SilverStripe\Security\Group;
use SilverStripe\Forms\TextField;
use SilverStripe\Security\Member;
use SilverStripe\View\Requirements;
use SilverStripe\Admin\SecurityAdmin;
use ilateral\SilverStripe\Users\Users;
use SilverStripe\Core\Injector\Injector;
use UncleCheese\Dashboard\DashboardPanel;
use UncleCheese\Dashboard\DashboardPanelAction;

class DashboardNewCustomersPanel extends DashboardPanel
{

    private static $icon = "silvercommerce/dashboard: client/dist/images/customers.png";

    private static $db = array (
        "Count" => "Int"
    );

	private static $defaults = array (
        "Count"     => "7",
		"PanelSize" => "small"
    );

    public function getLabel()
    {
        return _t('SilverCommerce.LatestCustomers','Latest Customers');
    }


    public function getDescription()
    {
        return _t('SilverCommerce.LatestCustomersDescription','Shows latest customers to join.');
    }

    /**
     * Generate a link to the security admin controller
     *
     * @return String
     */
    public function Securitylink()
    {
        return Injector::inst()->create(SecurityAdmin::class)->Link();
    }

    public function PanelHolder()
    {
        Requirements::css("silvercommerce/dashboard: client/dist/css/dashboard.css");
        return parent::PanelHolder();
    }

    public function getConfiguration()
    {
        $fields = parent::getConfiguration();

        $fields->push(TextField::create(
            "Count",
            "Number of customers to show"
        ));

        return $fields;
    }

    /**
     * Add view all button to actions
     *
     * @return ArrayList
     */
    public function getSecondaryActions() {
		$actions = parent::getSecondaryActions();
		$actions->push(DashboardPanelAction::create(
            $this->Securitylink(),
            _t("SilverCommerce.ViewAll", "View All")
        ));
			
		return $actions;
	}

    /**
     * Get a list of members who registered through the users module
     * and return (ordered by most recent first).
     *
     * @return SS_List | null
     */
    public function Customers()
    {
        $members = null;
        
        $groups = Group::get()->filter(array(
            "Code" => Users::config()->new_user_groups
        ));

        if ($groups->exists()) {
            $count = ($this->Count) ? $this->Count : 7;

            $members = Member::get()
                ->filter("Groups.ID", $groups->column("ID"))
                ->sort("Created", "DESC")
                ->limit($count);
        }

        return $members;
    }

}