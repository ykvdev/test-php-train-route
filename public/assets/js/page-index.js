({
    form: null,

    fields: {
        trainNumber: {
            selector: '#train-number',
            regExpValidator: /^\d{3}[А-Я]$/u,
        },
        departureDate: {
            selector: '#departure-date',
            regExpValidator: /^\d{4}-\d{2}-\d{2}$/,
        },
        departureStation: {
            selector: '#departure-station',
            regExpValidator: /^[а-яё\-\s.,]{3,}$/ui,
        },
        arrivalStation: {
            selector: '#arrival-station',
            regExpValidator: /^[а-яё\-\s.,]{3,}$/ui,
        },
    },

    run: function () {
        const self = this;
        $( document ).ready(function() {
            $('form#train-route-finder').on('submit', function(e) {
                e.preventDefault();
                self.form = $(this);

                if(self.validateFields()) {
                    this.toggleFormDisablingAndAnimation();
                    /*
                    send AJAX request
                    ajax success:
                        this.showModal(response.text, response.isSuccess);
                    ajax error
                        this.showModal('Произошла непредвиденная ошибка, попробуйте еще раз', false);
                    ajax complete
                        this.toggleFormDisablingAndAnimation();
                    */
                }
            });
        });
    },

    validateFields: function() {
        let hasErrors = false;
        for(const i in this.fields) {
            const field = this.form.find(this.fields[i]['selector']),
                val = field.val();

            field.removeClass('is-invalid');
            if(!val || !this.fields[i]['regExpValidator'].test(val)) {
                field.addClass('is-invalid');
                hasErrors = true;
            }
        }

        return hasErrors;
    },

    toggleFormDisablingAndAnimation: function() {
        const fieldset = this.form.find('fieldset'),
            findIcon = this.form.find('span.find-icon');

        if(fieldset.attr('disabled')) {
            fieldset.removeAttr('disabled');
        } else {
            fieldset.attr('disabled', 'disabled');
        }

        findIcon.toggleClass('rotation');
    },

    showModal: function(text, isSuccess = true) {
        const modal = $('#results-modal'),
            modalTitle = modal.find('.modal-header .modal-title'),
            modalBody = modal.find('.modal-body');

        if(isSuccess) {
            modalTitle.text('Маршрут');
            modalBody.removeClass('text-danger');
        } else {
            modalTitle.text('Ошибка');
            modalBody.addClass('text-danger');
        }

        modalBody.text(text);

        modal.modal();
    }
}).run();