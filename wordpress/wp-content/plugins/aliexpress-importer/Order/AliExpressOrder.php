<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 30-12-2016
 * Time: 15:31
 */

class AliExpressOrder {

    public function __construct()
    {

    }

    public function GetAliEpxressProductOrderLink($post_id) {
        $product_url = wc_get_product_terms($post_id, 'pa_product_url', array());
        $affiliate_short_key = wc_get_product_terms($post_id, 'pa_affiliate_short_key', array());

        $order_url = $product_url[0];
        if($affiliate_short_key[0] != null) {
            $order_url = PROMOTION_LINK_URL . PROMOTION_LINK_FIELDS;
            $order_url = sprintf($order_url, $product_url[0], $affiliate_short_key[0]);
        }

        $html = "<h2>AliExpress product information</h2>";
        $html .= "<table>";
        $html .= "<tr>";
        $html .= "<td><strong>Order from AliExpress:</strong> </td>";
        $html .= "<td><a href='$order_url' target='_blank'>$order_url</a></td>";
        $html .= "</tr>";

        return $html;
    }

    public function OrderProduct($storeId, $productId) {

    }
}