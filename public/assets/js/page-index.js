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

    run: function() {
        const self = this;
        $(document).ready(function() {
            $('form#train-route-finder').on('submit', function(e) {
                e.preventDefault();
                self.form = $(this);

                if(self.validateFields()) {
                    self.sendRequest();
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

        return !hasErrors;
    },

    sendRequest: function () {
        const self = this;
        $.ajax({
            type: 'POST',
            url: '/',
            data: self.form.serialize(),
            dataType: 'json',
            beforeSend: function() {
                self.toggleFormDisableAndAnimate();
            }
        }).done(function(data, textStatus, request) {
            const error = request.getResponseHeader('X-Error-Text');
            if(error) {
                self.showModal(error, false);
            } else {
                self.showModal(self.formatRouteToHtml(data.route));
            }
        }).fail(function() {
            self.showModal('Произошла непредвиденная ошибка', false);
        }).always(function() {
            self.toggleFormDisableAndAnimate();
        });
    },

    toggleFormDisableAndAnimate: function() {
        const fieldset = this.form.find('fieldset'),
            findIcon = this.form.find('span.find-icon');

        if(fieldset.attr('disabled')) {
            fieldset.removeAttr('disabled');
        } else {
            fieldset.attr('disabled', 'disabled');
        }

        findIcon.toggleClass('rotation');
    },

    formatRouteToHtml: function (stationsList) {
        let route = '';
        for(const i in stationsList) {
            let station = stationsList[i];
            route += '<div>'
                + 'Станция <span style="font-weight: bold; text-decoration: underline;">' + station.stop + '</span>: '
                + station.arrival_time
                + (station.arrival_time && station.departure_time ? ' - ' : '')
                + station.departure_time
                + (station.stop_time ? ' (' + station.stop_time + ' мин)' : '')
                + '</div>';
        }

        return route;
    },

    showModal: function(bodyHtml, isSuccess = true) {
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
        modalBody.html(bodyHtml);

        modal.modal();
    }
}).run();