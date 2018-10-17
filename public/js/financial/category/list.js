$( ".category-draggable" ).draggable({ revert: "invalid" });
$( ".category-droppable" ).droppable({
    classes: {
        "ui-droppable-active": "ui-state-active",
        "ui-droppable-hover": "ui-state-hover"
    },
    drop: function( event, ui ) {
        $(this).html( "Dropped!" );
    }
});