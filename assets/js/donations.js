jQuery(() => {
    window.$ = jQuery;

    function prependZero(digit) {
        return digit > 9 ? digit.toString() : `0${digit}`;
    }

    function getFormattedDateString(date = new Date()) {
        const day = prependZero(date.getDate());
        const month = prependZero(date.getMonth() + 1);
        const year = date.getFullYear();
        return `${year}-${month}-${day}`;
    }

    jQuery('#tbl-musyimi-donations').DataTable({
        scrollX: true,
        "aaSorting": [],
        columnDefs: [
            {targets: [0, 3, 4, 6], orderable: false},
            {targets: [2], class: "text-right"}
        ]
    });

    jQuery('#tbl-online-giving-transactions').DataTable({
        scrollX: true,
        "aaSorting": [],
        columnDefs: [
            {targets: [0, 3, 4, 6], orderable: false},
            {targets: [2], class: "text-right"}
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'csv',
                text: 'Export to CSV',
                filename: () => {
                    const today = getFormattedDateString();
                    return `Online Giving Transactions (${today})`;
                },
                exportOptions: {
                    format: {
                        body: (data, row, column, node) => {
                            if (column === 1) {
                                return `No. ${data}`;
                            }

                            // strip html tags and multi lines
                            data = data.replace(/(<([^>]+)>)/ig, "");
                            return data.replace(/[\r\n]+/gm, "");
                        }
                    }
                }
            }
        ]
    });
});