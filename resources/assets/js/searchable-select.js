$(document).ready(function() {
  let url = window.location.pathname === '/autodialer/bulk' ? 'callerid' : 'autodialer/callerid'; 

  $('.verified_phone_numbers').select2({
    placeholder: "Choose Verified Phone Number...",
    ajax: {
        url: url,
        dataType: 'json',
        data: function (params) {
            return {
                q: $.trim(params.term)
            };
        },
        processResults: function (data) {
            return {
                results: data.sort((a, b) => a.text < b.text ? -1 : 1)
            };
        },
        cache: true
    }
  });
;});
