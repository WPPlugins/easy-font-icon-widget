jQuery(document).ready(function($){

  var image_src = '';

  // Upload background image
  $(document).on('click', '#wps-upload-image', function(e) {
    e.preventDefault();
    
    image_src = $(this).prev('input');
    var image = wp.media({ 
      title: 'Upload Image',
      // mutiple: true if you want to upload multiple files at once
      multiple: false
    }).open()
    .on('select', function(e){
      // This will return the selected image from the Media Uploader, the result is an object
      var uploaded_image = image.state().get('selection').first();
      // We convert uploaded_image to a JSON object to make accessing it easier
      var image_url = uploaded_image.toJSON().url;
      // Let's assign the url value to the input field
      $(image_src).val(image_url);
    });
    
  });
  
  
  // Select predefined layout
  $(document).on('click', '.wps-fic-layout-sample', function(e){
    var obj = $(this);
    var layout = $(this).data('layout');
    var form = $(this).parents('form');
    $('.wps-widget-layout', form).val(layout);
    $('.wps-predefined-layout-samples div.selected').removeClass('selected');
    $(obj).addClass('selected');
  });  
  
  
  // Select predefined image
  $(document).on('click', '.wps-fic-bg-sample', function(e){
    var obj = $(this);
    var img_src = $(this).data('bg-image');
    var form = $(this).parents('form');
    $('.wps-custom-bg-image', form).val(img_src);
    
    $('.wps-predefined-bg-samples div.selected').removeClass('selected');
    $(obj).addClass('selected');
  });
  
  
  // Colorpicker init
  $('.wps-fic-colorpicker:not(".color-picker")', '#widgets-right').wpColorPicker();

  $(document).on('widget-added widget-updated', function(){
    $('.wps-fic-colorpicker:not(".color-picker")').wpColorPicker();
    // IconPicker
    $('.wps-fic-icon-picker').fontIconPicker();
  });

  // Preview Font Button
  $(document).on('click', '.wps-fic-preview-font-button', function(e){
    e.preventDefault();
    var parent = $(this).parents('.widget-content');
    var font = $('.wps-fic-selected-font', parent).val();
    var font_size = $('.wps-fic-selected-font-size', parent).val();

    WebFontConfig = {
      google: { families: [ font ] }
    };


    var wps = document.createElement('script');
    wps.src = ('https:' == document.location.protocol ? 'https' : 'http') +
    '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
    wps.type = 'text/javascript';
    wps.async = 'true';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(wps, s);

    $.post(ajaxurl, {action:'get_font_preview', font:font}, function(response){
      if (response.success) {
        $('.wps-fic-preview-font', parent).html('<strong>Preview text:</strong><br/><p style="font-family: \'' + response.data.family + '\', ' + response.data.variant + ';font-size:' + font_size + ';">Lorem ipsum dolor sit amet, mea ei oporteat laboramus, cu salutandi voluptatibus interpretaris sea. No putant iudicabit sed, nisl aliquam et pro, no nam eros affert alterum. In eum dictas antiopam efficiendi, choro putant salutandi eos ad.</p>');
      }
    });

  });

});