$(document).ready(function() {
    $('#refreshcaptcha').click(function() { 
        $.ajax({ 
            url: '/user/refresh', 
            dataType:'json', 
            success: function(data) { 
                $('.imgcaptcha img').attr('src', data.src); 
                $('#captcha-id').attr('value', data.id); 
            }
        }); 
    }); 
});