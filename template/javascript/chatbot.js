var _chatbot_initial_open = true;
var _chatbot_box = $("#chat");
var _chatbot_message = $("#_chatbot_messsage_input");
var _chatbot_header_warning = $(".head-text-warning");
var _chatbot_time_element = '<div class="time"></div>';
var _chatbot_customer_element = '<div class="message customer"></div>';
var _chatbot_bot_element = '<div class="message"></div>';
var _chatbot_message_typing = '<div class="message bot-typing"><div class="typing typing-1"></div><div class="typing typing-2"></div><div class="typing typing-3"></div></div>';
var _chatbot_menu_message = '<div onclick="_chat_menu_selected()" class="message menu"></div>';
var _chatbot_menu_back = '<div onclick="_chat_menu_selected()" class="message menu"></div>';

// menu choices 
var _bot_menu = [
    { id: 1, response: 'Policy Renewal', disabled: true },
    { id: 2, response: 'Quotation Requests', disabled: false },
    { id: 3, response: 'Claims', disabled: false },
    { id: 4, response: 'Payments', disabled: false },
    { 
        id: 5, 
        response: 'I want to talk to a customer service representative', 
        disabled: false,
        children: [
            { id: 5.1, response: 'Continue talking to the Bot?', disabled: false },
            { id: 5.2, response: 'Send an email', disabled: false },
            { id: 5.3, response: 'No, you can now close the chatbox', disabled: false }
        ]
    }
];

// client details
var _client_getting_details = false;
var _client_getting_details_step = 1;
var _client_what_detail_getting = '';
var _client_name = '';
var _client_email = '';
var _client_mobile = '';
var _client_concern = '';

// intervals
var _check_agent_latest_reply = '';
var _check_if_agent_pickup = '';

// schedule
var _agent_starttime = '';
var _agent_endtime = '';
var _agent_is_available = 'no';

$(document).ready(function(){
    $("#_chatbot_messsage_send").on('click', function(e) {
        _chatbot_send_chat();
    });

    $("#_chatbot_messsage_input").on('keypress', function(e) {
        if(e.which == 13) {
            _chatbot_send_chat();
        }
    });

    $("#chat-box-click").on('click', function(e){
        _get_chat_agent_schedule();

        if(_chatbot_initial_open){
            _get_chatbot_menus();
            //_start_chat();
        }

        _check_chat_reply();

        if($(this).is(':checked')){
            $('.chat-box-tooltip').addClass('hidden');
        }else{
            // $('.chat-box-tooltip').removeClass('hidden');
        }
    });

    $("#_chatbot_open_menu").on('click', function(e) {
        _set_chat_menu(_bot_menu, 0);
    });
});

function _disable_chatbox(is_disable) {
    $("#_chatbot_messsage_input").attr('readonly',is_disable);
    $("#_chatbot_messsage_input").parent().css('display','none');
    if(!is_disable){
        $("#_chatbot_messsage_input").parent().css('display','flex');
        $("#_chatbot_messsage_input").focus();
    }
}

