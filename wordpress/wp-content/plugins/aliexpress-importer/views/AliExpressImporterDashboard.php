<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 30-12-2016
 * Time: 18:03
 */
?>

<style>

    /* IMPORTER ---------------------------------------------------------------------------------------------------------------------------- */

    .importer {
        margin: 20px 12px;
    }

    .importer .success {
        color: green;
    }
    .importer .error {
        color: red;
    }

    .importer .form-section {
        margin-bottom: 10px;
    }
    .importer label {
        display: block;
    }
    .importer input[type=text], textarea {
        width: 100%;
        padding: 10px;
        border-radius: 4px;
        border: 1px solid #dbdbdb;
    }
    .importer button, .importer .btn {
        padding: 10px 14px;
        border-radius: 4px;
        border: 1px solid #dbdbdb !important;
        background-color: #f9f9f9;
        color: #333333;
    }

    .importer #product-url-input {
        width: 80%;
    }

    .importer .product-price {
        width: 20% !important;
    }

    .importer img {
        width: 200px;
        height: 200px;
    }
    .importer .sub-images img, .importer .skuProductImage {
        width: 50px;
        height: 50px;
        margin: 10px;
    }

    .importer .product-images-form-section {
        overflow: hidden;
        padding-top: 5px;
    }
    .importer .product-images-container {
        position: relative;
        float: left;
    }

    .importer .product-images-container .product-images-remove {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: white;
        color: #333333;
        box-shadow: 0 1px 2px rgba(0,0,0,0.4);
        border-radius: 50%;
        text-align: center;
        padding: 2px 8px 2px;
        font-family: Helvetica;
        cursor: pointer;
    }

    .importer .product-image-select {
        border: 2px solid #f9f9f9;
    }
    .importer .product-image-selected {
        border: 2px solid #0D72B2;
    }

    .importer table {
        width: 100%;
    }

    .importer table tr td {
        vertical-align: top;
        padding: 6px 8px;
        border: none;
    }

    .importer table thead tr,
    .importer table tfoot tr {
        background-color: white;
        color: #32373c;
    }

    .importer table tbody tr {
        background-color: #f9f9f9;
        color: #32373c;
    }

    .importer table tr td.product-images-col {
        width: 160px;
    }

    .importer table tr td.product-title-col {
        min-width: 300px;
    }
    .importer table tr td.product-price-col {
        width: 100px;
    }
    .importer table tr td.product-price-col {
        width: 200px;
    }
    .importer table tr td.product-active-col {
        width: 50px;
        text-align: center;
    }

    .importer .remove-skuProductRow {
        cursor: pointer;
        text-decoration: underline;
    }

</style>

