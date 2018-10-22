let addButton = $('#add-children'),
    collectionHolder = $('.childrens');

addButton.on('click', function(e) {
    console.log('click');
    // add a new tag form (see next code block)
    addTagForm(collectionHolder);
});

function addTagForm(collectionHolder) {
    let prototype = collectionHolder.data('prototype'),
        index = collectionHolder.data('index'),
        newForm = prototype;

    console.log('prototype', prototype);
    console.log('index', index);
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    collectionHolder.append(newForm);
}
