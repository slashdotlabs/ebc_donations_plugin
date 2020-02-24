const initPhoneMask = () => {
    //? 254 phone mask
    const phoneMask = $('.phone-mask');
    if (phoneMask.length === 0) return;

    if ($.isFunction($.fn.mask)) {
        phoneMask.mask('254799999999');
    }
};

const processingForm = isProcessing => {
    const button = $('#ebc-donations-form button[type=submit]');
    const text = isProcessing ? 'Processing...' : 'Submit';
    button.text(text);
};

const handleDonations = () => {
    const donationsForm = $("#ebc-donations-form");

    donationsForm.on('submit', event => {
        event.preventDefault();
        const data = {};
        donationsForm.serializeArray()
            .forEach(field => {
                data[field['name']] = field['value'];
            });

        // Show processing
        processingForm(true);

        // Get ipay url
        $.ajax({
            method: 'post',
            url: donations_form_ajax.ajax_url,
            data: {
                _ajax_nonce: donations_form_ajax.nonce,
                action: 'submit_donation',
                data,
            },
            dataType: 'json'
        }).then(res => {
            if (!res['success']) {
                // ?Handle error
                console.log(res);
                processingForm(false);
                return;
            }
            // Redirect to ipay
            window.location.href = res['data']['ipay_url'];
        });
    })
};

jQuery(() => {
    window.$ = jQuery;
    // Phone mask
    initPhoneMask();

    // Form
    handleDonations();
});