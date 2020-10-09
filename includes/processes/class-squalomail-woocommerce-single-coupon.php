<?php

/**
 * Created by Vextras.
 *
 * Name: Ryan Hungate
 * Email: ryan@vextras.com
 * Date: 10/6/17
 * Time: 11:14 AM
 */
class SqualoMail_WooCommerce_SingleCoupon extends Squalomail_Woocommerce_Job
{
    public $coupon_data;
    public $id;

    /**
     * SqualoMail_WooCommerce_Coupon_Sync constructor.
     * @param $id
     */
    public function __construct($id = null)
    {
        $this->setId($id);
    }

    /**
     * @param null $id
     * @return SqualoMail_WooCommerce_SingleCoupon
     */
    public function setId($id)
    {
        if (!empty($id)) {
            $this->id = $id instanceof WP_Post ? $id->ID : $id;
        }
    }
    
    /**
     * @return null
     */
    public function handle()
    {
        try {

            if (!mailchimp_is_configured()) {
                mailchimp_debug(get_called_class(), 'Mailchimp is not configured properly');
                return false;
            }

            if (empty($this->id)) {
                mailchimp_error('promo_code_submit.failure', "could not process coupon {$this->id}");
                return;
            }

            $api = mailchimp_get_api();
            $store_id = mailchimp_get_store_id();

            $transformer = new SqualoMail_WooCommerce_Transform_Coupons();
            $code = $transformer->transform($this->id);

            $api->addPromoRule($store_id, $code->getAttachedPromoRule(), true);
            $api->addPromoCodeForRule($store_id, $code->getAttachedPromoRule(), $code, true);

            mailchimp_log('promo_code_submit.success', "#{$this->id} :: code: {$code->getCode()}");
        } catch (SqualoMail_WooCommerce_RateLimitError $e) {
            sleep(3);
            $promo_code = isset($code) ? "code {$code->getCode()}" : "id {$this->id}";
            mailchimp_error('promo_code_submit.error', mailchimp_error_trace($e, "RateLimited :: #{$promo_code}"));
            $this->applyRateLimitedScenario();
            throw $e;
        } catch (SqualoMail_WooCommerce_ServerError $e) {
            mailchimp_error('promo_code_submit.error', mailchimp_error_trace($e, "error updating promo rule #{$this->id} :: {$code->getCode()}"));
            throw $e;
        } catch (SqualoMail_WooCommerce_Error $e) {
            mailchimp_error('promo_code_submit.error', mailchimp_error_trace($e, "error updating promo rule #{$this->id} :: {$code->getCode()}"));
            throw $e;
        } catch (\Exception $e) {
            $promo_code = isset($code) ? "code {$code->getCode()}" : "id {$this->id}";
            mailchimp_error('promo_code_submit.exception', mailchimp_error_trace($e, "error updating promo rule :: {$promo_code}"));
            throw $e;
        } catch (\Error $e) {
            $promo_code = isset($code) ? "code {$code->getCode()}" : "id {$this->id}";
            mailchimp_error('promo_code_submit.error', mailchimp_error_trace($e, "Error :: #{$promo_code}"));
            throw $e;
        }
    }
}
