let SITE_URL = getSiteUrl() ;
alert(SITE_URL);

function getSiteUrl() {
    let site_url=window.location.host;
    if (site_url=='localhost:8080'){
        return '';
    }
    return 'https://neutron-sys.com';
}



document.getElementById("openModal").addEventListener("click", function () {
    document.getElementById("modalContent").innerHTML = "Loading...";
    $("#myModal").modal("show");
    $("#modalContent").load(`${SITE_URL}/attributes/show-attributes`);
});

document.getElementById("modalButton")?.addEventListener("click", function () {
    document.getElementById("modalContent").innerHTML = "Loading...";
    $("#myModal").modal("show");
    $("#modalContent").load(`${SITE_URL}/attributes/show-attributes`);
});


$(document).on('click', '#generate-variant', function () {
     
});


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
        alert("يرجى اختيار الخاصية والخيار");
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


document.addEventListener("DOMContentLoaded", function () {
    alert("hello");
    document.getElementById("addVariant").addEventListener("click", function () {
        let attributeSelect = document.getElementById("attributeSelect");
        let optionSelect = document.getElementById("optionSelect");

        let attributeId = attributeSelect.value;
        let optionId = optionSelect.value;

        if (!attributeId || !optionId) {
            alert("يرجى اختيار الخاصية والخيار");
            return;
        }

        let attributeName = attributeSelect.options[attributeSelect.selectedIndex].text;
        let optionName = optionSelect.options[optionSelect.selectedIndex].text;

        let variantContainer = document.getElementById("variantsContainer");

        let variantDiv = document.createElement("div");
        variantDiv.classList.add("variant-item");

        variantDiv.innerHTML = `
            <input type="hidden" name="variants[${attributeId}][attribute]" value="${attributeId}">
            <input type="hidden" name="variants[${attributeId}][option]" value="${optionId}">
            
            <label>المتغير:</label>
            <input type="text" name="variants[${attributeId}][name]" class="form-control" value="${attributeName} - ${optionName}">

            <label>السعر:</label>
            <input type="number" name="variants[${attributeId}][price]" class="form-control">
            
            <button type="button" class="btn btn-danger remove-variant">حذف</button>
        `;

        variantContainer.appendChild(variantDiv);

        // إغلاق النافذة المنبثقة
        let modal = document.getElementById("variantModal");
        let modalInstance = bootstrap.Modal.getInstance(modal);
        modalInstance.hide();
    });

    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("remove-variant")) {
            event.target.closest(".variant-item").remove();
        }
    });
});

