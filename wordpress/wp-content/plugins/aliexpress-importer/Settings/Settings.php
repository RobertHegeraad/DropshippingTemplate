<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 30-12-2016
 * Time: 15:18
 */

const APP_KEY = 33442;

const DIGITAL_SIGNATURE = 'eXTg3FCKe9I';

const TRACKING_ID = 'antiskimpasaffiliate';

const API_URL = "http://gw.api.alibaba.com/openapi/param2/2/portals.open/";

const PRODUCT_DETAILS_ENDPOINT = "api.getPromotionProductDetail/";

const PRODUCT_DETAILS_FIELDS = "?fields=productId,productTitle,productUrl,imageUrl,originalPrice,salePrice,discount,evaluateScore,commission,commissionRate,30daysCommission,volume,packageType,lotNum,validTime,storeName,storeUrl";

const PRODUCT_PROMOTION_LINKS_ENDPOINT = "api.getPromotionLinks/";

const PROMOTION_LINK_URL = "http://s.click.aliexpress.com/deep_link.htm";

const PROMOTION_LINK_FIELDS = "?dl_target_url=%s&aff_short_key=%s";