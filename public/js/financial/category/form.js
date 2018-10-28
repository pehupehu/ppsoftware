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
    let that = $(this),
        options = {};

    options.buttons = {
        0: {
            text: Translator.trans('generic.diag_confirm.confirm'),
            callback: true,
            click: function () {
                that.parents('.row-children').remove();
                PPbox.close('removeChilren');
            }
        },
        1: {
            text: Translator.trans('generic.diag_confirm.cancel'),
        }
    };

    PPbox.confirm('removeChilren', Translator.trans('generic.diag_confirm.title'), Translator.trans('generic.diag_confirm.message'), options);
});