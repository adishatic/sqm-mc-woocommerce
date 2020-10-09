<?php

/**
 * Created by Vextras.
 *
 * Name: Ryan Hungate
 * Email: ryan@vextras.com
 * Date: 7/14/16
 * Time: 10:57 AM
 */
class SqualoMail_WooCommerce_Process_Orders extends SqualoMail_WooCommerce_Abstract_Sync
{
    /**
     * @var string
     */
    protected $action = 'squalomail_woocommerce_process_orders';
    public $items = array();

    /**
     * @return string
     */
    public function getResourceType()
    {
        return 'orders';
    }

    /**
     * After the resources have been loaded and pushed
     */
    protected function complete()
    {
        squalomail_log('order_sync.completed', 'Done with the order queueing.');

        // add a timestamp for the orders sync completion
        $this->setResourceCompleteTime();
    }

}
