
$('.img-preview-form').on('click', function() {
    let row = $(this).parents('.img-preview-row');

    console.log(row);
    console.log(row.find('.img-preview-form-file'));

    row.find('.img-preview-form-file').trigger('click');
});

$('.img-preview-form-file').on('change', function(evt) {
    let row = $(this).parents('.img-preview-row'),
        files = evt.target.files,
        f = files[0],
        reader = new FileReader();

    reader.onload = (function() {
        return function(e) {
            row.find('.img-preview-form').prop('src', e.target.result);
        };
    })(f);

    // Read in the image file as a data URL.
    reader.readAsDataURL(f);
});

let switchOptionToAllowed = function(src, dest) {
        if (src.length && dest.length) {
            let opt = new Option(src.text(), src.val());
            opt.ondblclick = bindOptionOnDblclickAllowed;
            dest.append(opt);
            src.remove();
        }
    },
    switchOptionToUnallowed = function(src, dest) {
        if (src.length && dest.length) {
            let opt = new Option(src.text(), src.val());
            opt.ondblclick = bindOptionOnDblclickUnallowed;
            dest.append(opt);
            src.remove();
        }
    },
    bindOptionOnDblclickAllowed = function () {
        let form_share = $(this).parents('.form-share'),
            allowed = $(this),
            unallowed = form_share.find('.form-share-unallowed');

        switchOptionToUnallowed(allowed, unallowed);
    },
    bindOptionOnDblclickUnallowed = function () {
        let form_share = $(this).parents('.form-share'),
            unallowed = $(this),
            allowed = form_share.find('.form-share-allowed');

        switchOptionToAllowed(unallowed, allowed);
    };

$('.btn-share-allowed').on('click', function() {
    let form_share = $(this).parents('.form-share'),
        unallowed = form_share.find('.form-share-unallowed option:selected'),
        allowed = form_share.find('.form-share-allowed');

    switchOptionToAllowed(unallowed, allowed);
});

$('.btn-share-unallowed').on('click', function() {
    let form_share = $(this).parents('.form-share'),
        allowed = form_share.find('.form-share-allowed option:selected'),
        unallowed = form_share.find('.form-share-unallowed');

    switchOptionToUnallowed(allowed, unallowed);
});

$('.form-share-allowed option[share="unallowed"]').each(function() {
    $(this).remove();
});

$('.form-share-unallowed option[share="allowed"]').each(function() {
    $(this).remove();
});

$('.form-share-allowed option').on('dblclick', bindOptionOnDblclickAllowed);

$('.form-share-unallowed option').on('dblclick', bindOptionOnDblclickUnallowed);

$('.btn-share-submit').on('click', function(){
    let form_share = $(this).parents('.form-share'),
        allowed = form_share.find('.form-share-allowed option'),
        unallowed = form_share.find('.form-share-unallowed option');

    allowed.prop('selected', true);
    unallowed.prop('selected', true);
});
