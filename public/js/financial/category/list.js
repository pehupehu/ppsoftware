let childrens = $('.childrens');
let parents = $('.parent-droppable');
$('.children-draggable', childrens).draggable({
    revert: 'invalid',
    containment: "document",
    helper: 'clone',
    cursor: 'move'
});
parents.droppable({
    classes: {
        'ui-droppable-hover': 'ui-state-hover'
    },
    drop: function(event, ui) {
        moveChildren(ui.draggable, $(this));
    }
});

let moveChildren = function (children, parent) {
    let children_id = children.data('children-id');
    let parent_id = parent.data('parent-id');

    if (!children_id || !parent_id) {
        printError();
        return;
    }

    let url = Routing.generate('financial_category_move', {'children_id': children_id, 'parent_id': parent_id}, true);
    $.post(url, function() {})
        .done(function() {
            // TODO order by
            children.appendTo(parent.children('.childrens'));
        })
        .fail(function() {
            printError();
    });
};

let printError = function () {
    PPbox.alert('financial_category_move', Translator.trans('financial.category.title.move'), Translator.trans('financial.category.message.warning.move'), 'warning');
};