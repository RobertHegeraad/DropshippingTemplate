<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 19-12-2016
 * Time: 21:42
 */
?>

<style>

    /* IMPORTER ---------------------------------------------------------------------------------------------------------------------------- */

    .importer {
        margin: 20px 12px;
    }

    .importer .form-error {
        margin: 10px;
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

    .importer #product-url {
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

    <input id="product-url" type="text" name="product-url" value="https://nl.aliexpress.com/item/2016-With-Iron-Core-New-Quality-Deluxe-COS-Albus-Dumbledore-Magic-Wand-of-Harry-Potter-Magical/32691894636.html">

    <button id="get-product">Get Product</button>

    <hr>

    <h1>IMPORT</h1>

    <form method="post" action="">

        <input id="product-url" type="hidden" name="product-url" value="">

        <table>

            <thead>
                <tr>
                    <td>Images</td>
                    <td>Title & description</td>
                    <td>Price & sale price</td>
                    <td>Categories</td>
                    <td>Tags (comma separated)</td>
                    <td class="product-active-col">Activate</td>
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
                    <td  class="product-tags-col">
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

        function getProduct() {
            clearForm();

            var productUrl = $("#product-url").val();

            console.log(ajaxurl);

            $.ajax({
                type:     "GET",
                url:      ajaxurl,
                dataType: "json",
                data: { action: 'aliexpress_importer_get_product', url: productUrl },
                success: function(data){
                    var $html = $(data);

                    var productTitle = $html.find(".product-name").html();
                    var productPrice = $html.find(".p-price").html();
                    var productImage = $html.find(".ui-image-viewer-thumb-frame img").attr('src');
                    var productImages = $html.find('.img-thumb-item img');
                    var productImagesArray = [];

                    for(var i=0; i<productImages.length; i++) {
                     	var image = $(productImages[i]).attr('src');
                        productImagesArray.push(image);
                     }

                    $("#product-url").val(productUrl);
                    $("#product-title").val(productTitle);
                    $("#product-price").val(productPrice);
                    $("#product-sale-price").val(productPrice);
                    $("#product-image").val(productImage);
                    $("#product-image-src").attr('src', productImage);

                    var subImagesHtml = '<div class="product-images-container"><input id="product-image" name="product-images[]" type="hidden" value="{value}"><img id="product-image-src" alt="" src="{src}"><div class="product-images-remove" title="Click to remove">x</div></div>';
                    for(var i=0; i<productImagesArray.length; i++) {
                        html = subImagesHtml.replace("{src}", productImagesArray[i]);
                        html = html.replace("{value}", productImagesArray[i]);
                        $(".sub-images").append(html);
                    }
                }
            });
        }

        function clearForm() {
            $("#product-title").val("");
            $("#product-description").val("");
            $("#product-price").val("");
            $("#product-sale-price").val("");
            $("#product-image").val("");
            $("#product-image-src").attr('src', "");
            $(".sub-images").html("");
            $("#product-tags").val("");
            $(".product-category").prop( "checked", false );
        }

        function removeProductImage() {
            $(this).parent(".product-images-container").remove();
        }

    })(jQuery);

</script>