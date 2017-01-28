<style>

    .importer {
        margin: 20px 12px;
    }

    .error {
        color: red;
    }

    .importer button, .importer .btn {
        padding: 10px 14px;
        border-radius: 4px;
        border: 1px solid #dbdbdb !important;
        background-color: #f9f9f9;
        color: #333333;
    }

</style>

<div class="importer">

    <button id="update-stock">Update stock</button>

    <ul class="errors"></ul>

    <ul class="output"></ul>

</div>

<script>

    (function($) {

        $(document).on("click", "#update-stock", updateStock);

        function updateStock() {
            $(".errors").html('');
            $(".output").html('');

            $("#update-stock").html("Updating stock...").attr('disabled', 'disabled');

            $.ajax({
                type: "GET",
                url: ajaxurl,
                dataType: "json",
                data: { action: 'AliExpressImporterGetProducts' },
                success: mapProduct
            });
        }

        function mapProduct(data) {
            console.log(data);

            for(var i=0; i<data.results.length; i++) {
                var product = data.results[i];

                console.log(i);
                console.log(product);
                if(product.product_html.success) {
                    var stock = /window\.runParams\.totalAvailQuantity=([0-9]+);/.exec(product.product_html.product);

                    var variations = [];
                    var skuProducts = JSON.parse(/var skuProducts=(\[.+\])/.exec(product.product_html.product)[1]);
                    if(skuProducts.length > 1)
                    for(var j=0; j<skuProducts.length; j++) {
                        skuProducts[j].skuProductStocks = skuProducts[j].skuVal.availQuantity;
                        skuProducts[j].skuProductSkus = skuProducts[j].skuPropIds.replace(/,/g, '-');

                        variations.push({
                            "variation_id": product.variations[skuProducts[j].skuProductSkus],
                            "stock": skuProducts[j].skuVal.availQuantity
                        });
                    }

                    console.log(skuProducts);
                    console.log(variations);

                    UpdateStockForProduct(product.ID, stock[1], product.product_url, variations);
                } else {
                    var html = '<li>';
                    html += 'Product ' + product.ID;
                    html += ' <span class="error">might be unavailable</span><br/>';
                    html += '<a href="' + product.product_url + '">View product on AliExpress</a>';
                    html += '</li>';

                    $(".errors").append(html);
                }
            }

            $("#update-stock").html("Update stock").removeAttr('disabled');

            if(!data.results) {
                alert('Something went wrong');
            }
        }

        function UpdateStockForProduct(product_id, product_stock, product_url, variations) {
            $.ajax({
                type: "GET",
                url: ajaxurl,
                dataType: "json",
                data: {
                    action: 'AliExpressImporterUpdateStock',
                    product: { product_id: product_id, product_stock: product_stock, variations: variations }
                },
                success: function(data) {
                    console.log(data);

                    var stockError = data.results.product.product_stock < 50 ? "orange" : "black";

                    var html = '<li>';
                    html += 'Product ' + product_id;
                    html += ' total stock updated to <strong style="color: ' + stockError + '">' + data.results.product.product_stock + '</strong><br/>';

                    if(data.results.product.variations)
                    for(var i=0; i<data.results.product.variations.length; i++) {
                        var variation = data.results.product.variations[i];
                        var variationStockError = variation.stock < 50 ? "orange" : "black";

                        html += 'Product variation ' + variation.variation_id;
                        html += ' varation stock updated to <strong style="color: ' + variationStockError + '">' + variation.stock + '</strong><br/>';
                    }

                    html += '<a href="' + product_url + '">View product on AliExpress</a>';
                    html += '</li>';

                    $(".output").append(html);
                }
            });
        }

    })(jQuery);

</script>