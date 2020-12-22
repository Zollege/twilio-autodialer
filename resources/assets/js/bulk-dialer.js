$(document).ready(function() {
  const contactInputSelect = $('select[name="contact_input"]');
  const contactTextBox =  $('#text-contact-input');
  const fileUpload = $('#csv-contact-input');

  contactInputSelect.change(function(){
    if ($(this).val() == "text"){
        contactTextBox.toggleClass('collapse');
        fileUpload.addClass('collapse');
    } else {
        fileUpload.toggleClass('collapse');
        contactTextBox.addClass('collapse');
     }        
  });

});
