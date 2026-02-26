$(document).ready(function(){
	$('#news-date').datepicker({
        uiLibrary: 'bootstrap4',
        format : 'yyyy-mm-dd'
        //format : 'dd-mm-yyyy'
    });

    $(function(){
        var dtToday = new Date();
        
        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
            day = '0' + day.toString();
        
        var minDate= year + '-' + month + '-' + day;
        
        $('#start_date').attr('min', minDate);
    });
$(function() {
    var today = new Date();
    $('#start_date').datepicker({
        uiLibrary: 'bootstrap4',
        format : 'yyyy-mm-dd',
        //format : 'dd-mm-yyyy',
        maxDate : new Date(),
        defaultDate : 'NOW()'
    });
});
$(function() {
    var today = new Date();
    $('#end_date').datepicker({
        uiLibrary: 'bootstrap4',
        format : 'yyyy-mm-dd',
        //format : 'dd-mm-yyyy',
        maxDate : new Date(),
        defaultDate : 'NOW()'
    });
});
    $(function(){
        var dtToday = new Date();
        
        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
            day = '0' + day.toString();
        
        var minDate= year + '-' + month + '-' + day;
        
        $('#end_date').attr('min', minDate);
    });
});