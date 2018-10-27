
let current_tab;

let runActiveTab = function () {
    current_tab = $('.tab-pane.active');
    runTab(current_tab, current_tab.data('account'), current_tab.data('year'));
};

let runTab = function (node, account, year) {
    console.log('Load ' + year);

    let url = Routing.generate('financial_transaction_account_year', {'account_id': account, 'year': year});
    console.log(url);
    $.get(url)
        .done(function (data) {
            node.append(data);
    });
};

runActiveTab();