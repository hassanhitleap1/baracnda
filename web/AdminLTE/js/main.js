let SITE_URL = getSiteUrl() ;


function getSiteUrl() {
    let site_url=window.location.host;
    if (site_url=='localhost:8080'){
        return '';
    }
    return 'https://neutron-sys.com';
}



$(document).ready(function () {
    $('#openVariantModal').click(function () {
        $('#variantModal').modal('show');
    });

    $('#attributeSelect').change(function () {
        let attributeId = $(this).val();
        if (attributeId) {
            $.ajax({
                url: 'product/get-options',
                type: 'GET',
                data: { attribute_id: attributeId },
                success: function (data) {
                    let options = JSON.parse(data);
                    $('#optionSelect').html('<option value="">اختر الخيار</option>');
                    options.forEach(option => {
                        $('#optionSelect').append(`<option value="${option.id}">${option.name}</option>`);
                    });
                }
            });
        }
    });

    $('#addVariant').click(function () {

        let attributeName = $("#attributeSelect option:selected").text();
        let attributeId = $("#attributeSelect").val();
        let optionName = $("#optionSelect option:selected").text();
        let optionId = $("#optionSelect").val();

        if (!attributeId || !optionId) {
            alert("يرجى اختيار الخاصية والخيار");
            return;
        }

        let variantHtml = `
            <div class="variant-item">
                <input type="hidden" name="variants[${attributeId}][attribute]" value="${attributeId}">
                <input type="hidden" name="variants[${attributeId}][option]" value="${optionId}">
                
                <label>المتغير:</label>
                <input type="text" name="variants[${attributeId}][name]" class="form-control" value="${attributeName} - ${optionName}">

                <label>السعر:</label>
                <input type="number" name="variants[${attributeId}][price]" class="form-control">
                
                <button type="button" class="btn btn-danger remove-variant">حذف</button>
            </div>
        `;

        $('#variantsContainer').append(variantHtml);
        $('#variantModal').modal('hide');
    });
   
});

$(document).on('click', '#addVariant', function () {
    let attributeId = $("#attributeSelect").val();
    let optionId = $("#optionSelect").val();

    if (!attributeId || !optionId) {
        alert("يرجى اختيار الخاصية والخيار");
        return;
    }

    let attributeName = $("#attributeSelect option:selected").text();
    let optionName = $("#optionSelect option:selected").text();

    let variantHtml = `
        <div class="variant-item">
            <input type="hidden" name="variants[${attributeId}][attribute]" value="${attributeId}">
            <input type="hidden" name="variants[${attributeId}][option]" value="${optionId}">
            
            <label>المتغير:</label>
            <input type="text" name="variants[${attributeId}][name]" class="form-control" value="${attributeName} - ${optionName}">

            <label>السعر:</label>
            <input type="number" name="variants[${attributeId}][price]" class="form-control">
            
            <button type="button" class="btn btn-danger remove-variant">حذف</button>
        </div>
    `;

    $('#variantsContainer').append(variantHtml);
    $('#variantModal').modal('hide');
});

