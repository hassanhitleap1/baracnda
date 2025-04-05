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
            var attributeName = $(this).attr('data-attribute-name');
           
            // Initialize array for this attribute if it doesn't exist
            if (!options[attributeId]) {
                options[attributeId] = []; // Initialize as array
            }
            
            // Add the option to the array
            options[attributeId].push({
                attributeOptionId: attributeOptionId,
                value: optionValue,
                attributeId: attributeId,
                attributeName: attributeName
            });

            // Group attributes by their ID
            if (!selectedAttributes[attributeId]) {
                selectedAttributes[attributeId] = [];
            }
            selectedAttributes[attributeId].push({
                attributeOptionId: attributeOptionId,
                value: optionValue,
                attributeId: attributeId,
                attributeName: attributeName
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
            // const variantAttributes = variant.map(attr => ({
            //     attributeId: attr.attributeId,
            //     optionId: attr.attributeOptionId
            // }));
    
            let attributeDropdowns = '';
            variant.forEach(attr => {
                attributeDropdowns += `
                    <div class="col-2">
                        <div class="form-group">
                            <label class="control-label">${attr.attributeName}</label>
                            <select class="form-control"  name="Product[variant_attribute_option][${variantId}][${attr.attributeId}]">
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

    // Prevent form submission on "Enter" in the search input
    $('#variantSearchInput').on('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevent form submission
        }
    });

    // Search for variants
    $('#variantSearchInput').on('input', function () {
        const searchTerm = $(this).val();
        if (searchTerm.length < 2) {
            $('#variantSearchResults').html('');
            return;
        }

        $.ajax({
            url: SITE_URL + '/variants/search', // Adjust the URL to your endpoint
            method: 'GET',
            data: { term: searchTerm },
            success: function (data) {
                let resultsHtml = '';
                data.forEach(variant => {
                    resultsHtml += `
                        <div class="dropdown-item d-flex align-items-center">
                            <img src="${variant.image}" alt="${variant.name}" class="img-thumbnail" style="width: 30px; height: 30px; margin-right: 10px;">
                            <span>${variant.name}</span>
                            <button class="btn btn-primary btn-sm ml-auto add-variant-btn" data-id="${variant.id}" data-name="${variant.name}" data-image="${variant.image}" data-price="${variant.price}">
                                Add
                            </button>
                        </div>
                    `;
                });
                $('#variantSearchResults').html(resultsHtml);
            }
        });
    });

    // Add variant to the order items list
    $(document).on('click', '.add-variant-btn', function (event) {
        event.preventDefault(); // Prevent form submission
        const variantId = $(this).data('id');
        const variantName = $(this).data('name');
        const variantImage = $(this).data('image');
        const variantPrice = $(this).data('price');

        // Check if the variant is already added
        if ($(`#orderItems .variant-item[data-id="${variantId}"]`).length > 0) {
            return; // Do nothing if the variant is already added
        }

        const variantHtml = `
            <div class="row mb-3 variant-item" data-id="${variantId}">
                <div class="col-2">
                    <input type="hidden" name="Orders[OrderItems][${variantId}][variant_id]" value="${variantId}">
                    <input type="hidden" name="Orders[OrderItems][${variantId}][variant_image]" value="${variantImage}">
                    <img src="${variantImage}" alt="${variantName}" class="img-thumbnail w-40">
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="Orders[OrderItems][${variantId}][variant_name]" value="${variantName}" readonly>
                </div>
                <div class="col-2">
                    <input type="number" class="form-control" name="Orders[OrderItems][${variantId}][variant_quantity]" value="1" min="1">
                </div>
                <div class="col-2">
                    <input type="text" class="form-control" name="Orders[OrderItems][${variantId}][variant_price]" value="${variantPrice}" readonly>
                </div>
                <div class="col-2">
                    <button class="btn btn-danger btn-sm delete-variant-btn">Delete</button>
                </div>
            </div>
        `;

        $('#orderItems').append(variantHtml);

        // Disable the "Add" button and mark it as checked
        $(this).prop('disabled', true).text('Added');
    });

    // Delete variant from the order items list
    $(document).on('click', '.delete-variant-btn', function () {
        const variantItem = $(this).closest('.variant-item');
        const variantId = variantItem.data('id');

        // Remove the variant item
        variantItem.remove();

        // Re-enable the "Add" button for the removed variant
        $(`#variantSearchResults .add-variant-btn[data-id="${variantId}"]`).prop('disabled', false).text('Add');
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
