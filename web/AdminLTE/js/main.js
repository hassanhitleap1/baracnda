let SITE_URL = getSiteUrl() ;


function getSiteUrl() {
    let site_url=window.location.host;
    if (site_url=='localhost:8080'){
        return '';
    }
    return 'https://neutron-sys.com';
}


$(document).ready(function() {
    $('#generateVariants').on('click', function() {
        var selectedAttributes = [];

        // Loop through all checked checkboxes
        $('input[type="checkbox"]:checked').each(function() {
            var attributeId = $(this).attr('data-attribute-id'); // Get the attribute ID
            var attributeOptionId = $(this).attr('data-attribute-option-id'); // Get the attribute option ID
            var price = $("#products-price").val()??0;
            var optionValue = $(this).val(); // Get the option value
            // Add the selected attribute to the array
            if (attributeId && attributeOptionId && optionValue) {
                selectedAttributes.push({
                    attributeId: attributeId,
                    attributeOptionId: attributeOptionId,
                    value: optionValue
                }); 

                $('#variants-generated').html(`
                    <div class="row">
                        <input type="text" name="Product[variant_name][1]" value="${optionValue}">
                        <input type="text" name="Product[variant_price][1]" value="${price}">
                        <input type="text" name="Product[variant_quantity][1]" value="1">
                        <input type="text" name="Product[variant_is_default][1]" value="${attributeId}">
                        <input type="text" name="Product[variant_attribute_id][1]" value="${attributeId}">
                        <input type="text" name="Product[variant_attribute_option_id][1]" value="${attributeOptionId}">
                        
                    </div>
                `);
            }
        
        });



        
    });
});
