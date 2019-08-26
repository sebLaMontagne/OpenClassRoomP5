$(function(){

    const FILE_MAX_SIZE = 10000000;
    const FILE_AUTHORIZED_EXTENSIONS = ['jpeg','png','tiff','gif','JPEG','PNG','TIFF','GIF'];

    function test(condition, target)
    {
        if(condition)
        {
            $(target).css('color','red');
        }
        else
        {
            $(target).css('color','green');
        }
    }

    $('#avatar').change(function(){

        test(this.files[0].size > FILE_MAX_SIZE,'#fileSize');
        test(FILE_AUTHORIZED_EXTENSIONS.indexOf(this.files[0].name.substring(this.files[0].name.lastIndexOf('.')+1, this.files[0].name.length)) == -1, '#fileExtension');

    });
});