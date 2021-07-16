var $addNewItem = $('<a href="#" class="btn btn-info">Add new block</a>');
$(document).ready(function () {
    hideBlock()
    $('#menu_item_is_single_link').change(function (){
        hideBlock()
    })
})
$(document).ready(function () {
    $collectionHolder = $('#menu_item_listBlock');
    $collectionHolder.append($addNewItem);
    $collectionHolder.data('index', $collectionHolder.children('.form-group').length)
    $collectionHolder.find('.panel').each(function () {
        addRemoveButton($(this));
    });
    addRowRemoveButton($collectionHolder);
    $addNewItem.click(function (e) {
        e.preventDefault();
        addNewForm();

    })
})
function hideBlock(){
    if($('#menu_item_is_single_link').val()==1){
        $('#menu_item_listBlock').parent().parent().hide()
    }else{
        $('#menu_item_link').parent().parent().hide()
        $('#menu_item_listBlock').parent().parent().show()
    }
}
