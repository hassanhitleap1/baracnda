let SITE_URL = getSiteUrl() ;

function getSiteUrl() {
    let site_url=window.location.host;
    if (site_url=='localhost:8080'){
        return '';
    }
    return 'https://neutron-sys.com';
}



var apiUrl = `${SITE_URL}/site/autocomplete`;
    // Initialize the autocomplete widget
    $('#autocomplete').autocomplete({
      source: function(request, response) {
        // Make an AJAX request to the API with the user's input
        $.ajax({
          url: apiUrl,
          method: 'GET',
          dataType: 'json',
          data: {
            query: $('#autocomplete').val()
        },
          success: function(data) {
            // Process the API response and pass the suggestions to the autocomplete widget

            var suggestions = $.map(data, function(product) {
                return {
                    label: product.name_en, // Display name_en in the autocomplete suggestions
                    value: product.name_en,       // Use product ID as the selected value
                    data: product            // Additional data if needed
                };
            });
            response(suggestions);
          },
          error: function() {
            // Handle errors
            console.error('Error fetching suggestions from the API');
          }
        });
      },
      minLength: 1 // Set the minimum number of characters before triggering the autocomplete
    });



    $(document).ready(function() {
      // Bind a keydown event to the document
      $(document).keydown(function(e) {
        // Check if the pressed key is Enter (keyCode 13)
      
        if (e.keyCode === 13) {
          // Prevent the default action (form submission) if the active element is an input field
          if ($(document.activeElement).is('input') && $(document.activeElement).attr('id')== 'autocomplete') {
            e.preventDefault();
            var text=$('#autocomplete').val();;
            var url=`${SITE_URL}/site/serach?query=${text}` ;
            window.location.href=url;
          }
  
      
 
          //$('#myForm').submit();
        }
      });
    });
