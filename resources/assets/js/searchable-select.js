$(document).ready(function() {
  $('#verified_numbers_list').select2({
    placeholder: "Choose Verified Phone Number...",
    minimumInputLength: 1,
    ajax: {
        url: 'autodialer/vpn',
        dataType: 'json',
        data: function (params) {
            return {
                q: $.trim(params.term)
            };
        },
        processResults: function (data) {
            return {
                results: data
            };
        },
        cache: true
    }
  });
;});
