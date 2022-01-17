Vue.component(
    'ReceiptsContestReceiptForm',
    require('./components/pages/Form.vue').default,
);

let receipts = require('./package/receipts_contest_receipts');
receipts.init();
