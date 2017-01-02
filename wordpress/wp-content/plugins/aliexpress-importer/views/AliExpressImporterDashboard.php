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
    .importer .sub-images img {
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

</style>

<div class="importer">

    <h1>AliExpress Importer</h1>

    <input id="product-url-input" type="text" name="product-url-input" placeholder="Enter an AliExpress product URL" value="https://nl.aliexpress.com/item/2016-With-Iron-Core-New-Quality-Deluxe-COS-Albus-Dumbledore-Magic-Wand-of-Harry-Potter-Magical/32691894636.html">

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

            <tbody>
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
                productIsAffiliate = false;

            if(data.results.URL.success) {

                var $html = $(data.results.URL.product);

                productId = data.results.URL.productId;
                productTitle = $html.find(".product-name").html();  // TODO: Parse price from string
                productPrice = $html.find(".p-price").html();
                productStock = parseInt($html.find('#j-sell-stock-num').html());
                productUrl = data.results.URL.product.productUrl;
                storeName = $html.find(".shop-name a").html();
                storeUrl = $html.find(".shop-name a").attr('href');

                productThumbnail = $html.find('.ui-image-viewer-thumb-frame img').attr('src');
                $productImages = $html.find('.img-thumb-item img');

                if($productImages.length == 0) {
                    productImages.push(productThumbnail);
                } else {
                    for (var i = 0; i < $productImages.length; i++) {
                        productImages.push($productImages[i].src);
                    }
                }
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
                productIsAffiliate
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
            productIsAffiliate)
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

            var formattedPrice = productPrice.match(/\d+(?:\.\d{1,2})?/g);
            $("#product-price").val(formattedPrice);
            $("#product-sale-price").val(formattedPrice);

            var subImagesHtml = '<div class="product-images-container"><input id="product-image" name="product-images[]" type="hidden" value="{value}"><img id="product-image-src" class="product-image-select" data-index="{index}" alt="" src="{src}"><div class="product-images-remove" title="Click to remove">x</div></div>';
            for(var i=0; i<productImages.length; i++) {
                html = subImagesHtml.replace("{src}", productImages[i]);
                html = html.replace("{value}", productImages[i]);
                html = html.replace("{index}", i);
                $(".sub-images").append(html);
            }

            $("#get-product").html("Get product").removeAttr('disabled');
        }

        function selectProductImageThumbnail() {
            var index = $(this).data('index');
            $(".product-image-select").removeClass("product-image-selected");
            $(this).addClass("product-image-selected");
            $("#product-thumbnail-index").val(index);
        }

        function removeProductImage() {
            $(this).parent(".product-images-container").remove();
        }

    })(jQuery);

</script>