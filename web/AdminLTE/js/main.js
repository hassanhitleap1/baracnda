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
                    const isAdded = $(`#orderItems .variant-item[data-id="${variant.id}"]`).length > 0;
                    resultsHtml += `
                        <div class="dropdown-item d-flex align-items-center">
                            <img src="${variant.image}" alt="${variant.name}" class="img-thumbnail" style="width: 30px; height: 30px; margin-right: 10px;">
                            <span>${variant.name}</span>
                            <button class="btn btn-primary btn-sm ml-auto add-variant-btn" data-id="${variant.id}" data-product-id="${variant.product_id}" data-name="${variant.name}" data-image="${variant.image}" data-price="${variant.price}" ${isAdded ? 'disabled' : ''}>
                                ${isAdded ? 'Added' : 'Add'}
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
        const product_id = $(this).data('product-id');

        // Check if the variant is already added
        if ($(`#orderItems .variant-item[data-id="${variantId}"]`).length > 0) {
            return; // Do nothing if the variant is already added
        }

        const variantHtml = `
            <div class="row mb-3 variant-item" data-id="${variantId}">
                <div class="col-2">
                    <input type="hidden" name="Orders[OrderItems][${variantId}][variant_id]" value="${variantId}">
                     <input type="hidden" name="Orders[OrderItems][${variantId}][product_id]" value="${product_id}">
                    <input type="hidden" name="Orders[OrderItems][${variantId}][variant_image]" value="${variantImage}">
                    <img src="${variantImage}" alt="${variantName}" class="img-thumbnail w-40">
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="Orders[OrderItems][${variantId}][variant_name]" value="${variantName}" readonly>
                </div>
                <div class="col-2">
                    <input type="number" class="form-control variant-quantity" name="Orders[OrderItems][${variantId}][variant_quantity]" value="1" min="1">
                </div>
                <div class="col-2">
                    <input type="text" class="form-control variant-price" name="Orders[OrderItems][${variantId}][variant_price]" value="${variantPrice}" readonly>
                </div>
                <div class="col-2">
                    <button class="btn btn-danger btn-sm delete-variant-btn">Delete</button>
                </div>
            </div>
        `;

        $('#orderItems').append(variantHtml);

        // Disable the "Add" button and mark it as checked
        $(this).prop('disabled', true).text('Added');



        updateOrderSummary();

        $('#variantSearchInput').val('');
        $('#variantSearchResults').html('');
    });

    // Delete variant from the order items list
    $(document).on('click', '.delete-variant-btn', function () {
        const variantItem = $(this).closest('.variant-item');
        const variantId = variantItem.data('id');

        // Remove the variant item
        variantItem.remove();

        // Re-enable the "Add" button for the removed variant
        $(`#variantSearchResults .add-variant-btn[data-id="${variantId}"]`).prop('disabled', false).text('Add');

        updateOrderSummary();
     
    });

    // Handle variant selection
    $('.variant-select').on('change', function () {
        const variantId = $(this).val();
        const productId = $(this).data('product-id');
        const url = `${SITE_URL}/orders/get-variant-details`;

        $.ajax({
            url: url,
            method: 'GET',
            data: { variant_id: variantId, product_id: productId },
            success: function (response) {
                if (response.success) {
                    // Update the price, quantity, and other details in the UI
                    $(`#variant-price-${productId}`).text(response.data.price);
                    $(`#variant-quantity-${productId}`).val(response.data.quantity);
                    updateOrderSummary();
                } else {
                    alert('Failed to fetch variant details.');
                }
            },
            error: function () {
                console.error('Error fetching variant details.');
            },
        });
    });

    $('#region-id').on('change', function (e) {
        e.preventDefault();
        if($(this).val() != '') {
            $('#select2-orders-shipping_id').prop('disabled', false);
        }else {
            $('#select2-orders-shipping_id').prop('disabled', true);   
        }
    });
    
    // Handle shipping selection
    $('#select2-orders-shipping_id').on('change', function () {
        const shippingId = $(this).val();
        const regionId = $('#region-id').val();
        const url = `${SITE_URL}/orders/get-shipping-price`;

        $.ajax({
            url: url,
            method: 'GET',
            data: { shipping_id: shippingId, region_id: regionId },
            success: function (response) {
                if (response.success) {
                    // Update the shipping price in the UI
                    $('#shipping-price').text(response.data.price);
                    updateOrderSummary();
                } else {
                    alert('Failed to fetch shipping price.');
                }
            },
            error: function () {
                console.error('Error fetching shipping price.');
            },
        });
    });


    // Trigger order summary update on quantity change
    $('.variant-quantity').on('input', function () {
        updateOrderSummary();
    });

    // Trigger order summary update on discount change
    $('#discount').on('input', function () {
        updateOrderSummary();
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
    if ($(this).val() === 'variant') {
        $('#btn-modal-open').css('display', 'block');
    } else {
        $('#btn-modal-open').css('display', 'none');
    }

})


