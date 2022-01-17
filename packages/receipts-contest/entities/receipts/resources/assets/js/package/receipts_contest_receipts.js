let receipts = {};

receipts.init = function () {
  $(document).ready(function () {
    let $moderationTable = $('#receipts_contest_receipts table');

    $moderationTable.off('click', 'a.receipt-moderate');
    $moderationTable.on('click', 'a.receipt-moderate', function (event) {
      event.preventDefault();

      let button = $(this),
          url = button.attr('href'),
          reason = button.attr('data-reason');

      $('#receipts_contest_receipts .ibox-content').toggleClass('sk-loading');

      let data = {
        id: button.data('id'),
        status_id: button.data('status_id'),
      };

      if (typeof reason !== typeof undefined && reason !== false) {
        swal.fire({
          title: 'Введите причину изменения статуса',
          input: 'select',
          inputOptions: {
            'Отсутствует фото чека': 'Отсутствует фото чека',
            'Отсутствуют данные/часть данных для валидации чека в ФНС': 'Отсутствуют данные/часть данных для валидации чека в ФНС',
            'Не прошел валидацию в ФНС': 'Не прошел валидацию в ФНС',
            'Отсутствуют акционные продукты': 'Отсутствуют акционные продукты',
            'Дата покупки не соответствует срокам проведения акции': 'Дата покупки не соответствует срокам проведения акции',
            'Отсутствует адрес магазина': 'Отсутствует адрес магазина',
            'Отсутствует QR-код': 'Отсутствует QR-код',
            'Присутствует только QR-код': 'Присутствует только QR-код',
            'Чек невозможно прочитать': 'Чек невозможно прочитать',
            'Чек не из Магнита': 'Чек не из Магнита',
            'Дубликат': 'Дубликат',
            'Hе хватает одного акционного товара': 'Hе хватает одного акционного товара',
            'Магнит Аптека': 'Магнит Аптека',
          },
          inputPlaceholder: 'Причина',
          inputAttributes: {
            autocapitalize: 'off'
          },
          showCancelButton: true,
          showLoaderOnConfirm: true,
          preConfirm: (inputReason) => {
            if (inputReason === '') {
              return {
                dismiss: 'cancel'
              };
            }

            data.receipt_data = {
              statusReason: inputReason
            };

            return moderate(url, data);
          },
          allowOutsideClick: () => ! swal.isLoading()
        }).then((result) => {
          processModerateResponse(result);
        });
      } else {
        new Promise(function(resolve, reject) {
          let result = moderate(url, data);

          resolve(result);
        }).then((result) => {
          processModerateResponse(result);
        });
      }
    });
  });

  function moderate(url, data) {
    return axios.post(url, data)
        .then(response => {
          $('#receipts_contest_receipts .ibox-content').toggleClass('sk-loading');

          if (response.status !== 200) {
            throw new Error(response.statusText);
          }

          return response.data;
        })
        .catch(error => {
          $('#receipts_contest_receipts .ibox-content').toggleClass('sk-loading');

          showError('При модерации произошла ошибка');
        });
  }

  function processModerateResponse(result) {
    result = _.get(result, 'value', result);

    if (result.dismiss === 'cancel') {
      $('#receipts_contest_receipts .ibox-content').toggleClass('sk-loading');

      return;
    }

    if (result.success === true) {
      result.items.forEach(function (item) {
        let row = $('#receipt_row_'+item.id);

        for (let column in item) {
          if (item.hasOwnProperty(column)) {
            row.find('.receipt-'+column).html(item[column]);
          }
        }
      });

      swal.fire({
        title: 'Статус изменен',
        type: 'success'
      });
    } else {
      showError('При модерации произошла ошибка');
    }
  }

  function showError(text) {
    swal.fire({
      title: 'Ошибка',
      text: text,
      type: 'error',
    });
  }
}

module.exports = receipts;
