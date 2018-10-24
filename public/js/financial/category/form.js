let addButton = $('#add-children'),
    collectionHolder = $('.childrens');

addButton.on('click', function(e) {
    addTagForm(collectionHolder);
});

function addTagForm(collectionHolder) {
    let prototype = collectionHolder.data('prototype'),
        index = collectionHolder.data('index'),
        newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    collectionHolder.data('index', index + 1);

    collectionHolder.append(newForm);
}

collectionHolder.on('click', '.del-children', function () {
    let index = collectionHolder.data('index');
    
    $(this).parents('.row-children').remove();
});