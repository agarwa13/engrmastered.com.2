var bodyEditor = CKEDITOR.replace( 'body' , {skin : 'icy_orange,/vendor/ckeditor/skins/icy_orange/'});

var titleEditor = $("input:text[name=title]");

titleEditor.change(function(){
    $('#title-wrapper').html(titleEditor.val());
});

bodyEditor.on('change',function(){
    data = bodyEditor.getData();

    // Without Repeat Fields
    var re1 = /{{([^}r,]+)(?:,([^}]+))?}}/g;

    // Including Repeat Fields
    var re2 = /{{([^},]+)(?:,([^}]+))?}}/g;

    data = data.replace(re1, function(match, p1, p2, offset, string){
        return '<input size="5" class="question-input" type="text" name="var' + p1  + '"' + (p2 ? ' value="' + p2 +  '"' : '') + '>';
    });

    data = data.replace(re2, function(match, p1, p2, offset, string){
        p1 = p1.substring(0, p1.length-1);
        return '<input size="5" type="text" class="repeated-question-input repeated-var' + p1 + '" disabled>';
    });

    $('#body-wrapper').html(data);
    MathJax.Hub.Queue(["Typeset",MathJax.Hub,'body-wrapper']);
    $('.question-input').change();
});



// This makes sure that the repeat fields update
$(".question-input").change(function (event) {
    // Get the input field that changed
    changedInput = $(event.target);

    // Find all associated repeat fields
    selectorString = "." + "repeated-" + changedInput.attr('name');
    repeatFields = $(selectorString);

    repeatFields.each(function (index) {
        $(this).val(changedInput.val());
    });
});