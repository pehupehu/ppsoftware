
$('#tabTransaction').on('click', '.transaction-row', function () {
    editTransaction($(this));
});

$('#navbarTransactionAction').on('click', '.transaction-new', function () {
    newTransaction($(this).data('account'), $(this).data('type'));
});

function runActiveTab() {
    current_tab = $('.tab-pane.active');
    runTab(current_tab, current_tab.data('account'), current_tab.data('year'));
}

function runTab(node, account, year) {
    let url = Routing.generate('financial_transaction_account_year', {'account_id': account, 'year': year});
    $.get(url)
        .done(function (data) {
            node.append(data);
    });
}

function newTransaction(account, type) {
    let url, title, options = {};

    if (type === 'credit' || type === 'debit') {
        url = Routing.generate('financial_transaction_new', {'account_id': account, 'type': type});
        title = Translator.trans('financial.transaction.title.new');
    } else if (type === 'transfer') {
        url = Routing.generate('financial_transfer_new', {'account_id': account});
        title = Translator.trans('financial.transfer.title.new');
    } else {
        return;
    }

    options.theme = 'dark';
    options.buttons = {
        0: {
            text: Translator.trans('generic.dialog.submit'),
            class: 'btn btn-primary px-3',
            success: true,
            callback_submit: function (data) {
                PPbox.close('transaction');
                refreshTransaction(null, data);
            }
        },
        1: {
            text: Translator.trans('generic.dialog.close'),
            class: 'btn btn-outline-dark px-3',
        }
    };

    PPbox.form('transaction', title, url, null, options);
}

function editTransaction(node) {
    let id = node.data('id'),
        type = node.data('type'),
        transfer = node.data('transfer'),
        url, title, options = {};

    if (transfer) {
        url = Routing.generate('financial_transfer_edit', {'id': transfer});
        title = Translator.trans('financial.transfer.title.edit');
    } else if (type === 'credit' || type === 'debit') {
        url = Routing.generate('financial_transaction_edit', {'id': id});
        title = Translator.trans('financial.transaction.title.edit');
    } else {
        return;
    }

    $('.transaction-row').removeClass('transaction-edit');
    node.addClass('transaction-edit');

    options.theme = 'dark';
    options.buttons = {
        0: {
            text: Translator.trans('generic.dialog.submit'),
            class: 'btn btn-primary px-3',
            success: true,
            callback_submit: function (data) {
                PPbox.close('transaction');
                refreshTransaction(node, data);
            }
        },
        1: {
            text: Translator.trans('generic.dialog.close'),
            class: 'btn btn-outline-dark px-3',
        }
    };

    PPbox.form('transaction', title, url, null, options);

    $('#ppboxtransaction').on('dialogclose', function( event, ui ) {
        node.removeClass('transaction-edit');
    });
}

function refreshTransaction(node, data) {
    let transaction = data.transaction,
        year = parseInt(transaction.year),
        yearsday = parseInt(transaction.yearsday),
        tab = $('#tab-' + year);

    if (node === null) {
        tab.append(data.template);
    } else {
        if (node.data('year') === year && node.data('yearsday') === yearsday) {
            node.replaceWith(data.template);
        } else {
            node.replaceWith(data.template);
        }
    }
}

runActiveTab();