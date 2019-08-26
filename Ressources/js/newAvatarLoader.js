$(function(){

    $('.account_avatar').css('cursor', 'pointer');

    $('#newAvatar').change(function(){
        $('#newAvatarForm').submit();
    });

    $('.account_avatar').click(function(){
        $('#newAvatar').click();
    });
});