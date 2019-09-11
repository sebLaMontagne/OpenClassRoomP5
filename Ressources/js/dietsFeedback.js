$(function(){

    $('.lead').css('display', 'none');
    $('#normal-feedback').css('display','block');

    $('select').change(function(){

        switch($('select').val())
        {
            case "normal":
                $('.lead').css('display','none');
                $('#normal-feedback').css('display','block');
            break;
            
            case "vegetarian":
                $('.lead').css('display','none');
                $('#vegetarian-feedback').css('display','block');
            break;

            case "vegan":
                $('.lead').css('display','none');
                $('#vegan-feedback').css('display','block');
            break;

            case "pesco-vegetarian":
                $('.lead').css('display','none');
                $('#pesco-vegetarian-feedback').css('display','block');
            break;

            case "pollotarian":
                $('.lead').css('display','none');
                $('#pollotarian-feedback').css('display','block');
            break;

            case "restriction":
                $('.lead').css('display','none');
                $('#restriction-feedback').css('display','block');
            break;
        }
    });
});