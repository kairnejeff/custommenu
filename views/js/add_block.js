var $addNewItem = $('<a href="#" class="btn btn-info">Add new item</a>');
$(document).ready(function () {
    $collectionHolder = $('#menu_block_listLink');
    $collectionHolder.append($addNewItem);
    $collectionHolder.data('menu_block_listLink', $collectionHolder.find('.panel').length)
    $collectionHolder.find('.panel').each(function () {
        addRemoveButton($(this));
    });
    $addNewItem.click(function (e) {
        e.preventDefault();
        addNewForm();
    })
})