<div class="importer">

    <h1>AliExpress Importer</h1>

    <p>
        https://nl.aliexpress.com/item/2016-With-Iron-Core-New-Quality-Deluxe-COS-Albus-Dumbledore-Magic-Wand-of-Harry-Potter-Magical/32691894636.html
    </p>
    <p>
        https://nl.aliexpress.com/item/Magnetische-kabel-nylon-gevlochten-micro-usb-magnectic-kabel-charge-kabel-magneet-snelle-oplaadkabel-voor-xiaomi-samsung/32725055956.html
    </p>
    <p>
        https://nl.aliexpress.com/item/Original-Razer-Kraken-Pro-Gaming-Headset-Game-Headphone-Computer-Earphone-Noise-Isolating-Earbuds-With-Mic-BOX/32601753013.html
    </p>
    <p>
        https://www.aliexpress.com/item/Super-3W-External-Aquarium-Filter-Waterfall-Water-Pumps-2sizes-for-choice-Active-Carbon-Sponge-Board-for/32325415503.html?spm=2114.01010208.3.128.Oss093&ws_ab_test=searchweb0_0,searchweb201602_6_10065_10068_10000032_10000025_10000029_10000028_10060_10062_10056_10055_10054_10059_10099_10000022_10000012_10103_10102_10000015_10096_10000018_10000019_10052_10053_10107_10050_10106_10051_10000009_10084_10083_10080_10082_10081_10110_10111_10112_10113_10114_10115_10037_10033_10032_10000044_10078_10079_10077_10073_429_10000035-10050_10110_10102,searchweb201603_3,afswitch_2,single_sort_1_total_tranpro_desc&btsid=bfa1675d-02b2-4b0e-a128-3677c35324f9
    </p>
    <input id="product-url-input" type="text" name="product-url-input" placeholder="Enter an AliExpress product URL" value="https://www.aliexpress.com/item/Super-3W-External-Aquarium-Filter-Waterfall-Water-Pumps-2sizes-for-choice-Active-Carbon-Sponge-Board-for/32325415503.html?spm=2114.01010208.3.128.Oss093&ws_ab_test=searchweb0_0,searchweb201602_6_10065_10068_10000032_10000025_10000029_10000028_10060_10062_10056_10055_10054_10059_10099_10000022_10000012_10103_10102_10000015_10096_10000018_10000019_10052_10053_10107_10050_10106_10051_10000009_10084_10083_10080_10082_10081_10110_10111_10112_10113_10114_10115_10037_10033_10032_10000044_10078_10079_10077_10073_429_10000035-10050_10110_10102,searchweb201603_3,afswitch_2,single_sort_1_total_tranpro_desc&btsid=bfa1675d-02b2-4b0e-a128-3677c35324f9">

    <button id="get-product">Get Product</button>

    <hr>

    <form method="post" action="">

        <input id="product-id" type="hidden" name="product-id" value="">
        <input id="product-stock" type="hidden" name="product-stock" value="">
        <input id="product-url" type="hidden" name="product-url" value="">
        <input id="product-promotion-url" type="hidden" name="product-promotion-url" value="">
        <input id="product-store-name" type="hidden" name="product-store-name" value="">
        <input id="product-store-url" type="hidden" name="product-store-url" value="">
        <input id="product-thumbnail-index" type="hidden" name="product-thumbnail-index" value="">

        <table>

            <thead>
                <tr>
                    <td>Images (Select the thumbnail)</td>
                    <td>Title & description</td>
                    <td>Price & sale price</td>
                    <td>Categories</td>
                    <td>Tags (comma separated)</td>
                    <td class="product-active-col"><label for="product-active">Activate</label></td>
                </tr>
            </thead>

            <tbody id="product-form-tbody">
                <tr>
                    <td class="product-images-col">
                        <div class="form-section product-images-form-section">
                            <div class="sub-images"></div>
                        </div>
                    </td>
                    <td class="product-title-col">
                        <div class="form-section">
                            <textarea id="product-title" rows="4" name="product-title" type="text" placeholder="title"></textarea>
                        </div>

                        <div class="form-section">
                            <textarea id="product-description" rows="10" name="product-description" placeholder="description"></textarea>
                        </div>
                    </td>
                    <td class="product-price-col">
                        <div class="form-section">
                            <input id="product-price" name="product-price" type="text" placeholder="price">
                        </div>
                        <div class="form-section">
                            <input id="product-sale-price" name="product-sale-price" type="text" placeholder="sale price">
                        </div>
                    </td>
                    <td>
                        <div class="form-section">
                            <?php foreach(get_categories(array('taxonomy' => 'product_cat', 'hide_empty' => 0)) as $category): ?>
                                <label for="<?= $category->term_id ?>"><input type="checkbox" class="product-category" name="product-categories[]" id="<?= $category->term_id ?>" value="<?= $category->term_id ?>"/><?= $category->name ?></label>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <td class="product-tags-col">
                        <div class="form-section">
                            <input id="product-tags" name="product-tags" type="text" placeholder="tags">
                        </div>
                    </td>
                    <td class="product-active-col">
                        <div class="form-section">
                            <input id="product-active" name="product-active" type="checkbox" checked>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-section form-product-id"></div>
                    </td>
                    <td colspan="5">
                        <div class="form-section form-product-url"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-section form-product-is-affiliate"></div>
                    </td>
                    <td colspan="5">
                        <div class="form-section form-product-promotion-url"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-section form-product-store-name"></div>
                    </td>
                    <td colspan="5">
                        <div class="form-section form-product-store-url"></div>
                    </td>
                </tr>
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="6">
                        <input class="btn" type="submit" name="import-product" id="import-product" value="Import Product">
                    </td>
                </tr>
            </tfoot>

        </table>

    </form>

</div>

