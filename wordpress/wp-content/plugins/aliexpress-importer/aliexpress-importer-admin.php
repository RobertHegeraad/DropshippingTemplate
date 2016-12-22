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

    .importer .form-section {
        margin: 5px;
        padding-top: 10px;
    }
    .importer label {
        display: block;
    }
    .importer input[type=text], textarea {
        width: 80%;
        padding: 10px;
        border-radius: 4px;
        border: 1px solid #dbdbdb;
    }
    .importer select {
        width: 80%;
    }
    .importer button {
        padding: 10px 14px;
        border-radius: 4px;
        border: 1px solid #dbdbdb !important;
        background-color: #f9f9f9;
        color: #333333;
    }
    .importer .product-price {
        width: 20% !important;
    }

    .importer img {
        width: 200px;
        height: 200px;;
    }
    .importer .sub-images img {
        width: 50px;
        height: 50px;
        margin: 5px;
    }

</style>

<div class="importer">

    <input id="product-url" type="text" name="product-url" value="https://nl.aliexpress.com/item/2016-With-Iron-Core-New-Quality-Deluxe-COS-Albus-Dumbledore-Magic-Wand-of-Harry-Potter-Magical/32691894636.html">

    <button id="get-product">Get Product</button>

    <hr>

    <h1>Import</h1>

    <form method="post" action="">

        <input id="product-url" type="hidden" name="product-url" value="">

        <div class="form-section">
            <label for="product-title">Title</label>
            <input id="product-title" name="product-title" type="text">
        </div>

        <div class="form-section">
            <label for="product-description">Description</label>
            <textarea id="product-description" name="product-description"></textarea>
        </div>

        <div class="form-section">
            <label for="product-price">Price</label>
            <input id="product-price" name="product-price" type="text">
        </div>

        <div class="form-section">
            <label for="product-image">Product Image</label>
            <input id="product-image" name="product-image" type="hidden">
            <img id="product-image-src" alt="" src="">
        </div>

        <div class="form-section">
            <label for="product-image">Product (Sub)image</label>
            <div class="sub-images"></div>
        </div>

        <div class="form-section">
            <label for="product-categories">Categories</label>
            <?php foreach(get_categories(array('taxonomy' => 'product_cat')) as $category): ?>
                <label for="<?= $category->term_id ?>"><input type="checkbox" class="product-category" name="product-categories[]" id="<?= $category->term_id ?>" value="<?= $category->term_id ?>"/><?= $category->name ?></label>
            <?php endforeach; ?>
        </div>

        <div class="form-section">
            <label for="product-tags">Tags (comma separated)</label>
            <input id="product-tags" name="product-tags" type="text">
        </div>

        <div class="form-section">
            <label for="product-active">Set as active</label>
            <input id="product-active" name="product-active" type="checkbox" checked>
        </div>

        <div class="form-section">
            <input type="submit" name="import-product" id="import-product" value="Import Product">
        </div>

    </form>
</div>

<script>

    (function($) {

        $(document).on("click", "#get-product", getProduct);

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

                    console.log("Name: " + productTitle);
                    console.log("Price: " + productPrice);
                    console.log("Image: " + productImage);
                    for(var i=0; i<productImages.length; i++) {
                     	var image = $(productImages[i]).attr('src');
                        console.log("Subimage: " + image);

                        productImagesArray.push(image);
                     }

                    $("#product-url").val(productUrl);
                    $("#product-title").val(productTitle);
                    $("#product-price").val(productPrice);
                    $("#product-image").val(productImage);
                    $("#product-image-src").attr('src', productImage);

                    var subImagesHtml = '<input id="product-image" name="product-images[]" type="hidden" value="{value}"><img id="product-image-src" alt="" src="{src}">';
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
            $("#product-price").val("");
            $("#product-image").val("");
            $("#product-image-src").attr('src', "");
            $(".sub-images").html("");
            $("#product-tags").val("");
            $(".product-category").prop( "checked", false );
        }

    })(jQuery);

</script>