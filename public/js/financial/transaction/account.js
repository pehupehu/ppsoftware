
let current_tab;

let runActiveTab = function () {
    current_tab = $('.tab-pane.active');
    runTab(current_tab, current_tab.data('account'), current_tab.data('year'));
};

let runTab = function (node, account, year) {
    let url = Routing.generate('financial_transaction_account_year', {'account_id': account, 'year': year});
    $.get(url)
        .done(function (data) {
            node.append(data);
    });
};

$('#tab-transaction').on('click', '.transaction-row', function () {
     editTransaction($(this));
});

let editTransaction = function (node) {
    let id = node.data('id'),
        url = Routing.generate('financial_transaction_edit', {'id': id}),
        options = {};

    $('.transaction-row').removeClass('transaction-edit border-dark');
    node.addClass('transaction-edit border-dark');

    options.theme = 'dark';
    options.width = '95%';
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

    PPbox.form('transaction', Translator.trans('financial.transaction.title.edit'), url, null, options);

    $('#ppboxtransaction').on('dialogclose', function( event, ui ) {
        node.removeClass('transaction-edit border-dark');
    });
};

let refreshTransaction = function (node, data) {
    let transaction = data.transaction;
    
    if (node.data('year') === parseInt(transaction.year) && node.data('yearsday') === parseInt(transaction.yearsday)) {
        node.html(data.template);
    } else {
        node.html(data.template);
    }
};

runActiveTab();