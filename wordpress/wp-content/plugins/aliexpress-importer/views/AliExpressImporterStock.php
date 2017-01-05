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

                if(product.product_html.success) {
                    var stock = /window\.runParams\.totalAvailQuantity=([0-9]+);/.exec(product.product_html.product);

                    UpdateStockForProduct(product.ID, stock[1], product.product_url);
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

        function UpdateStockForProduct(product_id, product_stock, product_url) {
            $.ajax({
                type: "GET",
                url: ajaxurl,
                dataType: "json",
                data: {
                    action: 'AliExpressImporterUpdateStock',
                    product: { product_id: product_id, product_stock: product_stock } },
                success: function(data) {
                    console.log(data);

                    var html = '<li>';
                    html += 'Product ' + product_id;
                    html += ' stock updated to <strong>' + data.results.product.product_stock + '</strong><br/>';
                    html += '<a href="' + product_url + '">View product on AliExpress</a>';
                    html += '</li>';

                    $(".output").append(html);
                }
            });
        }

    })(jQuery);

</script>