<script>

    (function($) {

        $(document).on("click", "#get-product", getProduct);

        $(document).on("click", ".product-images-remove", removeProductImage);

        $(document).on("click", ".remove-skuProductRow", removeSkuProductRow);

        $(document).on("click", ".product-image-select", selectProductImageThumbnail);

        function getProduct() {
            clearForm();

            var aliExpressProductUrl = $("#product-url-input").val();
            $("#get-product").html("Getting product...").attr('disabled', 'disabled');

            $.ajax({
                type: "GET",
                url: ajaxurl,
                dataType: "json",
                data: { action: 'AliExpressImporterGetProduct', url: aliExpressProductUrl },
                success: mapProduct
            });
        }

        function mapProduct(data) {
            console.log(data);

            var productId,
                productTitle,
                productImages = [],
                productThumbnail,
                $productImages,
                productPrice,
                productStock,
                productUrl,
                promotionUrl,
                storeUrl,
                storeName,
                productIsAffiliate = false,
                skuProducts,
                skuProductsFromUrl,
                productAttributes = data.results.productAttributes;

            if(data.results.URL.success) {

                var $html = $(data.results.URL.product);

                productId = data.results.URL.productId;
                productTitle = $html.find(".product-name").html();
                productPrice = $html.find(".p-price").html();
                productUrl = data.results.URL.product.productUrl;
                storeName = $html.find(".shop-name a").html();
                storeUrl = $html.find(".shop-name a").attr('href');

                $productImages = $html.find('.img-thumb-item img');
                for (var i = 0; i < $productImages.length; i++) {
                    productImages.push($productImages[i].src);
                }

                var loadedSkuIds = [];
                skuProducts = [];

                // Get product variations
                skuProductsFromUrl = JSON.parse(/var skuProducts=(\[.+\])/.exec(data.results.URL.product)[1]);
                var skuProductImage;
                for(var j=0; j<skuProductsFromUrl.length; j++) {
                    if(skuProductsFromUrl[j].skuPropIds == "") break;

                    // TODO: Import product variation combination
                    var skuId = skuProductsFromUrl[j].skuPropIds.split(",")[0];
                    if($.inArray(skuId, loadedSkuIds) != -1) {
                        continue;
                    }
                    loadedSkuIds.push(skuId);

                    skuProductsFromUrl[j].skuProductStocks = skuProductsFromUrl[j].skuVal.availQuantity;
                    skuProductsFromUrl[j].skuProductSkus = productId + '-' + skuId;

                    skuProductsFromUrl[j].skuProductTitle = $html.find("a[data-sku-id=" + skuId + "]").attr('title');
                    if(typeof skuProductsFromUrl[j].skuProductTitle == "undefined") {
                        skuProductsFromUrl[j].skuProductTitle = $html.find("a[data-sku-id=" + skuId + "] span").html();
                    }

                    skuProductImage = $html.find("a[data-sku-id=" + skuId + "] img").attr('src');
                    if(typeof skuProductImage != "undefined") {
                        skuProductsFromUrl[j].skuProductImage = skuProductImage;
                    }

                    skuProducts.push(skuProductsFromUrl[j]);
                }

                console.log(skuProducts);
            }

            if(data.results.API.success) {
                /*
                 30daysCommission: "US $0.22"
                 discount: "19%"
                 evaluateScore: "4.9"
                 imageUrl: "https://ae01.alicdn.com/kf/HTB1JgoyNXXXXXb0XXXXq6xXFXXXB/2016-With-Iron-Core-New-Quality-Deluxe-COS-Albus-Dumbledore-Magic-Wand-of-Harry-Potter-Magical.jpg"
                 lotNum: 1
                 originalPrice: "US $14.99"
                 packageType: "piece"
                 productId: 32691894636
                 productTitle: "2016 With Iron Core New Quality Deluxe COS Albus Dumbledore Magic Wand of Harry Potter Magical Wands with Gift Box Packing"
                 productUrl: "https://www.aliexpress.com/item/2016-With-Iron-Core-New-Quality-Deluxe-COS-Albus-Dumbledore-Magic-Wand-of-Harry-Potter-Magical/32691894636.html"
                 promotionUrl: null
                 salePrice: "US $12.14"
                 storeName: "NIGISLOW Professional Festive Supplier"
                 storeUrl: "https://www.aliexpress.com/store/1771295"
                 validTime: "2017-01-25"
                 volume: "17"
                 */

                productId = data.results.API.product.productId;
                productTitle = data.results.API.product.productTitle;
                productPrice = data.results.API.product.originalPrice;
                productUrl = data.results.API.product.productUrl;
                promotionUrl = data.results.API.product.promotionUrl;
                storeUrl = data.results.API.product.storeUrl;
                storeName = data.results.API.product.storeName;
                productIsAffiliate = true;
            }

            if(!data.results.API.success && !data.results.URL.success) {
                alert('Something went wrong');
            }

            displayProduct(
                productId,
                productTitle,
                productImages,
                productPrice,
                productStock,
                productUrl,
                promotionUrl,
                storeUrl,
                storeName,
                productIsAffiliate,
                skuProducts,
                productAttributes
            );
        }

        function clearForm() {
            $("#product-id").val("");
            $("#product-url").val("");
            $("#product-promotion-url").val("");
            $("#product-store-url").val("");
            $("#product-store-name").val("");
            $("#product-title").val("");
            $("#product-description").val("");
            $("#product-stock").val("");
            $("#product-price").val("");
            $("#product-sale-price").val("");
            $("#product-image").val("");
            $("#product-image-src").attr('src', "");
            $("#product-thumbnail-index").val("");
            $(".sub-images").html("");
            $("#product-tags").val("");
            $(".product-category").prop( "checked", false );

            $(".form-product-id").html("");
            $(".form-product-url").html("");
            $(".form-product-is-affiliate").html("");
            $(".form-product-stock").html("");
            $(".form-product-promotion-url").html("");
            $(".form-product-store-name").html("");
            $(".form-product-store-url").html("");

            $(".sku-product-row").remove();
        }

        function displayProduct(
            productId,
            productTitle,
            productImages,
            productPrice,
            productStock,
            productUrl,
            promotionUrl,
            storeUrl,
            storeName,
            productIsAffiliate,
            skuProducts,
            productAttributes)
        {
            $("#product-id").val(productId);
            $("#product-title").val(productTitle);
            $("#product-stock").val(productStock);
            $("#product-url").val(productUrl);
            $("#product-promotion-url").val(promotionUrl);
            $("#product-store-url").val(storeUrl);
            $("#product-store-name").val(storeName);
            $("#product-thumbnail-index").val(0);

            $(".form-product-id").html(productId);
            $(".form-product-url").html(productUrl);
            $(".form-product-is-affiliate").html(productIsAffiliate ? '<span class="success">Affiliate</span>' : '<span class="error">No affiliate</span>');
            $(".form-product-stock").html(productStock);
            $(".form-product-promotion-url").html(promotionUrl);
            $(".form-product-store-name").html(storeName);
            $(".form-product-store-url").html(storeUrl);

            var formattedPrice = productPrice.match(/\d+(?:\.\d{1,2})?/g)[0];
            $("#product-price").val(formattedPrice);
            $("#product-sale-price").val(formattedPrice);

            var subImagesHtml = '<div class="product-images-container"><input id="product-image" name="product-images[]" type="hidden" value="{value}"><img id="product-image-src" class="product-image-select" data-index="{index}" alt="" src="{src}"><div class="product-images-remove" title="Click to remove">x</div></div>';
            for(var i=0; i<productImages.length; i++) {
                html = subImagesHtml.replace("{src}", productImages[i]);
                html = html.replace("{value}", productImages[i]);
                html = html.replace("{index}", i);
                $(".sub-images").append(html);
            }

            console.log("--------------------");
            console.log(skuProducts);

            if(skuProducts.length > 1) {
                for (var j = 0; j < skuProducts.length; j++) {
                    var html = '<tr class="sku-product-row sku-product-row-' + j + '">';
                    html += '<td><span class="remove-skuProductRow" data-index="' + j + '">Remove</span><br/>';
                    console.log(skuProducts[j]);
                    if(typeof skuProducts[j].skuProductImage != "undefined") {
                        html += '<img class="skuProductImage" src="' + skuProducts[j].skuProductImage + '"/>';
                    }
                    html += '<input type="hidden" name="skuProductImages[]" value="' + skuProducts[j].skuProductImage + '"></td>';
                    html += '<td><input type="text" name="skuProductTitles[]" value="' + skuProducts[j].skuProductTitle + '"/></td>';
                    html += '<td><input type="text" name="skuProductPrices[]" value="' + formattedPrice + '"/></td>';

                    html += '<td><select name="skuProductAttribute[]">';
                    for(var a = 0; a < productAttributes.length; a++) {
                        html += '<option value="attribute_pa_' + productAttributes[a].attribute_name + '">' + productAttributes[a].attribute_label + '</option>';
                    }
                    html += '</select></td>';

                    html += '<td>SKU: ' + skuProducts[j].skuProductSkus + '<input type="hidden" name="skuProductSkus[]" value="' + skuProducts[j].skuProductSkus + '"/></td>';
                    html += '<td>Stock: ' + skuProducts[j].skuVal.availQuantity + '<input type="hidden" name="skuProductStocks[]" value="' + skuProducts[j].skuVal.availQuantity + '"/></td>';
                    html += '</tr>';
                    $("#product-form-tbody").append(html);
                }
            }

            $("#get-product").html("Get product").removeAttr('disabled');
        }

        function selectProductImageThumbnail() {
            var index = $(this).data('index');
            $(".product-image-select").removeClass("product-image-selected");
            $(this).addClass("product-image-selected");
            $("#product-thumbnail-index").val(index);
        }

        function removeSkuProductRow() {
            var index = $(this).data('index');

            $(".sku-product-row-" + index).remove();
        }

        function removeProductImage() {
            $(this).parent(".product-images-container").remove();
        }

    })(jQuery);

</script>