<?php
/**
 * This is the PHP base ACL configuration file.
 *
 * Use it to configure access control of your Cake application.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 2.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Example
 * -------
 *
 * Assumptions:
 *
 * 1. In your application you created a User model with the following properties:
 *    username, group_id, password, email, firstname, lastname and so on.
 * 2. You configured AuthComponent to authorize actions via
 *    $this->Auth->authorize = array('Actions' => array('actionPath' => 'controllers/'),...)
 *
 * Now, when a user (i.e. jeff) authenticates successfully and requests a controller action (i.e. /invoices/delete)
 * that is not allowed by default (e.g. via $this->Auth->allow('edit') in the Invoices controller) then AuthComponent
 * will ask the configured ACL interface if access is granted. Under the assumptions 1. and 2. this will be
 * done via a call to Acl->check() with
 *
 *    array('User' => array('username' => 'jeff', 'group_id' => 4, ...))
 *
 * as ARO and
 *
 *    '/controllers/invoices/delete'
 *
 * as ACO.
 *
 * If the configured map looks like
 *
 *    $config['map'] = array(
 *       'User' => 'User/username',
 *       'Role' => 'User/group_id',
 *    );
 *
 * then PhpAcl will lookup if we defined a role like User/jeff. If that role is not found, PhpAcl will try to
 * find a definition for Role/4. If the definition isn't found then a default role (Role/default) will be used to
 * check rules for the given ACO. The search can be expanded by defining aliases in the alias configuration.
 * E.g. if you want to use a more readable name than Role/4 in your definitions you can define an alias like
 *
 *    $config['alias'] = array(
 *       'Role/4' => 'Role/editor',
 *    );
 *
 * In the roles configuration you can define roles on the lhs and inherited roles on the rhs:
 *
 *    $config['roles'] = array(
 *       'Role/admin' => null,
 *       'Role/accountant' => null,
 *       'Role/editor' => null,
 *       'Role/manager' => 'Role/editor, Role/accountant',
 *       'User/jeff' => 'Role/manager',
 *    );
 *
 * In this example manager inherits all rules from editor and accountant. Role/admin doesn't inherit from any role.
 * Lets define some rules:
 *
 *    $config['rules'] = array(
 *       'allow' => array(
 *       	'*' => 'Role/admin',
 *       	'controllers/users/(dashboard|profile)' => 'Role/default',
 *       	'controllers/invoices/*' => 'Role/accountant',
 *       	'controllers/articles/*' => 'Role/editor',
 *       	'controllers/users/*'  => 'Role/manager',
 *       	'controllers/invoices/delete'  => 'Role/manager',
 *       ),
 *       'deny' => array(
 *       	'controllers/invoices/delete' => 'Role/accountant, User/jeff',
 *       	'controllers/articles/(delete|publish)' => 'Role/editor',
 *       ),
 *    );
 *
 * Ok, so as jeff inherits from Role/manager he's matched every rule that references User/jeff, Role/manager,
 * Role/editor, Role/accountant and Role/default. However, for jeff, rules for User/jeff are more specific than
 * rules for Role/manager, rules for Role/manager are more specific than rules for Role/editor and so on.
 * This is important when allow and deny rules match for a role. E.g. Role/accountant is allowed
 * controllers/invoices/* but at the same time controllers/invoices/delete is denied. But there is a more
 * specific rule defined for Role/manager which is allowed controllers/invoices/delete. However, the most specific
 * rule denies access to the delete action explicitly for User/jeff, so he'll be denied access to the resource.
 *
 * If we would remove the role definition for User/jeff, then jeff would be granted access as he would be resolved
 * to Role/manager and Role/manager has an allow rule.
 */

/**
 * The role map defines how to resolve the user record from your application
 * to the roles you defined in the roles configuration.
 */
$config['map'] = array(
	'User' => 'User/username',
	'Role' => 'User/role',
);

/**
 * define aliases to map your model information to
 * the roles defined in your role configuration.
 */
// $config['alias'] = array(
// 	'Role/4' => 'Role/editor',
// );

/**
 * role configuration
 */
$config['roles'] = array(
	'Role/Admin'    => null,
	'Role/User'     => null,
	'Role/Guest'    => 'Role/User',
	'Role/Content'  => 'Role/User',
	'Role/Marketing'    => 'Role/User',
	'Role/Developer'    => 'Role/Admin',
    'Role/Distributor'  => 'Role/User',
);

/**
 * rule configuration
 */