$(document).on('click', '.delete-item-btn', function (e) {
        e.preventDefault();
        var itemId = $(this).attr('data-id');
        if (confirm('Are you sure you want to delete this item?')) {
            $.post(`${SITE_URL}/orders/delete-item?id=${itemId}`, function (response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Failed to delete item.');
                }
            });
        }
});
// Update order summary
function updateOrderSummary() {
    const subtotal = calculateSubtotal();
    const shippingPrice = parseFloat($('#shipping-price').text()) || 0;
    const discount = parseFloat($('#discount').val()) || 0;

    const total = subtotal + shippingPrice - discount;
    $('#subtotal').text(subtotal.toFixed(2));
    $('#order-subtotal').text(subtotal.toFixed(2));
    $('#total').text(total.toFixed(2));
}


// Calculate subtotal
function calculateSubtotal() {
    subtotal=0;
    $('.variant-price').each(function () {
       
        const price = parseFloat($(this).val()) || 0;

        const quantity = parseInt($(this).closest('.variant-item').find('.variant-quantity').val()) || 0;
     
        subtotal += price * quantity;

    });
    return subtotal;
}



$(document).on('click', '#delivery-status-dropdown', function (e) {
    var deliveryStatus = $(this).val();
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('urlParams', urlParams);
    const id = urlParams.get('id');
    $.post(`${SITE_URL}/orders/update-delivery-status?id=${id}`, {delivery_status: deliveryStatus}, function (response) {
        if (response.success) {
            alert('Delivery status updated successfully.');
            location.reload();
        } else {
            alert('Failed to update delivery status.');
        }
    });
});



 // Prevent form submission on "Enter" in the search input
 
$(document).on('input', '#variantSearchInputInView', function (event) {
    event.preventDefault(); // Prevent form submission
    const searchTerm = $(this).val();
    if (searchTerm.length < 2) {
        $('#variantSearchResultsView').html('');
        return;
    }

    $.ajax({
        url: SITE_URL + '/variants/search', // Adjust the URL to your endpoint
        method: 'GET',
        data: { term: searchTerm },
        success: function (data) {
            let resultsHtml = '';
            data.forEach(variant => {
                const isAdded = $(`#orderItems .variant-item[data-id="${variant.id}"]`).length > 0;
                resultsHtml += `
                    <div class="dropdown-item d-flex align-items-center">
                        <img src="${variant.image}" alt="${variant.name}" class="img-thumbnail" style="width: 30px; height: 30px; margin-right: 10px;">
                        <span>${variant.name}</span>
                        <button class="btn btn-primary btn-sm ml-auto add-variant-btn-view" data-id="${variant.id}" data-product-id="${variant.product_id}" data-name="${variant.name}" data-image="${variant.image}" data-price="${variant.price}" ${isAdded ? 'disabled' : ''}>
                            ${isAdded ? 'Added' : 'Add'}
                        </button>
                    </div>
                `;
            });
            $('#variantSearchResultsView').html(resultsHtml);
        }
    });
});


$(document).on('click', '.add-variant-btn-view', function (event) {
    event.preventDefault(); // Prevent form submission
    const variantId = $(this).data('id');
    const variantName = $(this).data('name');
    const variantImage = $(this).data('image');
    const variantPrice = $(this).data('price');
    const product_id = $(this).data('product-id');

    // Check if the variant is already added
    if ($(`#orderItems .variant-item[data-id="${variantId}"]`).length > 0) {
        return; // Do nothing if the variant is already added
    }

    const variantHtml = `
        <div class="row mb-3 variant-item" data-variant-id="${variantId}">
            <div class="col-2">
                <input type="hidden" class="variant-id" name="variant_id"  value="${variantId}">
                 <input type="hidden" class="product-id" name="product_id" value="${product_id}">
                <input type="hidden" class="variant-image" name="variant_image" value="${variantImage}">
                <img src="${variantImage}" alt="${variantName}" class="img-thumbnail w-40">
            </div>
            <div class="col-4">
                <input type="text" class="form-control variant-name" name="variant_name" value="${variantName}" readonly>
            </div>
            <div class="col-2">
                <input type="number" class="form-control variant-quantity" name="variant_quantity" value="1" min="1">
            </div>
            <div class="col-2">
                <input type="text" class="form-control variant-price" name="variant_price" value="${variantPrice}" readonly>
            </div>
            <div class="col-2">
                <button class="btn btn-primary btn-sm save-variant-btn">Save</button>
            </div>
        </div>
    `;

    $('#orderItems').append(variantHtml);

    // Disable the "Add" button and mark it as checked
    $(this).prop('disabled', true).text('Added');

    $('#variantSearchInputView').val('');
    $('#variantSearchResultsView').html('');
});



