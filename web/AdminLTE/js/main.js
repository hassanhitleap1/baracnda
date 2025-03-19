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
        var cost = 0;
        // Loop through all checked checkboxes
        $('input[type="checkbox"].attribute-option:checked').each(function () {
            var attributeId = $(this).attr('data-attribute-id'); // Get the attribute ID
            var attributeOptionId = $(this).attr('data-attribute-option-id'); // Get the attribute option ID
            var optionValue = $(this).val(); // Get the option value
           

            // Group attributes by their ID
            if (!selectedAttributes[attributeId]) {
                selectedAttributes[attributeId] = [];
            }
            selectedAttributes[attributeId].push({
                attributeOptionId: attributeOptionId,
                value: optionValue
            });
        });

        // Generate all combinations of selected attributes
        var variants = generateCombinations(selectedAttributes);

        // Clear previous variants
        $('#variants-generated').html('');
let isDefaultChecked = ''
        // Create input fields for each variant
        variants.forEach((variant, index) => {
            let variantName = variant.map(attr => attr.value).join(' '); // Combine attribute values for the variant name
            let variantId = index + 1; // Unique ID for each variant

        isDefaultChecked=index === 0 ? 'checked' : ''
            $('#variants-generated').append(`
                <div class="row mb-3">
                    <div class="col-3">
                        <div class="form-group">
                            <label class="control-label">Variant Name</label>
                            <input type="text" class="form-control" name="Product[variant_name][${variantId}]" value="${variantName}" aria-required="true">
                            <div class="help-block"></div>
                        </div>
                    </div>
                     <div class="col-2">
                        <div class="form-group">
                            <label class="control-label">Variant Cost</label>
                            <input type="number" class="form-control" name="Product[variant_cost][${variantId}]" value="${cost}" aria-required="true">
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label class="control-label">Variant Price</label>
                            <input type="number" class="form-control" name="Product[variant_price][${variantId}]" value="${price}" aria-required="true">
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label class="control-label">Variant Quantity</label>
                            <input type="number" class="form-control" name="Product[variant_quantity][${variantId}]" value="${quantity}" aria-required="true">
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label class="control-label">Set as Default</label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="Product[variant_is_default]" ${isDefaultChecked}>
                                <label class="form-check-label">Default</label>
                            </div>
                        </div>
                    </div>
                    ${variant.map(attr => `
                        <input type="hidden" name="Product[variant_attribute_id][${variantId}][]" value="${attr.attributeId}">
                        <input type="hidden" name="Product[variant_attribute_option_id][${variantId}][]" value="${attr.attributeOptionId}">
                    `).join('')}
                </div>
            `);
        });

        // Close the modal
        $('#exampleModal').modal('hide');
        $('.btn-secondary[data-bs-dismiss="modal"]').trigger('click');

   

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


$('#products-type').on('click', function () {
    if($(this).val()==='variant'){
       $('#btn-modal-open').css('display', 'block');
    }else{
        $('#btn-modal-open').css('display', 'none');
    }

})
