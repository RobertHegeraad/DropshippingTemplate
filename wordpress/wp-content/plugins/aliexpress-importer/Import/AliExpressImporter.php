<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 30-12-2016
 * Time: 15:16
 */

class AliExpressImporter {

    public function ImportProduct($product) {

        $post_id = $this->InsertProduct($product);

        if(!$post_id) {

            // TODO: Show notification

            return;
        }

        // Affiliate short key
        $this->GetAffiliateShortKeyFromPromotionUrl($product);

        // Images
        $this->ImportImages($post_id, $product);

        // Categories
        $this->ImportCategories($post_id, $product);

        // Tags
        $this->ImportTags($post_id, $product);

        // Price
        $this->ImportPrice($post_id, $product);

        // Product attributes (Promotion Url)
        $this->ImportProductAttributes($post_id, $product);

        // Meta
        $this->ImportMeta($post_id, $product);
    }

    public function ImportProductAjax($product) {
        $this->ImportProduct($product);
    }

    public function ImportProducts($products) {
        // TODO: Bulk import via Excel
    }

    private function InsertProduct($product) {
        return wp_insert_post(array(
            'post_author' => get_current_user_id(),
            'post_content' => $product['product-description'],
            'post_status' => "publish",
            'post_title' => $product['product-title'],
            'post_parent' => '',
            'post_type' => "product",
        ));
    }

    private function ImportImages($post_id, $product) {
        $aliExpressImage = new AliExpressImage();
        $aliExpressImage->UploadProductThumbnail($product['product-images'][$product['product-thumbnail-index']], $post_id);
        @unlink($product['product-images'][0]);
        $aliExpressImage->UploadProductGallery($product['product-images'], $post_id);
    }

    private function ImportCategories($post_id, $product) {
        if(!is_null($product['product-categories'])) {
            $productCategories = array();
            foreach ($product['product-categories'] as $productCategory) {
                $term = get_term_by('id', $productCategory, 'product_cat');
                $productCategories[] = $term->name;
            }
            wp_set_object_terms($post_id, $productCategories, 'product_cat');
        }
    }

    private function ImportTags($post_id, $product) {
        if($product['product-tags'] != "") {
            $productTags = explode(',', $product['product-tags']);
            wp_set_object_terms($post_id, array_filter($productTags), 'product_tag');
        }
    }

    private function ImportPrice($post_id, $product) {
        update_post_meta( $post_id, '_regular_price', $product['product-price'] );
        update_post_meta( $post_id, '_sale_price', ($product['product-sale-price'] == "") ? $product['product-price'] : $product['product-sale-price'] );
    }

    private function ImportProductAttributes($post_id, $product) {
        if($product['product-promotion-url'] == "") {
            return;
        }

        $attributes = array(
            'pa_product_id' => $product['product-id'],
//            'pa_product_promotion_url' => $product['product-promotion-url'],
            'pa_affiliate_short_key' => $product['affiliate_short_key'],
            'pa_product_store_name' => $product['product-store-name'],
            'pa_product_store_url' => $product['product-store-url']
        );

        $product_attributes = array();

        foreach($attributes as $key => $value) {

            wp_set_object_terms($post_id, array($value), $key);

            $product_attributes[$key] = array(
                'name'         => $key,
                'value'        => $value,
                'is_visible'   => '0',
                'is_variation' => '0',
                'is_taxonomy'  => '1'
            );
        }

        update_post_meta($post_id, '_product_attributes', $product_attributes);
    }

    private function ImportMeta($post_id, $product) {
        wp_set_object_terms($post_id, 'simple', 'product_type');
        update_post_meta( $post_id, '_visibility', 'visible' ); // TODO: Set visibility with $product['product-active']
        update_post_meta( $post_id, '_stock_status', 'instock');
        update_post_meta( $post_id, 'total_sales', '0');
        update_post_meta( $post_id, '_downloadable', 'no');
        update_post_meta( $post_id, '_virtual', 'no');
        update_post_meta( $post_id, '_purchase_note', "" );
        update_post_meta( $post_id, '_featured', "no" );
        update_post_meta( $post_id, '_weight', "" );
        update_post_meta( $post_id, '_length', "" );
        update_post_meta( $post_id, '_width', "" );
        update_post_meta( $post_id, '_height', "" );
        update_post_meta( $post_id, '_sku', "");
        update_post_meta( $post_id, '_sale_price_dates_from', "" );
        update_post_meta( $post_id, '_sale_price_dates_to', "" );
        update_post_meta( $post_id, '_price', $product['product-price'] );
        update_post_meta( $post_id, '_sold_individually', "" );
        update_post_meta( $post_id, '_manage_stock', "no" );
        update_post_meta( $post_id, '_backorders', "no" );
        update_post_meta( $post_id, '_stock', "" );
    }

    private function GetAffiliateShortKeyFromPromotionUrl(&$product) {
        if($product['product-promotion-url'] != "") {
            parse_str(parse_url($product['product-promotion-url'])['query'], $query);
            $product['affiliate_short_key'] = $query['aff_short_key'];
        } else {
            $product['affiliate_short_key'] = "";
        }
    }
}