$(document).on('click', '.save-variant-btn-view', function (event) {
    event.preventDefault(); // Prevent form submission
   
    const id = $("#order-id").attr("data-id");
    const variantItem = $(this).closest('.variant-item');
    const variantId = variantItem.find('.variant-id').val();
    console.log("variantId", variantId);
    
    const variantQuantity = variantItem.find('.variant-quantity').val();
    console.log("variantQuantity", variantQuantity);
    
    const variantPrice = variantItem.find('.variant-price').val();
    console.log("variantPrice", variantPrice);
    
    const product_id = variantItem.find('.product-id').val();
    console.log("product_id", product_id);
    
    const variantName = variantItem.find('.variant-name').val();
    console.log("variantName", variantName);
    
    const variantImage = variantItem.find('.variant-image').val();
    console.log("variantImage", variantImage);

    // Create the HTML for the saved item
    const html = `<li class="list-group-item d-flex justify-content-between align-items-center">
                    <img src="${variantImage}" alt="${variantName}" style="width:50px; height:auto;">
                    ${variantName} (Qty: ${variantQuantity})
                    <button type="button" class="btn btn-danger btn-sm delete-item-btn" data-id="${variantId}">Delete</button>
                </li>`;
    
    // Clear the search input and append the new item
    $("#variantSearchInputInView").val('');
    $(".list-group-products").append(html);
    
    // Optionally hide or remove the variant item
    variantItem.remove(); // or variantItem.hide() if you want to keep it in DOM
});



$(document).on('click', '.save-variant-btn', function (event) {
    event.preventDefault();
    
    // Store the clicked button reference for use in AJAX callback
    const $button = $(this);
    const $variantItem = $button.closest('.variant-item');
    
    // Get all required data
    const id = $("#order-id").attr("data-id");
    const variantId = $variantItem.find('.variant-id').val();
    const variantQuantity = $variantItem.find('.variant-quantity').val();
    const variantPrice = $variantItem.find('.variant-price').val();
    const product_id = $variantItem.find('.product-id').val();
    const variantName = $variantItem.find('.variant-name').val();
    const variantImage = $variantItem.find('.variant-image').val();

    console.log({
        variantId,
        variantQuantity,
        variantPrice,
        product_id,
        variantName,
        variantImage
    });

    // Update the variant quantity and price in the database
    $.ajax({
        url: `${SITE_URL}/orders/add-variant-to-order?id=${id}`,
        type: 'POST',
        dataType: 'json',
        data: {
            variant_id: variantId,
            variant_quantity: variantQuantity,
            variant_price: variantPrice,
            product_id: product_id,
            variant_name: variantName,
            variant_image: variantImage
        },
        success: function (response) {
            if(response.success) {
                // Create the HTML for the saved item
                const html = `<li class="list-group-item d-flex justify-content-between align-items-center">
                                <img src="${variantImage}" alt="${variantName}" style="width:50px; height:auto;">
                                ${variantName} (Qty: ${variantQuantity})
                                <button type="button" class="btn btn-danger btn-sm delete-item-btn" data-id="${variantId}">Delete</button>
                            </li>`;
                
                // Update the UI
                $("#total").text(response.data.order.total);
                $("#subtotal").text(response.data.order.sub_total);
                $('#discount').text(response.data.order.discount);
                $("#shipping-price").text(response.data.order.shipping_price);
                $("#profit").text(response.data.order.profit);
                $("#variantSearchInputInView").val('');
                $(".list-group-products").append(html);
                $variantItem.remove(); // Remove the variant item from the form
            } else {
                alert(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error updating variant:', error);
            alert('An error occurred while saving the variant. Please try again.');
        }
    });
});