$config['rules'] = array(
	'allow' => array(
		'*' => 'Role/Admin',

        'Bonuses/*'             => 'Role/Content',
        'Bonuses/api_index'     => 'Role/User',

        'CompensePayments/*'    => 'Role/Content',

        'EmailMarketings/*'     => 'Role/Content, Role/Marketing',

        'games/admin_editDescription' => 'Role/Content',
        'games/admin_index' => 'Role/Content, Role/Marketing, Role/Developer',

        'genres/*' => 'Role/Content',

		'Oauth/*' => 'Role/User',

        'payments/api_pay'      => 'Role/User',
        'payments/index'        => 'Role/User',
        'payments/inapp'        => 'Role/User',
        'payments/order'        => 'Role/User',
        'payments/pay'          => 'Role/User',
        'payments/api_charge'   => 'Role/User',
        'payments/admin_index'  => 'Role/Content',
        'payments/admin_inpay'  => 'Role/Content',
        'payments/pay_list'  => 'Role/User',
        'payments/api_googleVerify'     => 'Role/User',
        'payments/pay_paypal_index'     => 'Role/User',
        'payments/pay_paypal_order'     => 'Role/User',
        'payments/pay_paypal_response'  => 'Role/User',

        'ManualPayments/index'          => 'Role/User',
        'ManualPayments/shopcard'       => 'Role/User',
        'ManualPayments/admin_index'    => 'Role/Content',

        'OvsPayments/pay_list'      => 'Role/User',
        'OvsPayments/admin_detail'  => 'Role/Content, Role/Distributor',

        'OvsPayments/pay_paypal_index'     => 'Role/User',
        'OvsPayments/pay_paypal_order'     => 'Role/User',
        'OvsPayments/pay_paypal_response'  => 'Role/User',

        'OvsPayments/pay_vippay_index'     => 'Role/User',
        'OvsPayments/pay_vippay_order'     => 'Role/User',
        'OvsPayments/pay_vippay_response'  => 'Role/User',

        'OvsPayments/pay_appota_index'     => 'Role/User',
        'OvsPayments/pay_appota_order'     => 'Role/User',
        'OvsPayments/pay_appota_response'  => 'Role/User',

        'OvsPayments/pay_ale_index'      => 'Role/User',
        'OvsPayments/pay_ale_order'      => 'Role/User',
        'OvsPayments/pay_ale_response'   => 'Role/User',

        'OvsPayments/pay_onepay_index'     => 'Role/User',
        'OvsPayments/pay_onepay_atm'        => 'Role/User',
        'OvsPayments/pay_onepay_order'     => 'Role/User',
        'OvsPayments/pay_onepay_response'  => 'Role/User',

		'OvsPayments/pay_paymentwall_index'  => 'Role/User',
        'OvsPayments/pay_paymentwall_order'  => 'Role/User',
        'OvsPayments/pay_paymentwall_response'  => 'Role/User',
        'OvsPayments/pay_paymentwall_bank'      => 'Role/User',
        'OvsPayments/pay_paymentwall_card'      => 'Role/User',
        'OvsPayments/pay_paymentwall_visa'      => 'Role/User',

        'permissions/*' => 'Role/Developer',
        'permissions/admin_delete' => 'Role/Content',

		'users/admin_index' => 'Role/Content, Role/Marketing, Role/Developer',
		'users/admin_editContent' => 'Role/Content',
        'users/admin_view' => 'Role/Content',
        'users/admin_deactive' => 'Role/Content',
		'users/admin_reset_password' => 'Role/Content',
        'users/admin_searchip'  => 'Role/Content',
		'users/admin_blockip'   => 'Role/Content',

		'users/api_update_info' => 'Role/User',

        'WaitingPayments/admin_google'  => 'Role/Content',
        'WaitingPayments/admin_index'   => 'Role/Content, Role/Distributor',
        'WaitingPayments/api_index'     => 'Role/User',
        'WaitingPayments/admin_block'   => 'Role/Content',
        'WaitingPayments/admin_block_ip'    => 'Role/Content',
        'WaitingPayments/api_gift'      => 'Role/User',

		'websites/admin_setsession' => 'Role/Content, Role/Marketing, Role/Developer',
	),
	'deny' => array(
		'users/admin_edit' => 'Role/Marketing, Role/Developer',
		'users/admin_add' => 'Role/Marketing, Role/Developer',
		'users/admin_delete' => 'Role/Marketing, Role/Developer',
		'accounts/admin_delete' => 'Role/Marketing, Role/Developer',
	),
);
