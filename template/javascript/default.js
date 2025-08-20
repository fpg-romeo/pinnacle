    //PRE LOAD FUNCTION
    //$(window).load(function(){  
    $(window).on('load', function(){    
        //disabledControl();

        /* MONEY FORMAT ONLY */
        $(document).on("keypress keyup",".money", function(event){
            // var patt = new RegExp(/(?<=\.\d\d).+/i);
            // $(this).val($(this).val().replace(patt, ''));
            $(this).val($(this).val().replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\.\d{2}).+/g, '$1'));

            if((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57) ){
                event.preventDefault();
            }
        });

        $(document).on("blur",".money",function(){
            $(this).formatCurrency();
        });

        /* PERCENTAGE FORMAT ONLY */
        $(document).on("keypress keyup blur",".percent", function(event){
            // var patt = new RegExp(/(?<=\.\d\d).+/i);
            // $(this).val($(this).val().replace(patt, ''));
            $(this).val($(this).val().replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\.\d{2}).+/g, '$1'));

            if((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57) ){
                event.preventDefault();
            }
        });

        //$('.numeric').on('input', function (event) { 
        $(document).on("keypress keyup blur",".numeric", function (event) {
            //this.value = this.value.replace(/[^0-9]/g, '');
            this.value = this.value.replace(/\D/g,'');
        });

        //$('.email', '.email-format').on('keypress keyup blur', function (event) { 
        $(document).on("keypress keyup blur",".email, .email-format", function (event) {
            var name = $(this).attr("name")+'_format_email';

            function validateEmail(email) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(String(email));
            }

            const validate = (emails = "") => {
              emails = emails.split(/[;,]/).map((x) => x.trim().toLocaleLowerCase());
              if (emails.length !== new Set(emails).size) return false;
              return emails.every(validateEmail);
            };

            /*
            $('#'+name).remove();

            if(validate(this.value) == true){
                $(this).removeClass('highlight');
            }else{
                $(this).addClass('highlight');
                $('<div id="'+name+'" class="required-prompt">*Please enter a valid email address</div>').insertAfter($(this));
            }
            */

            $('#'+name).remove();
            if(validate(this.value) == true){
                $(this).removeClass('highlight');
            }else{
                if(this.hasAttribute('required') == true || this.value != ''){
                    $(this).addClass('highlight');
                    $('<div id="'+name+'" class="required-prompt">*Please enter a valid email address</div>').insertAfter($(this));
                }
            }

        });

        $(document).on("keypress keyup", ".money-allow-negative", function(event){
            // Allow only numbers, one dot, and a minus sign at the start
                $(this).val($(this).val()
                .replace(/[^0-9.-]/g, '')  // Allow digits, one dot, and a minus sign
                .replace(/(?!^)-/g, '')     // Ensure only one minus sign at the beginning
                .replace(/(\..*?)\..*/g, '$1')  // Ensure only one decimal point
                .replace(/(\.\d{2}).+/g, '$1')  // Limit to two decimal places
            );

            // Prevent invalid input
            if((event.which != 46 && event.which != 45 || $(this).val().indexOf('.') != -1 && event.which == 46) 
                && (event.which < 48 || event.which > 57) && event.which != 45) {
                event.preventDefault();
            }
        });
        
        $(document).on("blur", ".money-allow-negative", function(){
            let value = $(this).val();
        
            // Remove any non-numeric characters except the decimal and minus sign
            value = value.replace(/[^0-9.-]/g, '');
        
            // Format the number with commas and two decimal places
            let isNegative = false;
            if (value.startsWith('-')) {
                isNegative = true;
                value = value.substring(1); // Remove the minus sign for formatting
            }
        
            let parts = value.split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');  // Add commas for thousands
        
            value = parts.join('.'); // Join the whole number and decimal parts
        
            if (isNegative) {
                value = '-' + value; // Add back the minus sign if necessary
            }
        
            $(this).val(value);
        });
        
        
    });

    //HELPER
    function disabledControl(){
        //DISABLED VIEW SOURCE : DISABLED CTRL + U
        document.onkeydown = function(e) {
                if (e.ctrlKey && 
                    (e.keyCode === 85 )) {
                    return false;
                }
        };

        //DISABLED F12 or CTRL+SHIFT+i
        $(document).keydown(function(event){
            if(event.keyCode==123){
                return false;
            } else if(event.ctrlKey && event.shiftKey && event.keyCode==73){        
                return false;  
            }
        });

        //RIGHT CLICK DISABLED
        $(document).bind("contextmenu",function(e){
            return false;
        });

        //ALLOW NUMBER ONLY
        $('.number').keypress(function(event){
            if(event.which != 8 && isNaN(String.fromCharCode(event.which))){
                event.preventDefault();
            }
        });
    }

    function formatNumber(str, max=4) {
        str = str.toString();
        return str.length < max ? formatNumber("0" + str, max) : str;
    }

    function formatDate(value){
        if(value == null || value == 'null' || value == ''){
            var new_date = '';
        }else{
            var date     = new Date(value);
            var new_date = String("00" + (date.getMonth() + 1)).slice(-2) + '/' + String("00" + date.getDate()).slice(-2) + '/' +  date.getFullYear();
        }

        return new_date;  
    }

    function formatTime(time=''){     
        if(time != ''){  
            //just use random date to run time format 
            return new Date('1970-01-01T' + time + 'Z').toLocaleTimeString('en-US', {timeZone:'UTC',hour12:true,hour:'numeric',minute:'numeric'});
        }
    }
    
    function decimalStandard(num, fixed=2) {
        var re = new RegExp('^-?\\d+(?:\.\\d{0,' + (fixed || -1) + '})?');
        return num.toString().match(re)[0];
    }

    function formatMoney(value, set='0.00'){
        if(value == null || value == 'null' || value == '' || value == 'undefined' || value == '0' || value == '0.00'){
            return set;
        }else{ 
            //value     = decimalStandard(moneyClean(value));
            //var parts = parseFloat(value).toFixed(2).toString().split(".");
            //parts[0]  = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            //return parts.join(".");
            
            var amount = (Math.round(value * 10 ** 2) / 10 ** 2).toFixed(2);
                amount = amount.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        
            // value = moneyClean(value).toString().split('.');
            // value = moneyClean(value[0]+'.'+(typeof value[1] !== typeof undefined && value[1] !== false ? value[1].substr(0, 2) : '00')).toFixed(2);
            // value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            return amount;
        }
    }

    function moneyClean(value=''){
        if(value == null || value == 'null' || value == '' || value == '0' || value == '0.00' || value == 'undefined'){
            return '0.00';
        }else{
            //return parseFloat(value.replace(/,/g, ''));
            return parseFloat(value.toString().replace(/,/g, ''));
        }
    }

    function numberClean(value=''){
        if(value == null || value == 'null' || value == '' || value == '0' || value == 'undefined'){
            return 0;
        }else{
            return Number(value.replace(/\D/g, '')).toString();
        }
    }

    function percentageToDecimal(value){
        return parseFloat(value)/100;
    }

    function percentageToNumber(value){
        return (value/100)*100;
    }

    function percentageToAmount(amount, percent){
        var result = [];
        var value  = '0.00';

        if(percent == null || percent == 'null' || percent == '' || percent == '0' || percent == '0.00' || amount == null || amount == 'null' || amount == '' || amount == '0' || amount == '0.00' ){
            result['percent'] = value;
            result['total']   = value;
        }else{
            var percentage    = parseFloat(percentageToDecimal(percent));
            var price         = parseFloat(amount);
            var total_gst     = parseFloat(price*percentage).toFixed(2);
            var total_amount  = parseFloat(parseFloat(price) + parseFloat(total_gst));

            result['percent'] = isNaN(total_gst) || total_gst == 0 ? value : formatMoney(total_gst);
            result['total']   = isNaN(total_amount) || total_amount == 0 ? value : formatMoney(total_amount);
        }

        return result;
    }

    function percentageToUnitPrice(amount, percent){
        var result = [];
        var value  = '0.00';

        if(percent == null || percent == 'null' || percent == '' || percent == '0' || percent == '0.00' || amount == null || amount == 'null' || amount == '' || amount == '0' || amount == '0.00' ){
            result['percent'] = value;
            result['total']   = value;
        }else{
            var percentage    = percentageToDecimal(percent)+1;
            var total_gst     = decimalStandard(amount/percentage);
            var total_amount  = (amount-total_gst).toFixed(2);

            result['percent'] = isNaN(total_amount) || total_amount == 0 ? value : formatMoney(total_amount);
            result['total']   = isNaN(total_gst) || total_gst == 0 ? value : formatMoney(total_gst); 
        }

        return result;
    }

    function divide(dividend, divisor){
        if(dividend != 0 && divisor != 0){
            return dividend/divisor;
        }else{
            return 0;   
        }
    }

    function currentUrl(){
        return $(location).attr('href');
    }

    function goBack(){
        window.history.back();
    }

    //MODAL LOADING
    function promptAjaxLoading(form, message='Please wait while we are processing your request', type='#'){
        disbaledKeyboard();
        var formModal = $(type+form+ ' .modal-content');
        var height    = formModal.height();
        var margin    = parseInt(height-90)/2;
        formModal.append('<div class="modal-loading" style="height: '+height+'px;"><i class="fa fa-spinner fa-pulse fa-4x" style="margin-top: '+margin+'px"></i><br><span>'+message+'</span></div>');
    }

    function promptAjaxSuccess(modal, message, link=''){
        setTimeout(function(){
            $('#'+modal+ ' .modal-loading span').html(message);
        }, 200); //1600
        setTimeout(function() {
            enableKeyboard(); 

            if(link == 'reload'){
                window.location = document.URL;
            }else if(link == 'redirect'){
                window.location.href = link;
            }else if(link == 'close'){
                $('#'+modal+ ' .close').click();   
                //$('#'+modal).modal('hide');
            }else if(link != ''){
                window.location.href = link;
            } 

        }, 3000);  //2600
        // setTimeout(function() {
        //     $('#'+modal+ ' .modal-loading').remove();
        //     $('#'+modal+ ' .close').click();
        // }, 500); //3600
    }

    function modalLoadingMessage(modal, message, type='#'){
        $(type+modal+ ' .modal-loading span').html(message);
    }

    function modalLoadingRemove(modal, type='#'){
        enableKeyboard(); 
        $(type+modal+ ' .modal-loading').remove();
    }

    function removeModalCover(modal, type='#'){
        $(type+modal+ ' .modal-cover').remove();
    }

    function reloadPromptMessage(){
        setTimeout(function() {
            //location.reload();
            window.location = document.URL;
        }, 500); //1600
    }

    function disbaledKeyboard(){
        document.onkeydown = function (e){
            return false;
        }
    }
    function enableKeyboard(){
        document.onkeydown = function (e){
            return true;
        }
    }

    function selectDropdown(){        
        $('select:not(.normal)').each(function () {
            $(this).select2({
                dropdownParent: $(this).parent()
            });
        });
    }

    function clearFormFields(div, type='#'){
        //$(type+div+' input:not(.fix-id, [type="radio"])').val('');
        // $(type+div+' textarea').val(' ');
        $(type+div+' i.required').remove();
        $(type+div+' input[type=text]').val('');
        $(type+div+' input[type=radio]').removeAttr('checked');
        $(type+div+' input[type=checkbox]').removeAttr('checked');
        $(type+div+' select option').attr('selected', false);
    }

    function clearModalFields(div, type='#'){
        clearFormFields(div, type);
        $(type+div+' .modal-loading').hide();
    }

    function firstLetterToUpper(str){
        strVal = '';
        str = str.split(' ');
        for (var chr = 0; chr < str.length; chr++) {
            strVal += str[chr].substring(0, 1).toUpperCase() + str[chr].substring(1, str[chr].length);
        }
        return strVal;
    }

    //DAT FORMAT
    function dateFormat(pattern, todayDate='', withTime=''){
        var monthNames=["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        var todayDate = new Date();
                                          
        var date            = todayDate.getDate().toString();
        var month           = todayDate.getMonth().toString(); 
        var year            = todayDate.getFullYear().toString(); 
        var formattedMonth  = (todayDate.getMonth() < 10) ? "0" + month : month;
        var formattedDay    = (todayDate.getDate() < 10) ? "0" + date : date;
        if(withTime != ''){
            var time        = timeFormat(todayDate);
        }else{
            var time        = "";
        }

        var result          = "";

        switch (pattern) {
            case "M/d/yyyy": 
                formattedMonth = formattedMonth.indexOf("0") == 0 ? formattedMonth.substring(1, 2) : formattedMonth;
                formattedDay = formattedDay.indexOf("0") == 0 ? formattedDay.substring(1, 2) : formattedDay;

                result  = formattedMonth + '/' + formattedDay + '/' + year + ' ' + time;
                break;

            case "M/d/yy": 
                formattedMonth = formattedMonth.indexOf("0") == 0 ? formattedMonth.substring(1, 2) : formattedMonth;
                formattedDay = formattedDay.indexOf("0") == 0 ? formattedDay.substring(1, 2) : formattedDay;
                result  = formattedMonth + '/' + formattedDay + '/' + year.substr(2) + ' ' + time;
                break;

            case "MM/dd/yy":
                result  = formattedMonth + '/' + formattedDay + '/' + year.substr(2) + ' ' + time;
                break;

            case "MM/dd/yyyy":
               result  = formattedMonth + '/' + formattedDay + '/' + year + ' ' + time;
                break;

            case "yy/MM/dd":
                result  = year.substr(2) + '/' + formattedMonth + '/' + formattedDay + ' ' + time;
                break;


            case "yyyy-MM-dd":
                result  = year + '-' + formattedMonth + '-' + formattedDay + ' ' + time;
                break;

            case "dd-MMM-yy":
               result  = formattedDay + '-' + monthNames[todayDate.getMonth()].substr(3) + '-' + year.substr(2) + ' ' + time;
                break;

            case "MMMM d, yyyy":
                result  = todayDate.toLocaleDateString("en-us", { day: 'numeric', month: 'long', year: 'numeric' }) + ' ' + time;
                break;
        }

        return result;
    }

    //DATE DEFAULT DISPLAY : MM/DD/YYYY
    function dateDefault(date=''){
        if(date == null || date == 'null' || date == ''){
            var date = '';
        }else{
            var date = new Date(date).toLocaleDateString('en-US', {year: 'numeric', month: '2-digit', day: '2-digit'});
        }

        return date;
    }

    //SYSTEM DATE DEFAULT DISPLAY : DD-MMM-YYYY
    function dateDisplaySystem(date=''){
        if(date == null || date == 'null' || date == ''){
            var date = '';
        }else{
            var date = new Date(date).toLocaleDateString('en-GB', {day: '2-digit', month: 'short', year: 'numeric'}).replace(/ /g, '-');
        }

        return date;
    }

    //TIME FORMAT
    function timeFormat(time) {
        var time = new Date();
        
        var hour   = time.getHours();
        var minute = time.getMinutes();
        var second = time.getSeconds(); 
        var ampm   = hour >= 12 ? 'pm' : 'am';
        hour       = hour % 12;
        hour       = hour ? hour : 12; // the hour '0' should be '12'
        minute     = minute < 10 ? '0'+minute : minute;

        var result = hour + ':' + minute + ' ' + second + ' ' + ampm;
        
        return result;
    }

    $(document).ready(function(){
        'use strict'
        selectDropdown();
    });

    $(document).ready(function(){
        const select2 = $('.select2');

        if (select2.length) {
            select2.each(function () {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Select value',
                    dropdownParent: $this.parent()
                });
            });
        }
    });

    //MODAL
    $(document).ready(function(){
        $(document).on('shown.bs.modal', '.modal', function (e){
        //$('.modal').on('shown.bs.modal', function(e){
            e.preventDefault();
            selectDropdown();
            
            /*
            $('.calendar').datepicker({ 
                dateFormat: 'dd-M-yy'
            });

            $('.calendar-option').datepicker({
                dateFormat: 'dd-M-yy'
            });
            */
           
            setTimeout(function() {
                $('body').addClass('modal-open');
            }, 500); //1000
                
            // var id    = $(this).attr('id');
            // var modal = $('#'+id);
            //     modal.data('bs.modal')._config.keyboard = false;
            //     modal.data('bs.modal')._config.backdrop = 'static';
        });

        $(document).on('hidden.bs.modal', '.modal', function (e){
        //$('.modal').on('hidden.bs.modal', function(e){
            e.preventDefault();
            e.stopPropagation();
            $(this).removeData('bs.modal');
            $('.modal form').each(function() { this.reset() });
            //$(this).data('bs.modal').escape(); // Resets ESC
        });

        //MODAL CANCEL BUTTON RELOAD CURRENT PAGE
        $(document).on('click', '.modal-cancel-reload ', function(e){
            var modal  = $(this).data('modal');
            var reload = $(this).data('reload');

            promptAjaxLoading(modal);

            setTimeout(function(){
                if(reload == 'origin'){
                    window.location.href = location.protocol + '//' + location.host + location.pathname;
                }else{
                    window.location.href = currentUrl();
                }
            }, 500); //1600
        });
    });

    //TOGGLE
    $(document).on('click', '.toggle ', function(e){

        var div   = $(this).data('accordion');
        var close = $(this).data('close');

        if(!$('#accordion-'+div).is(":visible")){
            $(this).find('i').removeClass("fa-caret-"+close);
            $(this).find('i').addClass("fa-caret-up");
        }

        $('#accordion-'+div).slideToggle(function(){ 
            if(!$('#accordion-'+div).is(":visible")){
                $('.toggle[data-accordion="'+div+'"]').find('i').removeClass("fa-caret-up");
                $('.toggle[data-accordion="'+div+'"]').find('i').addClass("fa-caret-"+close);
            }
        });

        selectDropdown();

    });

    $(document).ready(function(){
        //editor();
    });

    /*
    function editor(height=400, control=''){
        if(control == 'plain'){
            control = false;
        }else{
            control = [
                    ['style', ['bold', 'italic', 'underline']],
                    ['font', ['strikethrough']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol']]
                    
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['misc', ['undo', 'redo']]
          
                ];
        }

        // $('.editor').each(function (item, value) {
        //     var id = $(this).prop('id');

            //$((id != '' ? '#'+id+'.editor' : '.editor')).summernote({
            $('.editor').summernote({
                toolbar: control,
                height: height,
                tooltip: false,
                disableDragAndDrop: true
            });
        //});
    }
    */

    $(document).ready(function(){
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            selectDropdown();
        });
    });

    //LOADING DATA : SEGMENT
    function loadingData(){
        var div     = $('.loading-data');
        var inherit = $('.br-section-wrapper').width();
        var width   = parseInt(inherit+60);

        div.css('width', width+'px');        
    }

    //$(window).load(function(){  
    $(window).on('load', function(){ 
        loadingData();
    });    

    //PROMPT TEXT
    function promptText(location, message){
        $('#'+location).html(message);
        setTimeout(function() {
            if(location != ''){
                $('#'+location).html('');
            }
        }, 1600);

    }

    // TIN FORMAT
    $(document).ready(function(){
        $('.input-tin-format').keyup(function() {
            var tin_num = $(this).val().split("-").join(""); // remove hyphens
            if (tin_num.length > 0) {
                tin_num = tin_num.match(new RegExp('.{1,3}', 'g')).join("-");
            }
            $(this).val(tin_num);
        });
    });

    //CURRENCY LABEL
    $(document).ready(function(){
        $(document).on('change', 'select[name=currency_id]', function(e){
            e.preventDefault();
            var id   = $(this).val();
            var code = $(this).find(':selected').data('code');

            if(id != ''){
                $('.currency_label').html(code);
            }else{
                $('.currency_label').html('$');
            }
        });
    });

    //CHECKALL
    $(document).ready(function(){
        $(document).on('click', '#check_all', function(e){
            if($(this).prop('checked')){
                $('input:checkbox').prop('checked', true);
            }else{
                $('input:checkbox').prop('checked', false);
            }
        });
    });

    //CLOSE MODAL AND CLEAR FORM
    $(document).keydown(function(event){
        if(event.keyCode == 27){
            clearFormFields('modal', '.');
            $('.modal').modal('hide');
        }
    });

    //SHOW OTHER
    $(document).ready(function(){
        $(document).on('change', '.show_other', function(e){
            e.preventDefault();
            var text  = $(this).find(':selected').text();
            var field = $(this).data('field');

            if(text == 'Others'){
                $('.'+field+'_field').show();
            }else{
                $('.'+field+'_field').hide();
                clearFormFields(field+'_field', '.');
            }
        });
    });

    //DATE FROM and TO : no back date
    /*
    $(document).ready(function(){
        $('.start_date').datepicker({
            onSelect: function(selected) {
                $('.end_date').datepicker("option","minDate", selected)
            }
        });
        $('.end_date').datepicker({
            onSelect: function(selected) {
                $('.start_date').datepicker("option","maxDate", selected)
            }
        }); 
    });
    */

    //CALENDAR
    /*
    $(document).ready(function() {
        $('.calendar').datepicker({
            dateFormat: 'dd-M-yy'
        });
    });

    //CALENDAR YEAR and MONTH
    $(document).ready(function() {
        $('.calendar-option').datepicker({
            dateFormat: 'dd-M-yy',
            changeMonth: true,
            changeYear: true,
            monthNamesShort: $.datepicker.regional["en"].monthNames
        });
    });
    */

    //ORDINAL : add st, nd, rd and th (ordinal) suffix to a number
    function convertOrdinal(n) {
        var s = ["th", "st", "nd", "rd"],
            v = n % 100;

        return n + (s[(v - 20) % 10] || s[v] || s[0]);
    }

    //CHECK IF RETURN VALUE IS EMPTY OR NOT
    function displayValue(value=''){
        if(value == null || value.trim().length === 0){
            value = '';
        }

        return value
    }

    //CHECK ARRAY CONTAINS DIFFERENT VALUE OR SAME
    function arrayValue(array){
        var x = array[0];
        return array.every(function(item){
            return item === x;
        });
    }

    //PROPER TIME FORMAT
    function timeConvert(time) {
        // Check correct time format and split into components
        time = time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

        if (time.length > 1) { // If time format correct
        time = time.slice(1); // Remove full string match value
        time[5] = +time[0] < 12 ? 'am' : 'pm'; // Set AM/PM
        time[0] = +time[0] % 12 || 12; // Adjust hours
        }
        return time.join(''); // return adjusted time or original string
    }

    function hideShowTablePaginationColumns(table_name , pagination_details = ''){
        var pagination                  = pagination_details != '' ? JSON.parse(pagination_details) : '';
        var pagination_table_columns    = ((pagination.table_columns != '') &&  pagination.table_columns) ? pagination.table_columns : '';
        var selectedColumns             = pagination_table_columns != '' ? pagination_table_columns.split("-") : [] ;
        var table                       = $("table."+table_name);
        if(selectedColumns.length > 0){
            $("table."+table_name+" thead tr th").each(function(){
                var tableValue = $(this).text();
                var index = $(this).index();
                if(pagination != ''){
                    if(selectedColumns.includes(tableValue)){
                        table.find("th, td").filter(":nth-child(" + (index + 1 ) + ")").show();
                    }else{
                        table.find("th, td").filter(":nth-child(" + (index + 1) + ")").hide();
                    }
                }
            });
        }
    }
    
    function updatePaginationLimit(table_name , table_limit){
        $.ajax({
                url: '/page/manage-pagination-json/',
                type: 'POST',
                data: {table_name : table_name,
                    table_limit : table_limit},
                beforeSend: function(){
                },
                success: function(data){
                    // console.log(data);
                },
                error: function(xhr, desc, err){ 
                    console.warn(xhr.responseText);
                }
           });
    }

    $(document).ready(function(){
        $('body').on('hidden.bs.modal', function () {
            $('body').attr("style", "overflow:auto")
        });

        $(document).on('click', '.set-column', function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            e.stopPropagation();
            var table_name = $(this).data('table-name');
            var display     = $('#modal-page-pagination .modal-body');

            $.ajax({
                url: '/page/import-page-pagination/',
                type: 'POST',
                data: {table_name:table_name},
                beforeSend: function(){
                    display.html('');
                },
                success: function(data){
                    $(data).appendTo(display);
                    $('#modal-page-pagination').modal('show');
                },
                error: function(xhr, desc, err){ 
                    console.warn(xhr.responseText);
                }
            });
        });
    });

    /* TEMPORARY | FOR TESTING
    $(document).ready(function(){
        $('.table thead .sort').each(function(){
            var id = $(this).attr('id');
            $(this).append('<span class="control"><i class="fa fa-caret-up asc '+id+'_asc"></i><i class="fa fa-caret-down desc '+id+'_desc"></i></span>');
        });
    });
    */

    //TABLE ACTION DROPDOWN : RESPONSIVE DROPDOWN
    $(document).ready(function(){
        $(document).on('click', '.table-dropdown', function(e){
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            var id                  = $(this).data('menu');
            var position_x          = $(this).offset().left;
            var position_y          = $(this).offset().top;
            var table_dropdown_menu = $('#table-dropdown-menu-'+id);
            var dropdown_menu       = table_dropdown_menu.html();
            var dropdown_width      = parseInt(table_dropdown_menu.width()-33);

            $('.table-dropdown').removeClass('show');
            $('.table-horizontal-dropdown').remove();
            $('.table-dropdown .icon').removeClass('fa-angle-up').addClass('fa-angle-down');

            if(!$('.table-dropdown').hasClass('show')){
                $('.table-dropdown[data-menu="'+id+'"]').addClass('show');
                $('.table-dropdown[data-menu="'+id+'"] .icon').removeClass('fa-angle-down').addClass('fa-angle-up');
                $('<div class="table-horizontal-dropdown">'+dropdown_menu+'</div>').insertAfter('.table-wrapper');
                $('.table-horizontal-dropdown').css({ 'top': parseInt(23+position_y)+'px', 'left':parseInt(position_x-dropdown_width)+'px' });
            }
        });

        $(document).click(function(e){
            $('.table-horizontal-dropdown').remove();
            $('.table-dropdown .icon').removeClass('fa-angle-up').addClass('fa-angle-down');
        });

        $(document).on('click', '.table-horizontal-dropdown a', function(e){
            $('.table-horizontal-dropdown').remove();
            $('.table-dropdown .icon').removeClass('fa-angle-up').addClass('fa-angle-down');
        });

        $('.table-dropdown').bind('mousewheel DOMMouseScroll', function(e){ 
            $('.table-horizontal-dropdown').remove();
            $('.table-dropdown .icon').removeClass('fa-angle-up').addClass('fa-angle-down');
        });
    });

    function showFullPageLoadingModal(message = ''){
       if ($('#modal-full-page-loading').length) {
            $('#modal-full-page-loading').remove();
        }
        appendToBody = '        <div id="modal-full-page-loading" class="modal fade prevent-close" data-backdrop="static">'+
                                    '<div class="modal-dialog modal-dialog-vertical-center"> '+
                                    '</div>'+
                                '</div>';
        $('body').append(appendToBody);
        setTimeout(()=>{
            disbaledKeyboard();
            if(message == ''){
                message = 'Do not close this page or click the back button'
            }
            var modal   = $('#modal-full-page-loading');
            var display = $('#modal-full-page-loading .modal-dialog');
            var append  = '';
            display.html('');
            //
            append = ' <div class="row" style="margin-top: 50%;">'+
                            '<div class="col-md-12 text-center mg-b-30 tx-white">'+
                                '<i class="fa fa-spinner fa-pulse fa-4x"></i>'+
                            '</div>'+
                            '<div class="col-md-12 text-center tx-white">  '+
                                '<span>Please wait while we are processing your request</span>'+
                                '<br>'+
                                '<span id="message">'+message+'</span>'+
                            '</div>'+
                        '</div>';
            setTimeout(() => {
                display.append(append);
                modal.modal('show');
            }, 100);
        },100)
    }
    
    function successFullPageLoadingModal(message = '', link=''){
        var modal   = $('#modal-full-page-loading');
        var display = $('#modal-full-page-loading .modal-dialog');
        var append  = '';
        // var span_message = $('#modal-full-page-loading .modal-dialog span#message');
        if(message == ''){
            message = 'Succesfully processed your request.'
        }
        display.html('');
        append = ' <div class="row" style="margin-top: 50%;">'+
        '<div class="col-md-12 text-center mg-b-30 tx-white">'+
                    ' <i id="success-icon" class="fa fa-check-circle tx-white checkmark-animation fa-4x"></i>'+
                '</div>'+
                '<div class="col-md-12 text-center tx-white">  '+
                    '<span id="message">'+message+'</span>'+
                '</div>'+
            '</div>';
        display.append(append);
        // span_message.text(message);
        setTimeout(function() {
            enableKeyboard();
            if(link == 'reload' || link == ''){
                window.location = document.URL;
            }else if(link != ''){
                window.location.href = link;
            }
        }, 2600);
        setTimeout(()=>{
            modal.remove();
        },3000)
    }

    function errorFullPageLoadingModal(message = ''){
        var modal   = $('#modal-full-page-loading');
        var display = $('#modal-full-page-loading .modal-dialog');
        var span_message = $('#modal-full-page-loading .modal-dialog span#message');
        if(message == ''){
            message = 'Whoops something went wrong!';
        }
        span_message.text(message);
        setTimeout(function() {
            enableKeyboard();
            // alert(message);
            display.html('');
            modal.modal('hide');
        }, 2600);
        setTimeout(()=>{
            modal.remove();
        },3000)
    }

    function htmlDecode(field = '', type = '') {
        if (field) {
            let decoded = $('<textarea/>').html(field).text(); // Decode HTML entities

            if (type === 'textarea') {
                decoded = decoded.replace(/\n/g, '<br>'); // Convert newlines to <br>
            }

            return decoded;
        }
        return field;
    }
