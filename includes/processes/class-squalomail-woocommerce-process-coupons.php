<?php

/**
 * Created by Vextras.
 *
 * Name: Ryan Hungate
 * Email: ryan@vextras.com
 * Date: 10/06/17
 * Time: 10:57 AM
 */
class SqualoMail_WooCommerce_Process_Coupons extends SqualoMail_WooCommerce_Abstract_Sync
{
    /**
     * @var string
     */
    protected $action = 'squalomail_woocommerce_process_coupons';

    /**
     * @return string
     */
    public function getResourceType()
    {
        return 'coupons';
    }

    /**
     * After the resources have been loaded and pushed
     */
    protected function complete()
    {
        squalomail_log('coupon_sync.completed', 'Done with the coupon queueing.');

        // add a timestamp for the orders sync completion
        $this->setResourceCompleteTime();
    }
}
