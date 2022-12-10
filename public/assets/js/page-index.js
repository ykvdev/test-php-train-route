$( document ).ready(function() {
    $('form#train-route-finder').on('submit', function(e) {
        e.preventDefault();

        let form = $(this),
            fieldset = form.find('fieldset'),
            convertIcon = form.find('span.convert-icon'),
            fromCurrency = form.find('#from-currency').val(),
            toCurrency = form.find('#to-currency').val(),
            fromAmountField = form.find('#from-amount'),
            fromAmount = fromAmountField.val(),
            toAmountField = form.find('#to-amount');

        normalizeFromAmount();
        if(fromCurrency === toCurrency) {
            toAmountField.val(fromAmount);
        } else {
            disableFormAndStartAnimate();
            convertAmount();
        }

        function normalizeFromAmount() {
            fromAmount = fromAmount.replace(',', '.');
            fromAmount = fromAmount.replace(/[^\d\.]/g, '');
            fromAmount = parseFloat(fromAmount);
            fromAmount = fromAmount && fromAmount > 0 ? fromAmount : 1;
            fromAmountField.val(fromAmount.toString());
        }

        function convertAmount() {
            $.getJSON('/data/rates.json', function(rates) {
                let convertedAmount = (fromAmount * rates[fromCurrency][toCurrency]).toFixed(2);
                toAmountField.val(convertedAmount.toString());

                enableFormAndStopAnimate();
            });
        }

        function disableFormAndStartAnimate() {
            fieldset.attr('disabled', 'disabled');
            convertIcon.addClass('rotation');
        }

        function enableFormAndStopAnimate() {
            fieldset.removeAttr('disabled');
            convertIcon.removeClass('rotation');
        }
    });
});