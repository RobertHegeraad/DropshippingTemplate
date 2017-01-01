<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 30-12-2016
 * Time: 15:17
 */

class AliExpressApi
{
    private $apiErrors = array(
        '20010000' => '20010000: There is no product to display',
        '400' => '400: No error message',
        '20030000' => '20030000: Required parameters',
        '20030010' => '20030010: Keyword input parameter error',
        '20030020' => '20030020: Category ID input parameter error or formatting errors',
        '20030030' => '20030030: Commission rate input parameter error or formatting errors',
        '20030040' => '20030040: Unit input parameter error or formatting errors',
        '20030050' => '20030050: 30 days promotion amount input parameter error or formatting errors',
        '20030060' => '20030060: Tracking ID input parameter error or limited length',
        '20030070' => '20030070: Unauthorized transfer request',
        '20020000' => '20030000: System Error',
        '20030100' => '20030100: Error! Input parameter Product ID'
    );

    public function GetProductDetails($aliExpressProductUrl) {
        $aliExpressProductId = $this->GetAliExpressProductIdFromUrl($aliExpressProductUrl);

        $result = array(
            'API' => $this->GetProductDetailsFromApi($aliExpressProductId, $aliExpressProductUrl),
            'URL' => $this->GetProductDetailsFromUrl($aliExpressProductId, $aliExpressProductUrl)
        );

        return $result;
    }

    private function GetProductDetailsFromApi($aliExpressProductId, $aliExpressProductUrl) {
        $url = API_URL . PRODUCT_DETAILS_ENDPOINT . APP_KEY . PRODUCT_DETAILS_FIELDS . "&productId=$aliExpressProductId";
        $request = $this->AliExpressRequest($url);

        $data = json_decode($request['body'], true);

        if (isset($data['errorCode'])
            && $data['errorCode'] == 20010000
            && $data['result']['productId'] == $aliExpressProductId)
        {
            $data['result']['promotionUrl'] = $this->GetAffiliateLinkFromUrl($data['result']['productUrl']);

            return array('success' => true, 'message' => '', 'product' => $data['result']);
        } else {
            return array(
                'success' => false,
                'message' => $this->apiErrors[$data['errorCode']],
                'productId' => $aliExpressProductId,
                'productUrl' => $aliExpressProductUrl);
        }
    }

    private function GetProductDetailsFromUrl($aliExpressProductId, $aliExpressProductUrl) {
        $result = $this->GetAliExpressProductViaCurlRequest($aliExpressProductUrl);

        if($result['errno'] != 0 || $result['http_code'] != 200) {
            return array('success' => false, 'message' => "Product not found via URL", 'productId' => array('productId' => $aliExpressProductUrl));
        }

        return array(
            'success' => true,
            'message' => '',
            'product' => $result['content'],
            'productId' => $aliExpressProductId,
            'productUrl' => $aliExpressProductUrl);
    }

    public function GetAffiliateLinkFromUrl($aliExpressProductUrl) {
        $url = API_URL . PRODUCT_PROMOTION_LINKS_ENDPOINT . APP_KEY . "?fields=&trackingId=" . TRACKING_ID . "&urls=$aliExpressProductUrl";
        $request = $this->AliExpressRequest($url);

        $data = json_decode($request['body'], true);

        if (isset($data['errorCode'])
            && $data['errorCode'] == 20010000)
        {
            return $data['result']['promotionUrls'][0]['promotionUrl'];
        } else {
            return $aliExpressProductUrl;
        }
    }

    public function GetProductStock() {

    }

    public function GetProductAvailability() {

    }

    private function AliExpressRequest($url) {
        return wp_remote_get($url, array(
            'headers' => array('Accept-Encoding' => ''),
            'timeout' => 30,
            'user-agent' => 'Toolkit/1.7.3',
            'sslverify' => false));
    }

    private function GetAliExpressProductIdFromUrl($aliExpressProductUrl) {
        $parsed_url = parse_url($aliExpressProductUrl);
        return preg_replace(
            "/\.html|\.htm/",
            '',
            end(explode('/', $parsed_url['path'])));
    }

    private function GetAliExpressProductViaCurlRequest($aliExpressProductUrl) {
        $options = array(
            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0', //set user agent
            CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        $ch      = curl_init( $aliExpressProductUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err     = curl_errno($ch);
        $errmsg  = curl_error($ch);
        $header  = curl_getinfo($ch);
        curl_close($ch);

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }
}