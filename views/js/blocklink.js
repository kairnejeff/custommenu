
/*
$(document).ready(function(e) {
    $('#menu_block_save').click(function() {
        console.log($('#menu_block_link'))
        var $id=$('#menu_block_link').val()
        var $input = $('<input>')
        $input.attr("listLink")
        $input.attr('value',$id)
        $('#menu_block_listLink').append($input)
    })
})*/

var $collectionHolder;

/*
 * creates a new form and appends it to the collectionHolder
 */
function addNewForm() {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    $collectionHolder.data('index', index+1);
    var $panel = $('<div class="panel panel-warning"><div class="panel-heading"></div></div>');
    var $panelBody = $('<div class="panel-body"></div>').append(newForm);
    $panel.append($panelBody);
    addRemoveButton($panel);
    $addNewItem.before($panel);
}

/**
 * adds a remove button to the panel that is passed in the parameter
 * @param $panel
 */
function addRemoveButton ($panel) {
    var $removeButton = $('<a href="#" class="btn btn-danger">Remove</a>');
    var $panelFooter = $('<div class="panel-footer"></div>').append($removeButton);
    $removeButton.click(function (e) {
        e.preventDefault();
        $(e.target).parents('.panel').slideUp(1000, function () {
            $(this).remove();
        })
    });
    $panel.append($panelFooter);
}

function addRowRemoveButton($block){
    var $button= "<div class=\"panel-footer\"><a href=\"#\" class=\"btn btn-danger remove-item\">Remove</a></div>"
    $block.children(".form-group").each(function() {
        $(this).append($button)
    })
    $('.remove-item').click(function (e) {
        e.preventDefault();
        $(e.target).parent().parent('.form-group').slideUp(1000, function () {
            $(this).remove();
        })
    })

}