jQuery(document).ready(function($){
  
  
  $('div[id*="wps_ficwidget"]', '#widget-list').each(function(i, item){
    $(item).addClass('wps-fic-widget-highlight');
  });  
  
  $('div[id*="wps_ficwidget"]', '#widgets-right').each(function(i, item){
    $(item).addClass('wps-fic-widget-highlight');
  });
  
  
});