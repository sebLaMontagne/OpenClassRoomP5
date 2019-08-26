$(function(){

    const FILE_AUTHORIZED_EXTENSIONS = ['jpeg','png','tiff','jpg','JPEG','PNG','TIFF','JPG'];

    var ingredientCount = 1;
    var stepCount = 1;

    //ajout/suppression d'ingrédients
    $('#addIngredient').click(function(){
        
        ingredientCount++;
        var elm = $('#ingredient1').clone();
        elm.attr('id','ingredient'+ingredientCount);
        elm.find('select').attr('name','ingredient'+ingredientCount).attr('id','ingredientName'+ingredientCount);
        elm.find('input').attr('name', 'quantityIngredient'+ingredientCount).val('0');
        elm.find('.dishEditor_unity').attr('name','ingredientUnit'+ingredientCount).val('');
        elm.insertBefore('#addIngredient');

    });
    $('#removeIngredient').click(function(){
        $('#ingredient'+ingredientCount).remove();
        ingredientCount--;
    });

    //ajout/suppression d'étapes
    $('#addStep').click(function(){
        
        stepCount++;
        var elm = $('#step1').clone();
        elm.attr('id','step'+stepCount);
        elm.find('textarea').attr('name','step'+stepCount).val('');
        elm.append('<span class="removeStep" id="removeStep'+stepCount+'"></span>');
        elm.find('span').click(function(){ $(this).closest('div').remove()});
        elm.insertBefore('#addStep');
    });

    $('#removeStep').click(function(){
        $('#step'+stepCount).remove();
        stepCount--;
    });

    //controle des boutons
    $('form').click(function(){

        if(ingredientCount == 1)
        {
            $('#removeIngredient').attr('disabled', true);
        }
        else
        {
            $('#removeIngredient').attr('disabled', false);
        }

        if(stepCount == 1)
        {
            $('#removeStep').attr('disabled', true);
        }
        else
        {
            $('#removeStep').attr('disabled', false);
        }
    });


    //Gestion des feedbacks
    $('form').change(function(){

        var ingredientList = [];
        var disabled = false;

        $('#name_not_empty').hide('fast');
        $('#preparation_time_min').hide('fast');
        $('#valid_share_number').hide('fast');
        $('#valid_dish_type').hide('fast');
        $('#file_is_set').hide('fast');
        $('#file_is_valid').hide('fast');
        $('#all_ingredients_fields_filled').hide('fast');
        $('#all_ingredients_unities_filled').hide('fast');
        $('#all_ingredients_unities_informed').hide('fast');
        $('#ingredient_unicity').hide('fast');
        $('#all_steps_filled').hide('fast');

        //le nom du plat ne doit pas être vide
        if($('#dishName').val() == '')
        {
            $('#name_not_empty').show('fast');
            disabled = true;
        }

        //le temps ne peut pas être inférieur à 5 minutes ou pas nul
        if($('#preparationTime').val() < 5 || $('#preparationTime').val() == '')
        {
            $('#preparation_time_min').show('fast');
            disabled = true;
        }

        //il faut s'assurer que le nombre de parts soit au moins égal à 1 ou pas nul
        if($('#shareNb').val() < 1 || $('#shareNb').val() == '')
        {
            $('#valid_share_number').show('fast');
            disabled = true;
        }

        //il faut renseigner un type de plat
        if($('#dishType')[0].value == '')
        {
            $('#valid_dish_type').show('fast');
            disabled = true;
        }

        //il faut un fichier renseigné
        if($('#dishImage')[0].files.length == 0)
        {
            $('#file_is_set').show('fast');
        }
        //il faut que le fichier soit au bon format
        if($('#dishImage')[0].files[0] !== undefined)
        {
            var fileName = $('#dishImage')[0].files[0].name;
            var fileExtention = fileName.substr(fileName.lastIndexOf('.')+1, fileName.length);
            if(FILE_AUTHORIZED_EXTENSIONS.indexOf(fileExtention) == -1)
            {
                $('#file_is_valid').show('fast');
                disabled = true;
            }
        }

        for(let i = 1; i <= ingredientCount; i++)
        {
            //il faut que tous les champs ingredient soient renseignés
            if($('#ingredient'+i+' select').val() == '')
            {
                $('#all_ingredients_fields_filled').show('fast');
                disabled = true;
            }

            //il faut que les quantités pour les ingrédients soient renseignées
            if($('#ingredient'+i+' input').val() == 0)
            {
                $('#all_ingredients_unities_filled').show('fast');
                disabled = true;
            }

            //il faut que les unités pour les ingrédients soient renseignées
            if($('#ingredient'+i+' .dishEditor_unity').val() == '')
            {
                $('#all_ingredients_unities_informed').show('fast');
                disabled = true;
            }

            //construction de la liste d'ingredients pour le check d'ingredients uniques
            ingredientList.push($('#ingredientName'+i).val());
        }

        //unicité des ingredients
        ingredientList = ingredientList.sort();
        for(let i = 1; i < ingredientList.length; i++)
        {
            if(ingredientList[i-1] == ingredientList[i])
            {
                $('#ingredient_unicity').show('fast');
                disabled = true;
            }
        }

        //s'assurer que toutes les étapes soient renseignées
        for(let i = 1; i <= stepCount; i++)
        {
            if($('#step'+i+' textarea').val() == '')
            {
                $('#all_steps_filled').show('fast');
                disabled = true;
            }
        } 

        $('#submit').attr('disabled', disabled);
    });
});