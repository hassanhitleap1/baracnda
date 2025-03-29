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
        var cost = $("#products-cost").val() ?? 0; 
        var quantity = $("#products-quantity").val() ?? 0; 
        var options = {}; // Changed to object
        
        // Loop through all checked checkboxes
        $('input[type="checkbox"].attribute-option:checked').each(function () {
            var attributeId = $(this).attr('data-attribute-id');
            var attributeOptionId = $(this).attr('data-attribute-option-id');
            var optionValue = $(this).val();
           
            // Initialize array for this attribute if it doesn't exist
            if (!options[attributeId]) {
                options[attributeId] = []; // Initialize as array
            }
            
            // Add the option to the array
            options[attributeId].push({
                attributeOptionId: attributeOptionId,
                value: optionValue,
                attributeId: attributeId
            });

            // Group attributes by their ID
            if (!selectedAttributes[attributeId]) {
                selectedAttributes[attributeId] = [];
            }
            selectedAttributes[attributeId].push({
                attributeOptionId: attributeOptionId,
                value: optionValue,
                attributeId: attributeId
            });
        });

        // Rest of your code remains the same...
        // Generate all combinations of selected attributes
        var variants = generateCombinations(selectedAttributes);
        console.log(variants)
        
        // Clear previous variants
        $('#variants-generated').html('');
        let isDefaultChecked = '';
        
        // Create input fields for each variant
        variants.forEach((variant, index) => {
            let variantName = variant.map(attr => attr.value).join(' ');
            let variantId = index;
            const variantAttributes = variant.map(attr => ({
                attributeId: attr.attributeId,
                optionId: attr.attributeOptionId
            }));
            const variantAttributesJson = JSON.stringify(variantAttributes);

            let attributeDropdowns = '';
            variant.forEach(attr => {
                attributeDropdowns += `
                    <div class="col-2">
                        <div class="form-group">
                            <label class="control-label">${attr.attributeId} options</label>
                            <select class="form-control"  disabled name="Product[variants][${variantId}][attributes][${attr.attributeId}]">
                                ${options[attr.attributeId].map(opt => 
                                    `<option value="${opt.attributeOptionId}" ${opt.attributeOptionId == attr.attributeOptionId ? 'selected' : ''}>
                                        ${opt.value}
                                    </option>`
                                ).join('')}
                            </select>
                        </div>
                    </div>
                `;
            });

            isDefaultChecked = index === 0 ? 'checked' : '';
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
                                <input type="radio" class="form-check-input" name="Product[variant_is_default][${variantId}]"  ${isDefaultChecked}>
                                <label class="form-check-label">Default</label>
                            </div>
                        </div>
                    </div>
                    ${attributeDropdowns}
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
