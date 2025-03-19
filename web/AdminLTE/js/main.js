let SITE_URL = getSiteUrl();


function getSiteUrl() {
    let site_url = window.location.host;
    if (site_url == 'localhost:8080') {
        return '';
    }
    return 'https://neutron-sys.com';
}


$(document).ready(function () {
    $('#generateVariants').on('click', function () {
        var selectedAttributes = {};
        var price = $("#products-price").val() ?? 0;
        var quantity = $("#products-quantity").val() ?? 0;
        // Loop through all checked checkboxes
        $('input[type="checkbox"].attribute-option:checked').each(function () {
            var attributeId = $(this).attr('data-attribute-id'); // Get the attribute ID
            var attributeOptionId = $(this).attr('data-attribute-option-id'); // Get the attribute option ID

            var optionValue = $(this).val(); // Get the option value
            // Add the selected attribute to the array
            if (!selectedAttributes[attributeId]) {
                selectedAttributes[attributeId] = [];
                if (attributeOptionId && optionValue) {
                    selectedAttributes[attributeId].push({
                        attributeOptionId: attributeOptionId,
                        value: optionValue
                    });
                }
            } else {
                if (attributeOptionId && optionValue) {
                    selectedAttributes[attributeId].push({
                        attributeOptionId: attributeOptionId,
                        value: optionValue
                    });
                }
            }

        });

        var variants = generateCombinations(selectedAttributes);
        $('#variants-generated').html('');
        variants.forEach((variant, index) => {
            let variantName = variant.map(attr => attr.value).join(' '); // Combine attribute values for the variant name
            let variantId = index + 1; // Unique ID for each variant
            $('#variants-generated').append(`
                <div class="row">
                    <div class="col-3">
                      <input type="radio" class="form-control" name="Product[variant_is_default]" value="${index == 1 ? 1 : 0}">
                    <div class="form-group field-products-variant_name required">
                    <label class="control-label" for="products-variant_name">variant name</label>
                    <input type="number"  class="form-control" name="Product[variant_name][${variantId}]" value="${variantName}" aria-required="true" aria-invalid="true" />
                    <div class="help-block"></div>
                    </div>
                     </div>
                       <div class="col-3">
                    <div class="form-group field-products-variant_price required">
                        <label class="control-label" for="products-variant_price">variant price</label>
                        <input type="number"  class="form-control" name="Product[variant_price][${variantId}]" value="${price}" aria-required="true" aria-invalid="true" />
                        <div class="help-block"></div>
                    </div>
                    </div>
                     <div class="col-3">
                    <div class="form-group field-products-variant_quantity required">
                        <label class="control-label" for="products-variant_price">variant quantity</label>
                        <input type="number"  class="form-control" name="Product[variant_quantity][${variantId}]" value="${quantity}" aria-required="true" aria-invalid="true" />
                        <div class="help-block"></div>
                    </div>
                     </div>
                    ${variant.map(attr => `
                        <input type="hidden" name="Product[variant_attribute_id][${variantId}][]" value="${attr.attributeId}">
                        <input type="hidden" name="Product[variant_attribute_option_id][${variantId}][]" value="${attr.attributeOptionId}">
                    `).join('')}
                </div>
            `);
        });
        $('#exampleModal').modal('hide');
        $('#exampleModal').hide();
        $('#exampleModal').fadeOut();
        $('#exampleModal').slideUp();
    });
});


function generateCombinations(selectedAttributes) {
    var attributeGroups = Object.values(selectedAttributes); // Convert object to array of attribute groups
    var combinations = [];

    // Recursive function to generate combinations
    function combine(current, index) {
        if (index === attributeGroups.length) {
            combinations.push(current);
            return;
        }
        attributeGroups[index].forEach(attr => {
            combine([...current, { attributeId: Object.keys(selectedAttributes)[index], ...attr }], index + 1);
        });
    }

    combine([], 0); // Start combining from the first attribute group
    return combinations;
}