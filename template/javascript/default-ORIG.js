    //PRE LOAD FUNCTION
    $(window).load(function(){  
            
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

        }, 500);  //2600
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

    //MODAL
    $(document).ready(function(){
        $(document).on('shown.bs.modal', '.modal', function (e){
        //$('.modal').on('shown.bs.modal', function(e){
            e.preventDefault();
            selectDropdown();
            
            $('.calendar').datepicker({ 
                dateFormat: 'dd-M-yy'
            });

            $('.calendar-option').datepicker({
                dateFormat: 'dd-M-yy'
            });

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
        editor();
    });

    function editor(height=400, control=''){
        if(control == 'plain'){
            control = false;
        }else{
            control = [
                    ['style', ['bold', 'italic', 'underline']],
                    /*['font', ['strikethrough']],*/
                    ['color', ['color']],
                    ['para', ['ul', 'ol']]
                    /*
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['misc', ['undo', 'redo']]
                    */
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

    $(window).load(function(){  
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

    //CAMPAIGN DURATION
    $(document).ready(function(){
        $(document).on('change', 'select[name=campaign_duration_id]', function(e){
            e.preventDefault();
            var selected = $(this).find(':selected');
            var text     = selected.text();
            $(this).find('option').removeAttr('data-month');
            $(this).find('option').removeAttr('data-week');
            $(this).find('option').removeAttr('data-day');
            if(text == 'Others'){
                $('.campaign_duration_field').show();
                var campaign_duration_other  = $('select[name="campaign_duration_other"]');
                var campaign_duration_length = $('input[name="campaign_duration_length"]');
                if(campaign_duration_other.length && campaign_duration_other.find(':selected').val() == ''){
                    campaign_duration_other.val("Months").trigger('change');
                }
                if(campaign_duration_length.length && campaign_duration_length.val() == ''){
                    campaign_duration_length.val(1);
                }
                selectDropdown();
            }else{
                var value = text.split(" ");
                var month = value[0];
                var week  = value[0]*4;
                var day   = value[0]*30;
                selected.attr('data-month', month);
                selected.attr('data-week', week);
                selected.attr('data-day', day);
                $('.campaign_duration_field').hide();
                clearFormFields('campaign_duration_field', '.');
            }
            //computeBudget();
            //computeContract();
            //computeValue();
            //computeMonthly();
            //computeMedia();
            campaignDateSet();
        });
    });

    //SERVICE MODULE : CAMPAIGN DURATION BASED ON SELECTED PAYMENT TERM 
    function campaignDurationBasedOnPaymentTerm(value){
        if(value == 6){
            var campaign_duration_value = $('select[name=campaign_duration_id]').val();
            
            if(campaign_duration_value != 5 && campaign_duration_value != 6 && campaign_duration_value != 7){
                $('select[name=campaign_duration_id]').val("").find("option[value='']").attr('selected', true);
                $('select[name=campaign_duration_id]').trigger("change");
            }

            $("select[name=campaign_duration_id] option[value='1']").attr("disabled","disabled");
            $("select[name=campaign_duration_id] option[value='2']").attr("disabled","disabled");
            $("select[name=campaign_duration_id] option[value='3']").attr("disabled","disabled");
            $("select[name=campaign_duration_id] option[value='4']").attr("disabled","disabled");

            $("select[name=campaign_duration_id] option[value='5']").removeAttr("disabled");
            $("select[name=campaign_duration_id] option[value='6']").removeAttr("disabled");
            $("select[name=campaign_duration_id] option[value='7']").removeAttr("disabled");

        }else{
            $("select[name=campaign_duration_id] option[value='1']").removeAttr("disabled");
            $("select[name=campaign_duration_id] option[value='2']").removeAttr("disabled");
            $("select[name=campaign_duration_id] option[value='3']").removeAttr("disabled");
            $("select[name=campaign_duration_id] option[value='4']").removeAttr("disabled");

            $("select[name=campaign_duration_id] option[value='5']").removeAttr("disabled");
            $("select[name=campaign_duration_id] option[value='6']").removeAttr("disabled");
            $("select[name=campaign_duration_id] option[value='7']").removeAttr("disabled");
        }

        $("select[name=campaign_duration_id]").select2();
    }

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

    //CALENDAR
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

    //CAMPAIGN BUDGET
    $(document).ready(function(){
        $(document).on('change', '.compute_budget select[name=payment_type_id]', function(e){
            e.preventDefault();

            computeBudget();
        });

        $(document).on('change', '.compute_budget select[name=campaign_duration_id]', function(e){
            e.preventDefault();

            computeBudget();
        });

        $(document).on('change', '.compute_budget select[name=campaign_duration_other]', function(e){
            e.preventDefault();

            computeBudget();
        });

        $(document).on('change', '.compute_budget input[name=campaign_duration_length]', function(e){
            e.preventDefault();

            computeBudget();
        });

        $(document).on('change', '.compute_budget input[name=total_budget]', function(e){
            e.preventDefault();

            computeBudget();
        });

        $(document).on('change', '.compute_budget input[name=management_fee]', function(e){
            e.preventDefault();

            computeBudget();
        });

        $(document).on('change', '.compute_budget input[name=setup_fee]', function(e){
            e.preventDefault();

            computeBudget();
        });

        $(document).on('change', '.compute_budget input[name=discount]', function(e){
            e.preventDefault();

            computeBudget();
        });

        $(document).on('change', '.compute_budget select[name=service_package_id]', function(e){
            e.preventDefault();
            
            computeBudget();
        });

        $(document).on('change', '.compute_budget .auto_compute', function(e){
            e.preventDefault();

            computeBudget();
        });

        $(document).on('change', '.compute_budget input[name=total_value]', function(e){
            e.preventDefault();

            computeBudget();
        });

        $(document).on('change', '.compute_budget input[name=monthly_budget]', function(e){
            e.preventDefault();

            computeBudget();
        });

        $(document).on('change', '.compute_budget input[name=weekly_budget]', function(e){
            e.preventDefault();

            computeBudget();
        });

        $(document).on('change', '.compute_budget input[name=daily_budget]', function(e){
            e.preventDefault();

            computeBudget();
        });

        $(document).on('change', '.compute_budget input[name=total_value]', function(e){
            e.preventDefault();

            computeBudget();
        });

        $(document).on('change', '.compute_budget .gst_apply', function(e){
            e.preventDefault();

            computeBudget();
        });
    });

    function computeBudget(){
        var payment_type_id                  = $('select[name=payment_type_id]');
        var setup_fee                        = $('input[name=setup_fee]');
        var management_fee                   = $('input[name=management_fee]');
        var discount                         = $('input[name=discount]');
        var total_budget                     = $('.compute_budget input[name=total_budget]');
        var amount                           = (total_budget.length) ? moneyClean(total_budget.val()) : '0.00';
        var amount                           = (isNaN(amount) ? 0 : amount);
        var monthly_budget                   = $('.compute_budget input[name=monthly_budget]');
        var weekly_budget                    = $('.compute_budget input[name=weekly_budget]');
        var daily_budget                     = $('.compute_budget input[name=daily_budget]');
        var total_value                      = $('.compute_budget input[name=total_value]');

        var administrative_charge_percent    = $('.compute_budget input[name=administrative_charge_percent]');
        var administrative_charge_amount     = $('.compute_budget input[name=administrative_charge_amount]');
        var administrative_charge_apply      = $('.compute_budget input[name=administrative_charge_apply]');

        var gst_percent                      = $('.compute_budget input[name=gst_percent]');
        var gst_amount                       = $('.compute_budget input[name=gst_amount]');
        var gst_apply                        = $('.compute_budget input[name=gst_apply]');

        var total_cost                       = $('.compute_budget input[name=total_cost]');

        var duration                         = $('select[name=campaign_duration_id]');
        var selected                         = duration.find(':selected');
        var text                             = selected.text(); 

        var month                            = 0; 
        var week                             = 0; 
        var day                              = 0; 

        var package                          = $('select[name=service_package_id]').find(':selected').text();

        if($('.auto_compute').is(":checked")){
          
            total_budget.prop('readonly',true);
            monthly_budget.prop('readonly',true);
            weekly_budget.prop('readonly',true);
            daily_budget.prop('readonly',true);
            total_value.prop('readonly',true);

            administrative_charge_apply.prop('checked', true);
            administrative_charge_amount.prop('readonly',true);
            administrative_charge_apply.prop('disabled', true);
            if(text == 'Others'){
                var other = $('select[name=campaign_duration_other]').find(':selected').text(); 
                var value = $('input[name=campaign_duration_length]').val();

                if(other == 'Weeks'){
                    month = 0;
                    week  = value;
                    day   = divide(divide(amount, week), 7);
                }else if(other == 'Days'){
                    month = 0;
                    week  = 0;
                    day   = divide(amount, value);
                }else{
                    month = value;
                    week  = value*4;
                    day   = divide(divide(amount, week), 7);
                }
            }else{
                month = selected.data('month'); 
                week  = selected.data('week'); 
                day   = divide(divide(amount, week), 7); 
            }

            /* HIDE THIS : WILL APPLY OPTIONAL AUTO COMPUTE
            if(package == 'Customized'){
                month = 1; 
                week  = 4;
                day   = divide(divide(amount, week), 7); 
            }
            */

            if(payment_type_id.length && payment_type_id.find(':selected').val() == 1){ //1 = Pay via Client Card
                total_budget.val('0.00');
                monthly_budget.val('0.00');
                weekly_budget.val('0.00');
                daily_budget.val('0.00');

                //discount.val('0.00').attr('readonly', true);
                total_budget.val('0.00').attr('readonly', true);

                amount = 0;
            }
            else if(payment_type_id.length && payment_type_id.find(':selected').val() == 2){
                administrative_charge_amount.prop('readonly',false);
                administrative_charge_apply.prop('disabled', false);
                administrative_charge_apply.prop('checked', true);
            }else{

                

                if(total_budget.val() == '' || total_budget.val() == 0.00 || total_budget.val() == 0){
                    total_budget.val('0.00');
                    monthly_budget.val('0.00');
                    weekly_budget.val('0.00');
                    daily_budget.val('0.00');
                }else{
                    monthly_budget.val(formatMoney(divide(amount, month), '0.00'));
                    weekly_budget.val(formatMoney(divide(amount, week), '0.00'));
                    daily_budget.val(formatMoney(day, '0.00'));
                }

                total_budget.removeAttr('readonly');
            }

            if(discount.length && discount.val() != 0.00 && discount.val() != 0){
                amount = amount-moneyClean(discount.val());
            }else{
                discount.val('0.00');
            } 

            if(management_fee.length && management_fee.val() != 0.00 && management_fee.val() != 0){
                amount = amount+moneyClean(management_fee.val());
            }else{
                management_fee.val('0.00');
            }   

            if(setup_fee.length && setup_fee.val() != 0.00 && setup_fee.val() != 0){
                amount = amount+moneyClean(setup_fee.val());
            }else{
                setup_fee.val('0.00');
            } 

            total_value.val(formatMoney(isNaN(amount) || amount == 0 ? '0.00' : amount));
        }else{
            total_budget.prop('readonly',false);
            monthly_budget.prop('readonly',false);
            weekly_budget.prop('readonly',false);
            daily_budget.prop('readonly',false);
            total_value.prop('readonly',false);
            administrative_charge_amount.prop('readonly',false);
            administrative_charge_apply.prop('checked', true);
            administrative_charge_apply.prop('disabled', false);

            administrative_charge_apply.change(function() {
                if ($(this).prop('checked')) {
                    administrative_charge_amount.prop('readonly', false); 
                } else {
                    administrative_charge_amount.prop('readonly', true); 
                }
            });

            if(total_budget.val() == 0){
                total_budget.val('0.00');
            }
            if(monthly_budget.val() == 0){
                monthly_budget.val('0.00');
            }
            if(weekly_budget.val() == 0){
                weekly_budget.val('0.00');
            }
            if(daily_budget.val() == 0){
                daily_budget.val('0.00');
            }
            if(discount.val() == 0){
                discount.val('0.00');
            }
            if(management_fee.val() == 0){
                management_fee.val('0.00');
            }
            if(setup_fee.val() == 0){
                setup_fee.val('0.00');
            }
            if(total_value.val() == 0){
                total_value.val('0.00');
            }
            if(administrative_charge_amount.val() == 0){
                total_value.val('0.00');
            }
        }        

        if(gst_apply.length && gst_apply.is(":checked")){
            total_gst = moneyClean(total_value.val())*percentageToDecimal(gst_percent.val());
        }else{
            total_gst = 0;
        }

        gst_amount.val((isNaN(total_gst) || total_gst == 0 ? '0.00' : formatMoney(total_gst)));
        // total_cost.val((isNaN(total_gst) || total_gst == 0 ? formatMoney(moneyClean(total_value.val())) : formatMoney(moneyClean(total_value.val())+total_gst)));
        total_cost.val(formatMoney(moneyClean(total_value.val())+total_gst));
    }

    //CAMPAIGN CONTRACT
    $(document).ready(function(){
        $(document).on('change', '.compute_contract select[name=payment_type_id]', function(e){
            e.preventDefault();

            computeContract();
        });

        $(document).on('change', '.compute_contract select[name=campaign_duration_id]', function(e){
            e.preventDefault();

            computeContract();
        });

        $(document).on('change', '.compute_contract select[name=campaign_duration_other]', function(e){
            e.preventDefault();

            computeContract();
        });

        $(document).on('change', '.compute_contract input[name=campaign_duration_length]', function(e){
            e.preventDefault();

            computeContract();
        });

        $(document).on('change', '.compute_contract input[name=monthly_value]', function(e){
            e.preventDefault();

            computeContract();
        });

        $(document).on('change', '.compute_contract input[name=contract_value]', function(e){
            e.preventDefault();

            computeContract();
        });

        $(document).on('change', '.compute_contract input[name=discount]', function(e){
            e.preventDefault();

            computeContract();
        });

        $(document).on('change', '.compute_contract select[name=service_package_id]', function(e){
            e.preventDefault();

            computeContract();
        });

        $(document).on('change', '.compute_contract .auto_compute', function(e){
            e.preventDefault();

            computeContract();
        });

        $(document).on('change', '.compute_contract input[name=total_value]', function(e){
            e.preventDefault();

            computeContract();
        });

        $(document).on('change', '.compute_contract input[name=boost_post]', function(e){
            e.preventDefault();

            computeContract();
        });

        $(document).on('change', '.compute_contract input[name=boost_budget]', function(e){
            e.preventDefault();

            computeContract();
        });

        $(document).on('change', '.compute_contract .gst_apply', function(e){
            e.preventDefault();

            computeContract();
        });
    });

    function computeContract(){
        var payment_type_id                  = $('.compute_contract select[name=payment_type_id]');
        var boost_post                       = $('.compute_contract input[name="boost_post"]:checked').val();
        var boost_budget                     = $('.compute_contract input[name=boost_budget]');

        var amount                           = $('.compute_contract input[name=monthly_value]');
        var duration                         = $('.compute_contract select[name=campaign_duration_id]');
        var selected                         = duration.find(':selected');
        var text                             = selected.text(); 
        var month                            = 0; 

        var package                          = $('.compute_contract select[name=service_package_id]').find(':selected').text();
        var total_value                      = $('.compute_contract input[name=total_value]');
        var contract_value                   = $('.compute_contract input[name=contract_value]');
        var discount                         = $('.compute_contract input[name=discount]');

        var gst_percent                      = $('.compute_contract input[name=gst_percent]');
        var gst_amount                       = $('.compute_contract input[name=gst_amount]');
        var gst_apply                        = $('.compute_contract input[name=gst_apply]');
        var total_cost                       = $('.compute_contract input[name=total_cost]');

        var administrative_charge_percent    = $('.compute_contract input[name=administrative_charge_percent]');
        var administrative_charge_amount     = $('.compute_contract input[name=administrative_charge_amount]');
        var administrative_charge_apply      = $('.compute_contract input[name=administrative_charge_apply]');


        if(amount.length && amount.val() != 0.00 && amount.val() != 0){
            amount = moneyClean(amount.val());
        }else{
            amount.val('0.00');
            amount = 0.00;
        } 

        if($('.auto_compute').is(":checked")){
            
            administrative_charge_apply.prop('checked', true);
            administrative_charge_amount.prop('readonly',true);
            administrative_charge_apply.prop('disabled', true);

            total_value.prop('readonly',true);

            if(payment_type_id.length && payment_type_id.find(':selected').val() == 2){
                administrative_charge_amount.prop('readonly',false);
                administrative_charge_apply.prop('disabled', false);
                administrative_charge_apply.prop('checked', true);
            }

            if(text == 'Others'){
                var other = $('select[name=campaign_duration_other]').find(':selected').text(); 
                var value = $('input[name=campaign_duration_length]').val();

                if(other == 'Months'){
                    month = value;
                }
            }else{
                month = selected.data('month');
            }

            /* HIDE THIS : WILL APPLY OPTIONAL AUTO COMPUTE
            if(package == 'Customized'){
                month = 1;
            }
            */

            var total = amount;
            if(month){
                total = amount*month;
            } 

            if(contract_value.length && contract_value.val() != 0.00 && contract_value.val() != 0){
                total = total+moneyClean(contract_value.val());
            }else{
                contract_value.val('0.00');
            }  

            if(boost_budget.length && boost_budget.val() != 0.00 && boost_budget.val() != 0 && boost_post == 'Yes' && payment_type_id.length && payment_type_id.find(':selected').val() == 2){
                total = total+moneyClean(boost_budget.val());
            }else{
                //boost_budget.val('0.00');
            }

            if(discount.length && discount.val() != 0.00 && discount.val() != 0){
                total = total-moneyClean(discount.val());
            }else{
                discount.val('0.00');
            }  

            total_value.val(formatMoney(isNaN(total) || total == 0 ? '0.00' : total));
        }else{

            administrative_charge_amount.prop('readonly',false);
            administrative_charge_apply.prop('checked', true);
            administrative_charge_apply.prop('disabled', false);
            
            administrative_charge_apply.change(function() {
                if ($(this).prop('checked')) {
                    administrative_charge_amount.prop('readonly', false); 
                } else {
                    administrative_charge_amount.prop('readonly', true); 
                }
            });

            if(contract_value.val() == 0){
                contract_value.val('0.00');
            }

            if(boost_budget.val() == 0){
                boost_budget.val('0.00');
            }

            if(discount.val() == 0){
                discount.val('0.00');
            }

            if(total_value.val() == 0){
                total_value.val('0.00');
            }

            total_value.prop('readonly',false);
        }

        if(gst_apply.length && gst_apply.is(":checked")){
            total_gst = moneyClean(total_value.val())*percentageToDecimal(gst_percent.val());
        }else{
            total_gst = 0;
        }

        gst_amount.val((isNaN(total_gst) || total_gst == 0 ? '0.00' : formatMoney(total_gst)));
        // total_cost.val((isNaN(total_gst) || total_gst == 0 ? '0.00' : formatMoney(moneyClean(total_value.val())+total_gst)));
        total_cost.val(formatMoney(moneyClean(total_value.val())+total_gst));
    }

    //CAMPAIGN VALUE
    $(document).ready(function(){
        $(document).on('change', '.compute_value input[name=contract_value]', function(e){
            e.preventDefault();

            computeValue();
        });

        $(document).on('change', '.compute_value .auto_compute', function(e){
            e.preventDefault();

            computeValue();
        });

        $(document).on('change', '.compute_value input[name=discount]', function(e){
            e.preventDefault();
            computeValue();
        });

        $(document).on('change', '.compute_value input[name=total_value]', function(e){
            e.preventDefault();
            computeValue();
        });

        $(document).on('change', '.compute_value .gst_apply', function(e){
            e.preventDefault();
            
            computeValue();
        });
    });

    function computeValue(){
        var amount      = $('.compute_value input[name=contract_value]');
        var total_value = $('.compute_value input[name=total_value]');
        var discount    = $('.compute_value input[name=discount]');

        var gst_percent = $('.compute_value input[name=gst_percent]');
        var gst_amount  = $('.compute_value input[name=gst_amount]');
        var gst_apply   = $('.compute_value input[name=gst_apply]');
        var total_cost  = $('.compute_value input[name=total_cost]');

        if(amount.length && amount.val() != 0.00 && amount.val() != 0){
            amount = moneyClean(amount.val());
        }else{
            amount.val('0.00');
            amount = 0.00;
        } 

        if($('.auto_compute').is(":checked")){
            total_value.prop('readonly',true);

            if(discount.length && discount.val() != 0.00 && discount.val() != 0){
                amount = amount-moneyClean(discount.val());
            }else{
                discount.val('0.00');
            } 

            var total = amount;

            total_value.val((isNaN(total) || total == 0  ? '0.00' : formatMoney(total)));
        }else{

            if(discount.val() == 0){
                discount.val('0.00');
            } 

            if(total_value.val() == 0){
                total_value.val('0.00');
            }

            total_value.prop('readonly',false);
        }
        
        if(gst_apply.length && gst_apply.is(":checked")){
            total_gst = moneyClean(total_value.val())*percentageToDecimal(gst_percent.val());
        }else{
            total_gst = 0;
        }

        gst_amount.val((isNaN(total_gst) || total_gst == 0 ? '0.00' : formatMoney(total_gst)));
        // total_cost.val((isNaN(total_gst) || total_gst == 0 ? '0.00' : formatMoney(moneyClean(total_value.val())+total_gst)));
        total_cost.val(formatMoney(moneyClean(total_value.val())+total_gst));
    }

    //CAMPAIGN MONTHLY
    $(document).ready(function(){
        $(document).on('change', '.compute_monthly select[name=campaign_duration_id]', function(e){
            e.preventDefault();

            computeMonthly();
        });

        $(document).on('change', '.compute_monthly select[name=campaign_duration_other]', function(e){
            e.preventDefault();

            computeMonthly();
        });

        $(document).on('change', '.compute_monthly input[name=campaign_duration_length]', function(e){
            e.preventDefault();

            computeMonthly();
        });

        $(document).on('change', '.compute_monthly input[name=monthly_value]', function(e){
            e.preventDefault();

            computeMonthly();
        });

        $(document).on('change', '.compute_monthly input[name=discount]', function(e){
            e.preventDefault();

            computeMonthly();
        });

        $(document).on('change', '.compute_monthly select[name=service_package_id]', function(e){
            e.preventDefault();
            
            computeMonthly();
        });

        $(document).on('change', '.compute_monthly .auto_compute', function(e){
            e.preventDefault();

            computeMonthly();
        });

        $(document).on('change', '.compute_monthly input[name=total_value]', function(e){
            e.preventDefault();

            computeMonthly();
        });

        $(document).on('change', '.compute_monthly .gst_apply', function(e){
            e.preventDefault();
            
            computeMonthly();
        });
    });

    function computeMonthly(){
        var amount      = $('.compute_monthly input[name=monthly_value]');
        var duration    = $('select[name=campaign_duration_id]');
        var selected    = duration.find(':selected');
        var text        = selected.text(); 
        var month       = 0; 

        var package     = $('select[name=service_package_id]').find(':selected').text();
        var total_value = $('.compute_monthly input[name=total_value]');
        var discount    = $('.compute_monthly input[name=discount]');

        var administrative_charge_percent    = $('.compute_monthly input[name=administrative_charge_percent]');
        var administrative_charge_amount     = $('.compute_monthly input[name=administrative_charge_amount]');
        var administrative_charge_apply      = $('.compute_monthly input[name=administrative_charge_apply]');


        var gst_percent = $('.compute_monthly input[name=gst_percent]');
        var gst_amount  = $('.compute_monthly input[name=gst_amount]');
        var gst_apply   = $('.compute_monthly input[name=gst_apply]');
        var total_cost  = $('.compute_monthly input[name=total_cost]');

        if(amount.length && amount.val() != 0.00 && amount.val() != 0){
            amount = moneyClean(amount.val());
        }else{
            amount.val('0.00');
            amount = 0.00;
        } 

        if($('.auto_compute').is(":checked")){

            administrative_charge_apply.prop('checked', true);
            administrative_charge_amount.prop('readonly',true);
            administrative_charge_apply.prop('disabled', true);
            total_value.prop('readonly',true);

            if(text == 'Others'){
                var other = $('select[name=campaign_duration_other]').find(':selected').text(); 
                var value = $('input[name=campaign_duration_length]').val();

                //if(other == 'Months'){
                    month = value;
                //}
            }else{
                month = selected.data('month'); 
            }

            /* HIDE THIS : WILL APPLY OPTIONAL AUTO COMPUTE
            if(package == 'Customized'){
                month = 1;
            }
            */

            var total = amount;
            if(month){
                total = amount*month;
            }

            if(discount.length && discount.val() != 0.00 && discount.val() != 0){
                total = total-moneyClean(discount.val());
            }else{
                discount.val('0.00');
            } 

            total_value.val((isNaN(total) || total == 0  ? '0.00' : formatMoney(total)));
        }else{

            administrative_charge_amount.prop('readonly',false);
            administrative_charge_apply.prop('checked', true);
            administrative_charge_apply.prop('disabled', false);
            
            administrative_charge_apply.change(function() {
                if ($(this).prop('checked')) {
                    administrative_charge_amount.prop('readonly', false); 
                } else {
                    administrative_charge_amount.prop('readonly', true); 
                }
            });

            if(discount.val() == 0){
                discount.val('0.00');
            } 

            if(total_value.val() == 0){
                total_value.val('0.00');
            } 

            total_value.prop('readonly',false);
        }

        if(gst_apply.length && gst_apply.is(":checked")){
            total_gst = moneyClean(total_value.val())*percentageToDecimal(gst_percent.val());
        }else{
            total_gst = 0;
        }

        gst_amount.val((isNaN(total_gst) || total_gst == 0 ? '0.00' : formatMoney(total_gst)));
        // total_cost.val((isNaN(total_gst) || total_gst == 0 ? '0.00' : formatMoney(moneyClean(total_value.val())+total_gst)));
        total_cost.val(formatMoney(moneyClean(total_value.val())+total_gst));
    }

    //CAMPAIGN MEDIA
    $(document).ready(function(){
        $(document).on('change', '.compute_media input[name=media_budget]', function(e){
            e.preventDefault();

            computeMedia();
        });
        $(document).on('change', '.compute_media select[name=campaign_duration_other]', function(e){
            e.preventDefault();

            computeMedia();
        });

        $(document).on('change', '.compute_media input[name=campaign_duration_length]', function(e){
            e.preventDefault();

            computeMedia();
        });

        $(document).on('change', '.compute_media input[name=management_fee]', function(e){
            e.preventDefault();

            computeMedia();
        });

        $(document).on('change', '.compute_media input[name=discount]', function(e){
            e.preventDefault();

            computeMedia();
        });

        $(document).on('change', '.compute_media .auto_compute', function(e){
            e.preventDefault();

            computeMedia();
        });

        $(document).on('change', '.compute_media input[name=total_value]', function(e){
            e.preventDefault();

            computeMedia();
        });

        $(document).on('change', '.compute_media .gst_apply', function(e){
            e.preventDefault();
            
            computeMedia();
        });
    });

    function computeMedia(){
        var amount         = $('.compute_media input[name=media_budget]');
        var total_value    = $('.compute_media input[name=total_value]');
        var discount       = $('.compute_media input[name=discount]');
        var management_fee = $('.compute_media input[name=management_fee]');

        var gst_percent    = $('.compute_media input[name=gst_percent]');
        var gst_amount     = $('.compute_media input[name=gst_amount]');
        var gst_apply      = $('.compute_media input[name=gst_apply]');
        var total_cost     = $('.compute_media input[name=total_cost]');

        if(amount.length && amount.val() != 0.00 && amount.val() != 0){
            amount = moneyClean(amount.val());
        }else{
            amount.val('0.00');
            amount = 0.00;
        } 

        if($('.auto_compute').is(":checked")){

            total_value.prop('readonly',true);

            var total = amount;  

            if(management_fee.length && management_fee.val() != 0.00 && management_fee.val() != 0){
                total = total+moneyClean(management_fee.val());
            }else{
                management_fee.val('0.00');
            } 

            if(discount.length && discount.val() != 0.00 && discount.val() != 0){
                total = total-moneyClean(discount.val());
            }else{
                discount.val('0.00');
            } 

            total_value.val((isNaN(total) || total == 0 ? '0.00' : formatMoney(total)));
        }else{

            if(total_value.val() == 0){
                total_value.val('0.00');
            }

            if(management_fee.val() == 0){
                management_fee.val('0.00');
            }

            if(discount.val() == 0){
                discount.val('0.00');
            }

            total_value.prop('readonly',false);
        }
        
        if(gst_apply.length && gst_apply.is(":checked")){
            total_gst = moneyClean(total_value.val())*percentageToDecimal(gst_percent.val());
        }else{
            total_gst = 0;
        }

        gst_amount.val((isNaN(total_gst) || total_gst == 0 ? '0.00' : formatMoney(total_gst)));
        // total_cost.val((isNaN(total_gst) || total_gst == 0 ? '0.00' : formatMoney(moneyClean(total_value.val())+total_gst)));
        total_cost.val(formatMoney(moneyClean(total_value.val())+total_gst));
    }

    //CAMPAIGN POSTPAID
    $(document).ready(function(){
        $(document).on('change', '.compute_postpaid input[name=utilized_budget]', function(e){
            e.preventDefault();

            computePostPaid();
        });

        $(document).on('change', '.compute_postpaid input[name=discount]', function(e){
            e.preventDefault();

            computePostPaid();
        });

        $(document).on('change', '.compute_postpaid input[name=management_fee]', function(e){
            e.preventDefault();

            computePostPaid();
        });

        $(document).on('change', '.compute_postpaid .auto_compute', function(e){
            e.preventDefault();

            computePostPaid();
        });

        $(document).on('change', '.compute_postpaid input[name=total_value]', function(e){
            e.preventDefault();

            computePostPaid();
        });

        $(document).on('change', '.compute_postpaid .gst_apply', function(e){
            e.preventDefault();
            
            computePostPaid();
        });

        $(document).on('change', '.compute_postpaid input[type=radio][name=paid_oom]', function(e){
            e.preventDefault();

            computePostPaid();
        });
    });

    function computePostPaid(){
        var amount                           = $('.compute_postpaid input[name=utilized_budget]');
        var total_value                      = $('.compute_postpaid input[name=total_value]');
        var discount                         = $('.compute_postpaid input[name=discount]');
        var management_fee                   = $('.compute_postpaid input[name=management_fee]');
        
        var gst_percent                      = $('.compute_postpaid input[name=gst_percent]');
        var gst_amount                       = $('.compute_postpaid input[name=gst_amount]');
        var gst_apply                        = $('.compute_postpaid input[name=gst_apply]');
        var total_cost                       = $('.compute_postpaid input[name=total_cost]');

        var administrative_charge_percent    = $('.compute_postpaid input[name=administrative_charge_percent]');
        var administrative_charge_amount     = $('.compute_postpaid input[name=administrative_charge_amount]');
        var administrative_charge_apply      = $('.compute_postpaid input[name=administrative_charge_apply]');


        if(amount.length && amount.val() != 0.00 && amount.val() != 0){
            amount = moneyClean(amount.val());
        }else{
            amount.val('0.00');
            amount = 0.00;
        } 

        if($('.auto_compute').is(":checked")){
         
            total_value.prop('readonly',true);

            administrative_charge_apply.prop('checked', true);
            administrative_charge_amount.prop('readonly',true);
            administrative_charge_apply.prop('disabled', true);

            var total = amount; 

            if(management_fee.length && management_fee.val() != 0.00 && management_fee.val() != 0){
                total = total+moneyClean(management_fee.val());
            }else{
                management_fee.val('0.00');
            }   

            if(discount.length && discount.val() != 0.00 && discount.val() != 0){
                total = total-moneyClean(discount.val());
            }else{
                discount.val('0.00');
            } 

            total_value.val((isNaN(total) || total == 0 ? '0.00' : formatMoney(total)));
        }else{
           
            administrative_charge_amount.prop('readonly',false);
            administrative_charge_apply.prop('checked', true);
            administrative_charge_apply.prop('disabled', false);
            
            administrative_charge_apply.change(function() {
                if ($(this).prop('checked')) {
                    administrative_charge_amount.prop('readonly', false); 
                } else {
                    administrative_charge_amount.prop('readonly', true); 
                }
            });

            if(management_fee.val() == 0){
                management_fee.val('0.00');
            }

            if(discount.val() == 0){
                discount.val('0.00');
            }

            if(total_value.val() == 0){
                total_value.val('0.00');
            }

            total_value.prop('readonly',false);
        }
        
        if(gst_apply.length && gst_apply.is(":checked")){
            total_gst = moneyClean(total_value.val())*percentageToDecimal(gst_percent.val());
        }else{
            total_gst = 0;
        }

        gst_amount.val((isNaN(total_gst) || total_gst == 0 ? '0.00' : formatMoney(total_gst)));
        // total_cost.val((isNaN(total_gst) || total_gst == 0 ? '0.00' : formatMoney(moneyClean(total_value.val())+total_gst)));
        total_cost.val(formatMoney(moneyClean(total_value.val())+total_gst));
    }

    //CAMPAIGN BASIC
    $(document).ready(function(){
        $(document).on('change', '.compute_basic select[name=payment_type_id]', function(e){
            e.preventDefault();

            computeBasic();
        });

        $(document).on('change', '.compute_basic select[name=campaign_duration_id]', function(e){
            e.preventDefault();

            computeBasic();
        });

        $(document).on('change', '.compute_basic select[name=campaign_duration_other]', function(e){
            e.preventDefault();

            computeBasic();
        });

        $(document).on('change', '.compute_basic input[name=campaign_duration_length]', function(e){
            e.preventDefault();

            computeBasic();
        });

        $(document).on('change', '.compute_basic input[name=discount]', function(e){
            e.preventDefault();

            computeBasic();
        });

        $(document).on('change', '.compute_basic input[name=total_budget]', function(e){
            e.preventDefault();

            computeBasic();
        });

        $(document).on('change', '.compute_basic select[name=service_package_id]', function(e){
            e.preventDefault();

            computeBasic();
        });

        $(document).on('change', '.compute_basic ,auto_compute', function(e){
            e.preventDefault();

            computeBasic();
        });

        $(document).on('change', '.compute_basic .gst_apply', function(e){
            e.preventDefault();
            
            computeBasic();
        });
    });

    function computeBasic(){
        var payment_type_id= $('select[name=payment_type_id]');
        var discount       = $('.compute_basic input[name=discount]');
        var total_budget   = $('.compute_basic input[name=total_budget]');
        var monthly_budget = $('.compute_basic input[name=monthly_budget]');
        var weekly_budget  = $('.compute_basic input[name=weekly_budget]');
        var daily_budget   = $('.compute_basic input[name=daily_budget]');
        var total_value    = $('.compute_basic input[name=total_value]');

        var duration       = $('select[name=campaign_duration_id]');
        var selected       = duration.find(':selected');
        var text           = selected.text(); 

        var month          = 0; 
        var week           = 0; 
        var day            = 0; 

        var package        = $('select[name=service_package_id]').find(':selected').text();

        var gst_percent    = $('.compute_basic input[name=gst_percent]');
        var gst_amount     = $('.compute_basic input[name=gst_amount]');
        var gst_apply      = $('.compute_basic input[name=gst_apply]');
        var total_cost     = $('.compute_basic input[name=total_cost]');

        if(total_budget.length && total_budget.val() != 0.00 && total_budget.val() != 0){
            amount = moneyClean(total_budget.val());
        }else{
            total_budget.val('0.00');
            amount = 0.00;
        } 

        if($('.auto_compute').is(":checked")){

            monthly_budget.prop('readonly',true);
            weekly_budget.prop('readonly',true);
            daily_budget.prop('readonly',true);
            total_value.prop('readonly',true);

            if(text == 'Others'){
                var other = $('select[name=campaign_duration_other]').find(':selected').text(); 
                var value = $('input[name=campaign_duration_length]').val();

                if(other == 'Weeks'){
                    month = 0;
                    week  = value;
                    day   = divide(divide(amount, week), 7);
                }else if(other == 'Days'){
                    month = 0;
                    week  = 0;
                    day   = divide(amount, value);
                }else{
                    month = value;
                    week  = value*4;
                    day   = divide(divide(amount, week), 7);
                }
            }else{
                month = selected.data('month'); 
                week  = selected.data('week'); 
                day   = divide(divide(amount, week), 7); 
            }

            /* HIDE THIS : WILL APPLY OPTIONAL AUTO COMPUTE
            if(package == 'Customized'){
                month = 1;
                week  = 4;
                day   = divide(divide(amount, week), 7); 
            }
            */

            monthly_budget.val(formatMoney(divide(amount, month), '0.00'));
            weekly_budget.val(formatMoney(divide(amount, week), '0.00'));
            daily_budget.val(formatMoney(day, '0.00'));

            var total = amount;
            if(month){
                total = amount*month;
            }

            if(discount.length && discount.val() != 0.00 && discount.val() != 0){
                total = total-moneyClean(discount.val());
            }else{
                discount.val('0.00');
            } 

            total_value.val(formatMoney(isNaN(total) || total == 0 ? '0.00' : total));
        }else{

            monthly_budget.prop('readonly',false);
            weekly_budget.prop('readonly',false);
            daily_budget.prop('readonly',false);
            total_value.prop('readonly',false);

            if(monthly_budget.val() == 0){
                monthly_budget.val('0.00');
            }
            if(weekly_budget.val() == 0){
                weekly_budget.val('0.00');
            }
            if(daily_budget.val() == 0){
                daily_budget.val('0.00');
            }
            if(total_value.val() == 0){
                total_value.val('0.00');
            }

            if(discount.val() == 0){
                discount.val('0.00');
            }
        }

        if(gst_apply.length && gst_apply.is(":checked")){
            total_gst = moneyClean(total_value.val())*percentageToDecimal(gst_percent.val());
        }else{
            total_gst = 0;
        }

        gst_amount.val((isNaN(total_gst) || total_gst == 0 ? '0.00' : formatMoney(total_gst)));
        // total_cost.val((isNaN(total_gst) || total_gst == 0 ? '0.00' : formatMoney(moneyClean(total_value.val())+total_gst)));
        total_cost.val(formatMoney(moneyClean(total_value.val())+total_gst));
    }

    // ANNUAL
    $(document).ready(function(){
        $(document).on('change', '.compute_annual input[name=monthly_value]', function(e){
            e.preventDefault();
            computeAnnual();
        });
        $(document).on('change', '.compute_annual input[name=discount]', function(e){
            e.preventDefault();
            computeAnnual();
        });
        $(document).on('change', '.compute_annual .auto_compute', function(e){
            e.preventDefault();
            computeAnnual();
        });

        $(document).on('change', '.compute_annual .gst_apply', function(e){
            e.preventDefault();
            
            computeAnnual();
        });
    });
    function computeAnnual(){
        var amount      = $('.compute_annual input[name=monthly_value]');
        var total_value = $('.compute_annual input[name=total_value]');
        var discount    = $('.compute_annual input[name=discount]');
        var gst_percent = $('.compute_annual input[name=gst_percent]');
        var gst_amount  = $('.compute_annual input[name=gst_amount]');
        var gst_apply   = $('.compute_annual input[name=gst_apply]');
        var total_cost  = $('.compute_annual input[name=total_cost]');

        if(amount.length && amount.val() != 0.00 && amount.val() != 0){
            amount = moneyClean(amount.val());
        }else{
            amount.val('0.00');
            amount = 0.00;
        }

        if($('.auto_compute').is(":checked")){
            total_value.prop('readonly',true);
            var total = amount;
            if(discount.length && discount.val() != 0.00 && discount.val() != 0){
                total = total-moneyClean(discount.val());
            }else{
                discount.val('0.00');
            }
            total_value.val((isNaN(total) || total == 0  ? '0.00' : formatMoney(total)));
        }else{
            if(discount.val() == 0){
                discount.val('0.00');
            }
            if(total_value.val() == 0){
                total_value.val('0.00');
            }
            total_value.prop('readonly',false);
        }

        if(gst_apply.length && gst_apply.is(":checked")){
            total_gst = moneyClean(total_value.val())*percentageToDecimal(gst_percent.val());
        }else{
            total_gst = 0;
        }

        gst_amount.val((isNaN(total_gst) || total_gst == 0 ? '0.00' : formatMoney(total_gst)));
        // total_cost.val((isNaN(total_gst) || total_gst == 0 ? '0.00' : formatMoney(moneyClean(total_value.val())+total_gst)));
        total_cost.val(formatMoney(moneyClean(total_value.val())+total_gst));
    }

    //CAMPAIGN DURATION DATE SET
    $(document).ready(function(){
        $(document).on('change', 'input[name=campaign_date_start]', function(e){
            e.preventDefault();

            campaignDateSet();
        });
        $(document).on('change', 'select[name=campaign_duration_other]', function(e){
            e.preventDefault();

            campaignDateSet();
        });
        $(document).on('change', 'input[name=campaign_duration_length]', function(e){
            e.preventDefault();

            campaignDateSet();
        });
    });

    function campaignDateSet(){
        var date     = $('input[name=campaign_date_start]').val();
        var campaign = new Date(date);
        var duration = $('select[name=campaign_duration_id]');
        var selected = duration.find(':selected');
        var text     = selected.text();
        
        if(date){
            if(text == 'Others'){
                var other = $('select[name=campaign_duration_other]').find(':selected').text();
                var value = $('input[name=campaign_duration_length]').val();
                if(other == 'Weeks'){
                    campaign.setDate(campaign.getDate() + parseInt(7*value));
                }else if(other == 'Days'){
                    campaign.setDate(campaign.getDate() + parseInt(value));
                }else if(other == 'Months'){
                    campaign.setMonth(campaign.getMonth() + parseInt(value));
                }
            }else{
                campaign.setMonth(campaign.getMonth() + selected.data('month'));
            }
            campaign.setDate(campaign.getDate() - 1);
            //$('input[name=campaign_date_end]').val(campaign.toLocaleDateString('en-US', {year: 'numeric', month: '2-digit', day: '2-digit'}));
            $('input[name=campaign_date_end]').val(dateDisplaySystem(campaign));
        }
    }

    //CMS
    $(document).ready(function(){
        $(document).on('change', 'select[name=cms_id]', function(e){
            e.preventDefault();

            cmsSystem();
        });

        if($('select[name=cms_id]').length){
            cmsSystem();
        }
    });
    
    function cmsSystem(){
        var cms      = $('select[name=cms_id]');
        var selected = cms.find(':selected');
        var text     = selected.text(); 

        if(text == 'Others'){
            $('textarea[name=cms_other]').attr('required', true);
            $('.cms_other').show();
        }else{
            $('textarea[name=cms_other]').removeAttr('required');
            clearFormFields('cms_other', '.');
            $('.cms_other').hide();
        }
    }

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

    /*
    function serviceInvoiceCompute(){
        var item              = [];
        var total             = [];
        var table_sub         = 0;
        var table_gst         = 0;
        var table_total       = 0;

        var discount          = moneyClean($('input[name=discount]').val());
        var gst_percent       = $('.gst_percent').html();
        var payment_term_type = $('select[name=payment_term_id] option:selected').data('code');
        var total_cost        = moneyClean($('input[name=total_cost]').val());

        $('.invoice-list tr').each(function(index, tr) {
            var id                 = $(this).attr('id');
            var unit_price         = moneyClean($('#unit_price_'+id).val());
            var quantity           = moneyClean($('#quantity_'+id).val());
            var gst_amount         = moneyClean($('#gst_'+id).val());

            if(payment_term_type == 'Percentage'){
                total_price = parseFloat(unit_price*quantity/100);
            }else{
                total_price = unit_price*percentageToNumber(quantity);
            }
            
            if(gst_percent > 0){
                item = percentageToAmount(total_price, gst_percent);
            }else{
                item['percent'] = formatMoney(gst_percent, '0.00'); 
                item['total']   = formatMoney(total_price, '0.00'); 
            }

            $('#gst_'+id).val(item['percent']);
            $('#amount_'+id).val(item['total']);

            table_sub += total_price;
        });

        $('.table_sub').html(formatMoney(table_sub, '0.00'));

        if(discount > 0){
            table_sub = parseFloat(table_sub)-parseFloat(discount);
        }

        if(gst_percent > 0){
            total = percentageToAmount(table_sub, gst_percent);
        }else{
            total['percent'] = formatMoney(gst_percent, '0.00'); 
            total['total']   = formatMoney(table_sub, '0.00'); 
        }
        
        $('.table_discount').html(formatMoney(discount, '0.00'));
        $('.table_gst').html(total['percent']);
        $('.table_total').html(total['total']);

        if(moneyClean(total['total']) != total_cost){
            $('.invoice-error').html('ERROR: Customized Payment Total Amount is not equal to Service Total Cost');
            $('.total_amount').addClass('tx-danger');
        }else{
            $('.invoice-error').html('');
            $('.total_amount').removeClass('tx-danger');
        }
    }

    function serviceInvoiceAdd(){
        var last_id = $('.invoice-list tr:last').attr('id');
        var id      = (isNaN(last_id) || last_id == 0 || last_id == 'undefined' || last_id == '' ? 0 : last_id);
        var item    = '';

        id++;

        item += '<td class="tx-center"><button type="button" class="align-self-center btn btn-sm btn-link invoice-remove" data-id="'+id+'"><i class="fa fa-trash fa-lg tx-danger"></i></button></td>';
        item += '<td><input name="description[]" id="description_'+id+'" class="form-control" type="text" autocomplete="off"></td>';
        item += '<td><input name="date_invoice[]" id="date_invoice_'+id+'" class="form-control calendar date_invoice" data-id="'+id+'" type="text" autocomplete="off" required></td>';
        item += '<td><input name="date_due[]" id="date_due_'+id+'" class="form-control calendar" type="text" autocomplete="off" required></td>';
        item += '<td><input name="unit_name[]" id="unit_name_'+id+'" maxlength="50" class="form-control" type="text" autocomplete="off"></td>';
        item += '<td><input name="unit_price[]" id="unit_price_'+id+'" class="form-control money tx-right compute" type="text" value="0.00" autocomplete="off" required></td>';
        item += '<td><input name="quantity[]" id="quantity_'+id+'" maxlength="3" class="form-control money tx-center compute" type="text" value="1.00" autocomplete="off"></td>';
        item += '<td><input name="gst[]" id="gst_'+id+'" class="form-control gst money tx-right" type="text" value="0.00" readonly autocomplete="off"></td>';
        item += '<td><input name="amount[]" id="amount_'+id+'" class="form-control money tx-right" type="text" value="0.00" readonly autocomplete="off"></td>';

        $('.invoice-list').append('<tr id="'+id+'">'+item+'</tr>');
        selectDropdown();
        
        $('.calendar').datepicker({ 
            dateFormat: 'dd-M-yy'
        });
    }

    function serviceInvoiceRemove(id){
        if(confirm('Are you sure you want to remove this item?')){
            $('.invoice-list tr#'+id).remove();

            if($('.invoice-list tr').length == 0){
                $('.invoice-add').click();
            }

            serviceInvoiceCompute();
        }
    }

    function serviceInvoicePayment(){
        if($('input[name=payment_customized]').is(':checked')){
            $("input[name='date_invoice[]'], input[name='date_due[]'], input[name='unit_price[]']").each(function(index,element) {
                $(this).attr('required', true);
            });
            $('.payment_customized').removeClass('hidden');
        }else{
            $("input[name='date_invoice[]'], input[name='date_due[]'], input[name='unit_price[]']").each(function(index,element) {
                $(this).removeAttr('required');
            });
            $('.payment_customized').addClass('hidden');
        }
    }

    function serviceInvoiceAllow(){
        $('input').each(function() {
            var name         = $(this).attr("name");
            var value        = $(this).val();
            var payment_term_id = $('select[name=payment_term_id]').find(":selected").val();  

            if(name == 'total_value'){
                if(moneyClean(value) > 0 && payment_term_id == 10){ //10 = Customized
                    $('input[name=payment_customized]').attr('disabled', false);
                    $("input[name='payment_customized']").prop("checked", true);
                    $('.payment_customized').removeClass('hidden');
                }else{
                    $('input[name=payment_customized]').attr('disabled', true); 
                    $("input[name='payment_customized']").prop("checked", false);
                    $('.payment_customized').addClass('hidden');
                }

                serviceInvoicePayment();
            }
        });
    }

    function serviceInvoiceDueDate(id){
        var credit_term_day = $('select[name=credit_term_id]').find(":selected").data('code');  

        if(credit_term_day == ''){
            credit_term_day = $('input[name=credit_term_length]').val();
            if(credit_term_day == ''){
                credit_term_day = 0;
            }
        }

        var date_invoice = $('#date_invoice_'+id).val();
        var date_due     = new Date(date_invoice);
            date_due.setDate(date_due.getDate() + parseInt(credit_term_day));

        $('#date_due_'+id).val(dateDisplaySystem(date_due));
    }
    */

    function customizedPaymentAdd(){
        var last_id  = $('.customized-payment tbody tr:last').attr('id');
        var id       = (isNaN(last_id) || last_id == 0 || last_id == 'undefined' || last_id == '' ? 0 : last_id);
        var item     = '';

        id++;

        var readonly = (id >= 2 ? ' readonly ' : ''); 

        item += '<td class="tx-center lh-24 counter" id="counter_'+id+'">'+ convertOrdinal(id)+'</td>';
        item += '<td><input name="customized_media_budget[]" id="customized_media_budget_'+id+'" class="form-control tx-right money customized" type="text" value="0.00" autocomplete="off"></td>';
        item += '<td><input name="customized_account_management_fee[]" id="customized_account_management_fee_'+id+'" class="form-control tx-right money customized" type="text" value="0.00" autocomplete="off"></td>';
        item += '<td><input name="customized_setup_fee[]" id="customized_setup_fee_'+id+'" class="form-control tx-right money customized" type="text" value="0.00" autocomplete="off" '+readonly+'></td>';
        item += '<td><input name="customized_other[]" id="customized_other_'+id+'" class="form-control tx-right money customized" type="text" value="0.00" autocomplete="off"></td>';
        item += '<td class="tx-right lh-24"><span class="sub_total" id="sub_total_'+id+'">0.00</td>';
        item += '<td class="tx-center"><button type="button" class="align-self-center btn btn-sm btn-link customized-payment-remove" data-id="'+id+'"><i class="fa fa-trash fa-lg tx-danger"></i></button></td>';

        $('.customized-payment tbody').append('<tr id="'+id+'">'+item+'</tr>');
        selectDropdown();
        
        $('.calendar').datepicker({ 
            dateFormat: 'dd-M-yy'
        });

        customizedPaymentCompute();
    }

    function customizedPaymentRemove(id){
        if(confirm('Are you sure you want to remove this item?')){
            $('.customized-payment tbody tr#'+id).remove();

            if($('.customized-payment tbody tr').length == 0){
                $('.customized-payment-add').click();
            }

            customizedPaymentCompute();

            var counter = 1;
            $('.customized-payment tbody .counter').each(function(index, item) {
                var id = $(this).prop('id');
                $('.customized-payment tbody #'+id).html(convertOrdinal(counter));  
                counter++;      
            });
        }
    }

    /*
    function customizedPaymentAllow(type, psg=''){
        var id  = $('select[name=payment_term_id]').find(":selected").val();  
        
        if((type == 'opportunity' && psg == 'Yes' && id == 10) || (type == 'service' && id == 10) ){ //10 = Customized
            $('.customized_payment').removeClass('hidden');
        }else{
            $('.customized_payment').addClass('hidden');
        }
    }
    */
    function customizedPaymentAllow(type, psg=''){
        var id  = $('select[name=payment_term_id]').find(":selected").val();  
        
        // if((type == 'opportunity' && psg == 'Yes' && id == 10) || (type == 'service' && id == 10) ){ //10 = Customized
        if((type == 'opportunity' && id == 10) || (type == 'service' && id == 10) ){ //10 = Customized
            $('.customized_payment').removeClass('hidden');
        }else{
            $('.customized_payment').addClass('hidden');
        }
    }

    

    // function customizedInvoiceDueDate(id){
    //     var credit_term_day = $('select[name=credit_term_id]').find(":selected").data('code');  

    //     if(credit_term_day == ''){
    //         credit_term_day = $('input[name=credit_term_length]').val();
    //         if(credit_term_day == ''){
    //             credit_term_day = 0;
    //         }
    //     }

    //     var date_invoice = $('#date_invoice_'+id).val();
    //     var date_due     = new Date(date_invoice);
    //         date_due.setDate(date_due.getDate() + parseInt(credit_term_day));

    //     $('#date_due_'+id).val(dateDisplaySystem(date_due));
    // }

    function customizedPaymentCompute(){

        var sub_total   = 0;
        var total       = 0;
        var total_value = moneyClean($('input[name=total_value]').val());

        $('.customized-payment tbody tr').each(function(index, tr) {
            var id                     = $(this).attr('id');
            var media_budget           = parseFloat(moneyClean($('#customized_media_budget_'+id).val()));
            var account_management_fee = parseFloat(moneyClean($('#customized_account_management_fee_'+id).val()));
            var setup_fee              = parseFloat(moneyClean($('#customized_setup_fee_'+id).val()));
            var other                  = parseFloat(moneyClean($('#customized_other_'+id).val()));

            sub_total += parseFloat(media_budget+account_management_fee)+parseFloat(setup_fee+other);
            total     += sub_total;

            $('.customized-payment tbody #sub_total_'+id).html(formatMoney(sub_total));   
            sub_total = 0;         
        });

        $('.customized-payment tfoot .total').html(formatMoney(total));

        if(total != total_value){
            $('.customized_payment .message').html('Customized Payment Total is not equal to Total Contract');
            $('.customized-payment tfoot').addClass('tx-danger');
        }else{
            $('.customized_payment .message').html('');
            $('.customized-payment tfoot').removeClass('tx-danger');
        }

        // var total_media_budget = 0;
        // var total_management_fee = 0;
        // var total_other = 0;
        
        // $(".customized-payment-list input[name='media_budget[]']").each(function(index, element){
        //     total_media_budget += parseFloat($(element).val());
        // });
        
        // $(".customized-payment-list input[name='management_fee[]']").each(function(index, element){
        //     total_management_fee += parseFloat($(element).val());
        // });
        
        // $(".customized-payment-list input[name='other[]']").each(function(index, element){
        //     total_other += parseFloat($(element).val());
        // }); 

        // $('.customized_total_media_budget').html(formatMoney(total_media_budget));
        // $('.customized_total_management_fee').html(formatMoney(total_management_fee)); 
        // $('.customized_total_other').html(formatMoney(total_other));
    }

    //DISCOUNT TYPE
    $(document).ready(function(){
        $(document).on('keypress keyup blur', '.service-discount input[name=discount]', function(e){
            var value      = $(this).val();
            var default_id = $(this).data('defaultid');
            var type_id    = $(this).data('typeid');
            var type_name  = $('.service-discount select[name=discount_type_id]'); 
            
            if(value == '' || value == 0 || value == 0.00){
                type_name.removeAttr('selected').find('option:first').attr('selected', true).trigger("change");
                type_name.attr('disabled', true);
            }else{
                type_name.attr('disabled', false);
                if(type_id == 0 || type_id == ''){
                    type_name.val(default_id).find("option[value=" + default_id +"]").attr('selected', true).trigger("change"); 
                }else{
                    type_name.val(type_id).find("option[value=" + type_id +"]").attr('selected', true).trigger("change");
                }
            }
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