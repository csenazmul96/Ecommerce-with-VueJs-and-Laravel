$(function() {
    $('#schedule_time').timepicki();switchTab();
    
    window.addEventListener("hashchange", switchTab);

    function switchTab() {

        var currentLocation = window.location.href;
        
        if(currentLocation.split('#')[1]) {
            
            $('.header_acc_content').css('display' , 'none');
            
            var tabName = currentLocation.split('#')[1];
            
            
            $('.tab_link').each(function() {
                
              $( this ).removeClass('active');
              
            });
            
            var tabId = '#' + tabName;
            
            $(tabId + 'Tab').addClass('active');
            
            
            $('.tab_content').each(function() {
                
              $( this ).removeClass('show');
              
            });
            
            $(tabId).addClass('show');
        
        }
    
    }

});