function _chatbot_send_chat() {
    const htmlEntities = {
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&apos;"
    };

    let __chatbot_message = _chatbot_message.val().replace(/([&<>\"'])/g, match => htmlEntities[match]);
    if(!__chatbot_message){
        return false;
    }

    _save_chat('client', __chatbot_message);
    _chatbot_box.append(_set_value_on_chatbot_element(_chatbot_customer_element, __chatbot_message));
    _chatbot_box.scrollTop(_chatbot_box[0].scrollHeight);

    // for getting the client details
    if(_client_getting_details){
        _disable_chatbox(true);

        if(_client_what_detail_getting == 'name'){
            _client_name = __chatbot_message;
        }else if(_client_what_detail_getting == 'email'){
            _client_email = __chatbot_message;
        }else if(_client_what_detail_getting == 'mobile'){
            _client_mobile = __chatbot_message;
        }else if(_client_what_detail_getting == 'concern'){
            _client_concern = __chatbot_message;
        }

        _chat_menu_selected('5.2.'+_client_getting_details_step);

        _chatbot_message.val('');

        return false;
    }

    _chatbot_message.val('');
}

function _chatbot_send_chat_from_menu(message, is_save=false) {
    if(is_save){
        _save_chat('client', message);
    }
    _chatbot_box.append(_set_value_on_chatbot_element(_chatbot_customer_element, message));
    _chatbot_box.scrollTop(_chatbot_box[0].scrollHeight);
}

function _set_value_on_chatbot_element(element,message,id=0) {
    if(id){
        element = element.replace("_chat_menu_selected()", "_chat_menu_selected('" +id + "')");
        element = element.replace("><", ">" +message + "<");
    }else{
        element = element.replace("><", ">" +message + "<");
    }
    return element;
}

function _send_chat_from_chatbot(bot_message, delay, is_save=false) {
    setTimeout(function(){ 
        if(Array.isArray(bot_message)){
            for (var i = 0; i < bot_message.length; i++) {
                $('.bot-typing').remove();

                if(is_save){
                    _save_chat('bot', bot_message[i]['response']);
                }

                _chatbot_box.append(_set_value_on_chatbot_element(_chatbot_bot_element, bot_message[i]['response']));
                _chatbot_box.scrollTop(_chatbot_box[0].scrollHeight);
            }
        }else{
            $('.bot-typing').remove();

            if(is_save){
                _save_chat('bot', bot_message);
            }

            _chatbot_box.append(_set_value_on_chatbot_element(_chatbot_bot_element, bot_message));
            _chatbot_box.scrollTop(_chatbot_box[0].scrollHeight);
        }

    }, delay);
}

function _fetch_bot_data(bot_menu_id) {
    if(bot_menu_id == '0' || bot_menu_id == 0){
        _set_chat_menu(_bot_menu, 1000);
        return false;
    }

    var bot_data = new Array();
    var bot_menu_id_exploded = bot_menu_id.split(".");
    var bot_menu_id_imploded = '';
    var bot_menu_selected = _bot_menu;


    for (var i = 0; i < bot_menu_id_exploded.length; i++) {
        var bot_menu_active_id = '';
        if(bot_menu_id_imploded == ''){
            bot_menu_id_imploded = bot_menu_id_exploded[i];
        }else{
            bot_menu_id_imploded += '.' + bot_menu_id_exploded[i];
        }

        for (var j = 0; j < bot_menu_selected.length; j++) {
            if(bot_menu_selected[j]['id'] == bot_menu_id_imploded){
                bot_data = bot_menu_selected[j]
                bot_menu_selected = bot_menu_selected[j]['children'];

                if(bot_menu_selected == undefined){
                    break;
                }
            }
        }
    }

    return bot_data;
}

function _set_chat_menu(bot_menu, delay, parent_id = 0) {
    setTimeout(function(){ 
        if(Array.isArray(bot_menu)){
            for (var i = 0; i < bot_menu.length; i++) {
                $('.bot-typing').remove();

                if(bot_menu[i]['id'] == '5' && _agent_is_available == 'no'){
                    _chatbot_box.append(_set_value_on_chatbot_element(_chatbot_menu_message, 'Contact Us', bot_menu[i]['id']));
                }else{
                    if(!bot_menu[i]['disabled']){
                        _chatbot_box.append(_set_value_on_chatbot_element(_chatbot_menu_message, bot_menu[i]['response'], bot_menu[i]['id']));
                    }
                }
            }

            if(parent_id){
                _chatbot_box.append(_set_value_on_chatbot_element(_chatbot_menu_back, 'Go Back', parent_id));
            }

            _chatbot_box.scrollTop(_chatbot_box[0].scrollHeight);
            _disable_chatbox(true);
        }else{
            $('.bot-typing').remove();

            _chatbot_box.append(_set_value_on_chatbot_element(_chatbot_menu_message, bot_menu, bot_menu_id));
            _chatbot_box.scrollTop(_chatbot_box[0].scrollHeight);
            _disable_chatbox(true);
        }
    }, delay);
}

function _chat_menu_selected(bot_menu_id) {
    var bot_menu_id_exploded = bot_menu_id.split(".");
    if(bot_menu_id_exploded[0] == 5 && _agent_is_available == 'yes'){
        if(bot_menu_id_exploded.length <= 1){
            _get_chat_agent_schedule();

            // menu choices 
            var chat_bot_no_agent_menu = new Array();
            var chat_bot_menu_message = '';

            var chatbot_menu_data = _fetch_bot_data(bot_menu_id_exploded[0]);
            chat_bot_menu_message = chatbot_menu_data['response'];
            chat_bot_no_agent_menu = chatbot_menu_data['children'];

            if (_agent_is_available == 'yes') {
                // @todo
                if(chat_bot_menu_message){
                    _chatbot_send_chat_from_menu(chat_bot_menu_message, true);
                }

                _send_chat_from_chatbot("Please wait while we assign a Customer Care Officer to assist you.", 700, true);

                $('.message.menu').remove();

                // 
                _disable_chatbox(false);
                _chat_start_live();

            } else {
                if(chat_bot_menu_message){
                    _chatbot_send_chat_from_menu(chat_bot_menu_message, true);
                }

                _chatbot_box.append(_chatbot_message_typing);
                _send_chat_from_chatbot("Our Customer Care, Claims Service Desk, and Claims Hotline are available during business hours of 8:00AM to 5:00PM.", 700, true);

                _set_chat_menu(chat_bot_no_agent_menu, 1000);

                $('.message.menu').remove();
            }
        }else{
            var chatbot_menu_data = _fetch_bot_data(bot_menu_id_exploded[0] + '.' + bot_menu_id_exploded[1]);
            chat_bot_menu_message = chatbot_menu_data['response'];

            if(bot_menu_id_exploded[1] == 1){

                _chatbot_send_chat_from_menu(chat_bot_menu_message, true);

                $('.message.menu').remove();

                _chatbot_box.append(_chatbot_message_typing);
                _send_chat_from_chatbot("Tell me what is your concern?", 500, true);
                _set_chat_menu(_bot_menu, 1000);

            }else if(bot_menu_id_exploded[1] == 2){

                _client_getting_details = true;

                var step = bot_menu_id_exploded[2] == undefined ? _client_getting_details_step : bot_menu_id_exploded[2];
                if(step == 1){
                    $('.message.menu').remove();
                    _chatbot_send_chat_from_menu(chat_bot_menu_message, true);

                    _chatbot_box.append(_chatbot_message_typing);
                    _send_chat_from_chatbot("What is your name?", 500, true);

                    _client_what_detail_getting = 'name';
                    _client_getting_details_step++;
                }else if(step == 2){

                    _chatbot_box.append(_chatbot_message_typing);
                    _send_chat_from_chatbot("What is your email address?", 500, true);

                    _client_what_detail_getting = 'email';
                    _client_getting_details_step++;
                }else if(step == 3){

                    _chatbot_box.append(_chatbot_message_typing);
                    _send_chat_from_chatbot("What is your mobile number?", 500, true);

                    _client_what_detail_getting = 'mobile';
                    _client_getting_details_step++;
                }else if(step == 4){

                    _chatbot_box.append(_chatbot_message_typing);
                    _send_chat_from_chatbot("Now what is your concern?", 500, true);

                    _client_what_detail_getting = 'concern';
                    _client_getting_details_step++;
                }else if(step == 5){

                    _chatbot_box.append(_chatbot_message_typing);
                    _send_chat_from_chatbot("Thank you for giving us your contact details "+_client_name+", will get back to you as soon as possible.", 500, true);
                    _client_getting_details_step++;
                    _client_getting_details = false;

                    setTimeout(function(){ 
                        $('#chat-box-click').prop('checked', false);
                    }, 1000)

                    _chatbot_initial_open = true;
                }

                _disable_chatbox(false);

            }else if(bot_menu_id_exploded[1] == 3){

                _chatbot_send_chat_from_menu(chat_bot_menu_message, true);

                $('.message.menu').remove();
                _chatbot_box.append(_chatbot_message_typing);
                _send_chat_from_chatbot("Thank you for chatting me! Bye!", 300, true);

                setTimeout(function(){ 
                    $('#chat-box-click').prop('checked', false);
                }, 1000)

                _chatbot_initial_open = true;
            }
        }
    }else{
        $('.message.menu').remove();
        var chatbot_menu_data = _fetch_bot_data(bot_menu_id);
        if(bot_menu_id == '0' || bot_menu_id == 0){
            return false;
        }

        var chat_bot_menu_message = chatbot_menu_data['response'];
        var chat_bot_menu_parent = chatbot_menu_data['parent'];
        var chat_bot_menu = chatbot_menu_data['children'];

        if(chatbot_menu_data['have_children']){
            if(chat_bot_menu_message){
                _chatbot_send_chat_from_menu(chat_bot_menu_message, true);
            }

            _chatbot_box.append(_chatbot_message_typing);
            
            _set_chat_menu(chat_bot_menu, 1000, chat_bot_menu_parent);
        }else{
            if(chat_bot_menu_message){
                _chatbot_send_chat_from_menu(chat_bot_menu_message, true);
            }

            if(bot_menu_id_exploded[0] == 5 && _agent_is_available == 'no'){
                _send_chat_from_chatbot("Now redirecting you to our Contact Us Page.", 100, true);
                setTimeout(function(){ 
                    window.location.href= '/contact/us/';
                }, 1000);
                
                return false;
            }

            console.log(chatbot_menu_data);

            if(chatbot_menu_data['bot_answer'] == 'null' || chatbot_menu_data['bot_answer'] == '' || chatbot_menu_data['bot_answer'] == null){
                _send_chat_from_chatbot('I am really sorry this option is not yet available.', 500, true);
            }else{
                _send_chat_from_chatbot(chatbot_menu_data['bot_answer'], 500, true);
            }

            _set_time_message();

            _chatbot_box.append(_chatbot_message_typing);

            _send_chat_from_chatbot("Is there anything else I can help you with today?", 2000, true);
            _set_chat_menu(_bot_menu, 3000);
        }

        $('.message.menu').remove();
    }
}

function _start_chat() {
    $.ajax({
        url: '/contact/startChat_json/',
        type: 'GET', 
        beforeSend: function(){

        },
        success: function(data) {
            if(data.status == 'success'){
                if(data.messages != undefined){
                    _set_session_messages(data.messages);

                    if(data.is_agent_pickup == 'no' && data.is_client_want_live_chat == 'no'){
                        _chatbot_initial_open = false;

                        _set_time_message();

                        _chatbot_box.append(_chatbot_message_typing);
                        _set_chat_menu(_bot_menu, 1000);
                    }else if(data.is_agent_pickup == 'no' && data.is_client_want_live_chat == 'yes'){
                        _chatbot_box.append(_chatbot_message_typing);
                        _check_if_pickup()
                    }
                }else{
                    _set_initial_messages();
                }
            }
        },
        error: function(e) {
            console.log(e.message);
        }
    });
}

function _set_initial_messages() {
    _set_time_message();

    // setting initial message of chatbot
    _chatbot_box.append(_chatbot_message_typing);
    _send_chat_from_chatbot("Hello! I'm SAM, your self-service automated messenger.", 700, true);
    _send_chat_from_chatbot("How can I help you today?", 700, true);

    _chatbot_initial_open = false;
    _set_chat_menu(_bot_menu, 1000);
}

function _set_time_message() {
    // setting time on chatbot
    var _chatbot_time_now = new Date();
    _chatbot_time_now = _chatbot_time_now.toLocaleTimeString('en-US');
    _chatbot_time_now = _chatbot_time_now.toString().split(":");
    _chatbot_time_now = [_chatbot_time_now[0], _chatbot_time_now[1]].join(':');

    var _chatbot_time_element_append = _chatbot_time_element;
    _chatbot_time_element_append = _set_value_on_chatbot_element(_chatbot_time_element_append, "Today at "+_chatbot_time_now);

    _chatbot_box.append(_chatbot_time_element_append);
}

function _set_session_messages(messages) {
    var set_chatbot_time = true;
    $.each(messages,function(index, value){
        if(value.responder_type == 'bot' || value.responder_type == 'agent'){
            _chatbot_box.append(_set_value_on_chatbot_element(_chatbot_bot_element, value.message));
        }else if(value.responder_type == 'client'){
            _chatbot_box.append(_set_value_on_chatbot_element(_chatbot_customer_element, value.message));
        }
        _chatbot_box.scrollTop(_chatbot_box[0].scrollHeight);
    });
}

function _save_chat(responder, message) {
    $.ajax({
        url: '/contact/saveChat_json/',
        type: 'POST', 
        data: {
            responder: responder,
            message: message
        },  
        dataType: 'json',
        beforeSend: function(){

        },
        success: function(data) {
            // console.log(data);
        },
        error: function(e) {
            console.log(e.message);
        }
    });
}

function _check_chat_reply() {
    _check_agent_latest_reply = setInterval(function(){ 
        $.ajax({
            url: '/contact/getLatestAgentReply_json/',
            type: 'GET', 
            beforeSend: function(){

            },
            success: function(data) {
                if(data.message != undefined){
                    _send_chat_from_chatbot(data.message, 100, false);
                }
            },
            error: function(e) {
                console.log(e.message);
            }
        });
    }, 2000);
}

function _chat_start_live() {
    $.ajax({
        url: '/contact/startLiveChat_json/',
        type: 'POST', 
        dataType: 'json',
        beforeSend: function(){
            _chatbot_box.append(_chatbot_message_typing);
        },
        success: function(data) {
            _check_if_pickup()
        },
        error: function(e) {
            console.log(e.message);
        }
    });
}

function _check_if_pickup() {
    _check_if_agent_pickup = setInterval(function(){ 
        $.ajax({
            url: '/contact/checkIfAgentPickup_json/',
            type: 'GET', 
            beforeSend: function(){

            },
            success: function(data) {
                // console.log(data);
            },
            error: function(e) {
                console.log(e.message);
            }
        });
    }, 3000);
}

function _get_chatbot_menus() {
    $.ajax({
        url: '/contact/getAllChatbotMenu_json/',
        type: 'POST', 
        dataType: 'json',
        beforeSend: function(){

        },
        success: function(data) {
            _bot_menu = data;
        },
        error: function(e) {
            console.log(e.message);
        }
    });
}

function _get_chat_agent_schedule() {
    $.ajax({
        url: '/contact/getChatAgentSchedule_json/',
        type: 'POST', 
        dataType: 'json',
        beforeSend: function(){

        },
        success: function(data) {
            if(data){
                _agent_starttime = data.start_datetime;
                _agent_endtime = data.end_datetime;
                _agent_is_available = data.is_agent_available;

                if(_agent_is_available == 'yes'){
                    _chatbot_header_warning.addClass('hidden');
                    var chat_height = window.innerHeight - 160;

                    $('.chat').css('height', chat_height+'px');
                }else{
                    _chatbot_header_warning.removeClass('hidden');
                    var chat_height = window.innerHeight - 220;

                    $('.chat').css('height', chat_height+'px');
                }
            }
        },
        error: function(e) {
            console.log(e.message);
        }
    });
}

function _send_chatbot_email() {
    $.ajax({
        url: '/contact/sendChatbotEmail_json/',
        type: 'POST', 
        data: {
            name:_client_name,
            email:_client_email,
            mobile:_client_mobile,
            concern:_client_concern
        },  
        dataType: 'json',
        beforeSend: function(){

        },
        success: function(data) {
            console.log(data);
        },
        error: function(e) {
            console.log(e.message);
        }
    });
}