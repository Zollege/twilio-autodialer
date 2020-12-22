$(document).ready(function() {
  const contactInputSelect = $('select[name="contact_input"]');
  const contactTextBox =  $('#text-contact-input');
  const fileUpload = $('#csv-contact-input');

  contactInputSelect.change(function(){
    if ($(this).val() == "text"){
        contactTextBox.removeClass('collapse');
        fileUpload.addClass('collapse');
    } else {
        fileUpload.removeClass('collapse');
        contactTextBox.addClass('collapse');
     }        
  });

});
