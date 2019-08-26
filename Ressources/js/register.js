$(function(){

    //-------------------------------------------------------------------------------------------------------------//
    //----------------------------------------------------REGEXS---------------------------------------------------//
    //-------------------------------------------------------------------------------------------------------------//

    const regexEmail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    const regexUsername = /^.{6,}$/;
    const regexPassword = /^.{8,}$/;
    const regexLowercase = /[a-z]/;
    const regexUppercase = /[A-Z]/;
    const regexFigure = /[0-9]/;
    const regexFileExtension = /(.*?)\.(jpg|jpeg|gif|tiff|png)$/;

    const FILE_MAX_SIZE = 10000000;
 
    //-------------------------------------------------------------------------------------------------------------//
    //---------------------------------------------------FUNCTIONS-------------------------------------------------//
    //-------------------------------------------------------------------------------------------------------------//

    function inputDataChecker(inputID, infoID, regexToCheck)
    {
        $(inputID).focus(function(){

            $(infoID).show('fast');
            if(regexToCheck.test($(inputID).val()))
            {
                $(infoID).addClass('register_info_OK').removeClass('register_info_NOK');
            }
            else
            {
                $(infoID).addClass('register_info_NOK').removeClass('register_info_OK');
            }
    
        });
    
        $(inputID).blur(function(){
    
            $(infoID).hide('fast');
    
        });
    
        $(inputID).keyup(function(){
    
            if(regexToCheck.test($(inputID).val()))
            {
                $(infoID).addClass('register_info_OK').removeClass('register_info_NOK');
            }
            else
            {
                $(infoID).addClass('register_info_NOK').removeClass('register_info_OK');
            }
        });
    }

    //-------------------------------------------------------------------------------------------------------------//
    //----------------------------------------TREATMENTS ON REGISTER INPUTS----------------------------------------//
    //-------------------------------------------------------------------------------------------------------------//

    //colors controllers depending on input values
    inputDataChecker('#registerName', '#infoPseudoLength', regexUsername);
    inputDataChecker('#registerEmail', '#infoValidEmail', regexEmail);

    inputDataChecker('#registerPassword', '#infoPasswordLength', regexPassword);
    inputDataChecker('#registerPassword', '#infoPasswordLowercase', regexLowercase);
    inputDataChecker('#registerPassword','#infoPasswordUppercase', regexUppercase);
    inputDataChecker('#registerPassword','#infoPasswordFigure', regexFigure);

    inputDataChecker('#registerPasswordConfirmation', '#infoPasswordLength', regexPassword);
    inputDataChecker('#registerPasswordConfirmation', '#infoPasswordLowercase', regexLowercase);
    inputDataChecker('#registerPasswordConfirmation','#infoPasswordUppercase', regexUppercase);
    inputDataChecker('#registerPasswordConfirmation','#infoPasswordFigure', regexFigure);

    //particuliar traitment for file imput
    $('#registerAvatar').change(function() 
    {
        $('#infoFileSize').show('fast');
        $('#infoFileExtension').show('fast');

        if(!(regexFileExtension.test($(this).val().toLowerCase())))
        {
            $('#infoFileExtension').addClass('register_info_NOK').removeClass('register_info_OK');
        }
        else
        {
            $('#infoFileExtension').addClass('register_info_OK').removeClass('register_info_NOK');
        }

        if (this.files[0].size > FILE_MAX_SIZE)
        {
            $('#infoFileSize').addClass('register_info_NOK').removeClass('register_info_OK');
        }
        else
        {
            $('#infoFileSize').addClass('register_info_OK').removeClass('register_info_NOK');
        }
    });
    $('#registerAvatar').blur(function()
    {
        $('#infoFileSize').hide('fast');
        $('#infoFileExtension').hide('fast');
    });

    //submit register button activation
    $('#registration').bind('keyup change', function(){

        if( $('#infoPseudoLength').hasClass('register_info_OK') &&
            $('#infoValidEmail').hasClass('register_info_OK') &&
            $('#infoPasswordLength').hasClass('register_info_OK') &&
            $('#infoPasswordLowercase').hasClass('register_info_OK') &&
            $('#infoPasswordUppercase').hasClass('register_info_OK') &&
            $('#infoPasswordFigure').hasClass('register_info_OK') &&
            $('#infoFileSize').hasClass('register_info_OK') &&
            $('#infoFileExtension').hasClass('register_info_OK') &&
            $('#registerPassword').val() == $('#registerPasswordConfirmation').val()
            )
        {
            $('#confirmRegistration').attr('disabled', false);
        }
        else
        {
            $('#confirmRegistration').attr('disabled', true);
        }
    });

    //submit connexion button activation
    $('#connexion').keyup(function(){

        if($('#connexionName').val() != '' && $('#connexionPassword').val() != '')
        {
            $('#confirmConnexion').attr('disabled', false);
        }
        else
        {
            $('#confirmConnexion').attr('disabled', true);
        }
    });
});