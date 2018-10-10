PPbox.init();
PPcheck.init();

$('.img-preview-form').on('click', function() {
    let row = $(this).parents('.img-preview-row');

    console.log(row);
    console.log(row.find('.img-preview-form-file'));

    row.find('.img-preview-form-file').trigger('click');
});

$('.img-preview-form-file').on('change', function(evt) {
    let row = $(this).parents('.img-preview-row');

    console.log(row.find('.img-preview-form').prop('src'));

    var files = evt.target.files; // FileList object
    var f = files[0];
    var reader = new FileReader();

    reader.onload = (function(theFile) {
        return function(e) {
            row.find('.img-preview-form').prop('src', e.target.result);
        };
    })(f);

    // Read in the image file as a data URL.
    reader.readAsDataURL(f);
/*
    var reader = new FileReader();

    // Closure to capture the file information.
    reader.onload = (function(theFile) {
        return function(e) {
            // Render thumbnail.
            var span = document.createElement('span');
            span.innerHTML = ['<img class="thumb" src="', e.target.result,
                '" title="', escape(theFile.name), '"/>'].join('');
            document.getElementById('list').insertBefore(span, null);
        };
    })(f);

    // Read in the image file as a data URL.
    reader.readAsDataURL(f);
    */
});
