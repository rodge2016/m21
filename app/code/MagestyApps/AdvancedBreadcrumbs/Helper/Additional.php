<?php
/**
 * Copyright © 2016 MagestyApps. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MagestyApps\AdvancedBreadcrumbs\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\App\Helper\Context;
use \Magento\Framework\Registry;
use \Magento\Customer\Model\Url;
use Magento\Sales\Model\Order;

class Additional extends AbstractHelper
{
    const XML_PATH_ADDITIONAL_PAGES = 'magestyapps_advbreadcrumbs/additional_pages/';

    const ADDITIONAL_PAGE_CUSTOMER_ACCOUNT = 'customer_account';
    const ADDITIONAL_PAGE_CHECKOUT = 'checkout';
    const ADDITIONAL_PAGE_CART = 'cart';
    const ADDITIONAL_PAGE_CONTACTS = 'contacts';

    /** @var array */
    protected $_availableCrumbs = [];

    /** @var Data $_dataHelper */
    protected $_dataHelper;

    /** @var Registry $_coreRegistry */
    protected $_coreRegistry;

    /** @var Url $_customerUrlModel */
    protected $_customerUrlModel;

    public function __construct(
        Context $context,
        Data $dataHelper,
        Registry $coreRegistry,
        Url $customerUrlModel
    ) {
        $this->_dataHelper = $dataHelper;
        $this->_coreRegistry = $coreRegistry;
        $this->_customerUrlModel = $customerUrlModel;

        parent::__construct($context);
    }

    /**
     * Check whether breadcrumbs for $pageType should be displayed
     *
     * @param $pageType
     * @return bool
     */
    public function isEnabled($pageType)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ADDITIONAL_PAGES . $pageType
        );
    }

    /**
     * Initialize available breadcrumbs for additional pages
     *
     * @return $this
     */
    protected function initCrumbs()
    {
        $crumbs = [];

        if ($this->isEnabled(self::ADDITIONAL_PAGE_CUSTOMER_ACCOUNT)) {
            $customerAccountCrumb = [
                'code' => 'customer_account',
                'title' => __('My Account'),
                'url' => $this->_customerUrlModel->getAccountUrl()
            ];

            $newCrumbs = [
                'customer_form_login' => [
                    $customerAccountCrumb,
                    [
                        'code' => 'account_login',
                        'title' => __('Customer Login')
                    ],
                ],
                'customer_account_dashboard_info' => [
                    $customerAccountCrumb,
                    [
                        'code' => 'account_dashboard',
                        'title' => __('Dashboard')
                    ],
                ],
                'customer_edit' => [
                    $customerAccountCrumb,
                    [
                        'code' => 'account_edit',
                        'title' => __('Account Information'),
                    ],
                ],
                'address_book' => [
                    $customerAccountCrumb,
                    [
                        'code' => 'address_book',
                        'title' => __('Address Book'),
                    ],
                ],
                'customer_address_edit' => [
                    $customerAccountCrumb,
                    [
                        'code' => 'address_book',
                        'title' => __('Address Book'),
                        'url' => $this->_urlBuilder->getUrl('customer/address'),
                    ],
                    [
                        'code' => 'edit_address',
                        'title' => __('Edit Address'),
                    ],
                ],
                'sales.order.history' => [
                    $customerAccountCrumb,
                    [
                        'code' => 'customer_orders',
                        'title' => __('My Orders'),
                    ],
                ],
                'sales.order.info' => [
                    $customerAccountCrumb,
                    [
                        'code' => 'customer_orders',
                        'title' => __('My Orders'),
                        'url' => $this->_urlBuilder->getUrl('sales/order/history'),
                    ],
                    [
                        'code' => 'order_view',
                        'title' => __('Order # %1', $this->getCurrentOrderId()),
                    ],
                ],
                'review_customer_list' => [
                    $customerAccountCrumb,
                    [
                        'code' => 'product_reviews',
                        'title' => __('My Product Reviews'),
                    ],
                ],
                'customers_review' => [
                    $customerAccountCrumb,
                    [
                        'code' => 'product_reviews',
                        'title' => __('My Product Reviews'),
                        'url' => $this->_urlBuilder->getUrl('review/customer'),
                    ],
                    [
                        'code' => 'review_details',
                        'title' => __('Review Details'),
                    ],
                ],
                'downloadable_customer_products_list' => [
                    $customerAccountCrumb,
                    [
                        'code' => 'downloadable_products',
                        'title' => __('My Downloadable Products'),
                    ],
                ],
                'customer.account.billing.agreement' => [
                    $customerAccountCrumb,
                    [
                        'code' => 'billing_agreements',
                        'title' => __('Billing Agreements'),
                    ],
                ],
                'sales.recurring.profiles' => [
                    $customerAccountCrumb,
                    [
                        'code' => 'recurring_profiles',
                        'title' => __('Recurring Profiles'),
                    ],
                ],
                'customer.wishlist' => [
                    $customerAccountCrumb,
                    [
                        'code' => 'wishlist',
                        'title' => __('Wishlist'),
                    ],
                ],
                'wishlist.sharing' => [
                    $customerAccountCrumb,
                    [
                        'code' => 'wishlist',
                        'title' => __('Wishlist'),
                        'url' => $this->_urlBuilder->getUrl('wishlist'),
                    ],
                    [
                        'code' => 'share_whishlist',
                        'title' => __('Share Wishlist'),
                    ],
                ],
                'oauth_customer_token_list' => [
                    $customerAccountCrumb,
                    [
                        'code' => 'applications',
                        'title' => __('Applications'),
                    ],
                ],
                'customer_newsletter' => [
                    $customerAccountCrumb,
                    [
                        'code' => 'customer_newsletter',
                        'title' => __('Newsletter Subscriptions'),
                    ],
                ],
            ];

            $crumbs = array_merge($crumbs, $newCrumbs);
        }

        if ($this->isEnabled(self::ADDITIONAL_PAGE_CHECKOUT)) {
            $newCrumbs = [
                'checkout.root' => [
                    [
                        'code'  => 'checkout_cart',
                        'title' => __('Shopping Cart'),
                        'url'   => $this->_urlBuilder->getUrl('checkout/cart')
                    ],
                    [
                        'code'  => 'checkout_onepage',
                        'title' => __('Checkout'),
                    ],
                ],
                'checkout.success' => [
                    [
                        'code'  => 'checkout_cart',
                        'title' => __('Shopping Cart'),
                        'url'   => $this->_urlBuilder->getUrl('checkout/cart')
                    ],
                    [
                        'code'  => 'checkout_onepage',
                        'title' => __('Checkout'),
                    ],
                ],
                'checkout.failure' => [
                    [
                        'code'  => 'checkout_cart',
                        'title' => __('Shopping Cart'),
                        'url'   => $this->_urlBuilder->getUrl('checkout/cart')
                    ],
                    [
                        'code'  => 'checkout_onepage',
                        'title' => __('Checkout'),
                    ],
                ],
            ];

            $crumbs = array_merge($crumbs, $newCrumbs);
        }

        if ($this->isEnabled(self::ADDITIONAL_PAGE_CART)) {
            $newCrumbs = [
                'checkout.cart' => [
                    [
                        'code'  => 'checkout_cart',
                        'title' => __('Shopping Cart'),
                    ],
                ],
            ];

            $crumbs = array_merge($crumbs, $newCrumbs);
        }

        if ($this->isEnabled(self::ADDITIONAL_PAGE_CONTACTS)) {
            $crumbs[ 'contactForm'] = [
                [
                    'code'  => 'contacts',
                    'title' => __('Contact Us'),
                ],
            ];
        }

        $this->_availableCrumbs = $crumbs;

        return $this;
    }

    /**
     * Get crumbs based on specific block in layout
     *
     * @param string $nameInLayout
     * @return array
     */
    public function getCrumbs($nameInLayout)
    {
        if (!count($this->_availableCrumbs)) {
            $this->initCrumbs();
        }

        return isset($this->_availableCrumbs[$nameInLayout]) ?
            $this->_availableCrumbs[$nameInLayout] : [];
    }

    /**
     * Get current order model (when it is opened in customer account)
     *
     * @return bool|string
     */
    public function getCurrentOrderId()
    {
        /** @var Order $order */
        $order = $this->_coreRegistry->registry('current_order');
        if (!$order || !$order->getIncrementId()) {
            return false;
        }

        return $order->getIncrementId();
    }
}