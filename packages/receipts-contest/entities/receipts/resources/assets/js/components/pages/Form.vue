<template>
  <div id="receipts_contest_receipt_form_modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal inmodal fade" ref="modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span>
          </button>
          <h1 class="modal-title">Содержимое чека</h1>
        </div>
        <div class="modal-body">
          <div class="ibox-content">
            <div class="sk-spinner sk-spinner-double-bounce">
              <div class="sk-double-bounce1"></div>
              <div class="sk-double-bounce2"></div>
            </div>
            <div class="content">
              <div class="row m-b-md">
                <div class="col-lg-12">
                  <span :class="'btn-' + _.get(receipt, 'model.status.color_class', '')" class="btn btn-sm float-right">{{ _.get(receipt, 'model.status.name', '') }}</span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <div class="ibox border-bottom collapsed">
                    <div class="ibox-title">
                      <h5>Данные чека</h5>
                      <div class="ibox-tools">
                        <a class="collapse-link">
                          <i class="fa fa-chevron-up"></i>
                        </a>
                      </div>
                    </div>
                    <div class="ibox-content">
                      <pre class="json-data">{{ _.get(receipt, 'model.receipt_data', '') }}</pre>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <div class="ibox">
                    <div class="ibox-title">
                      <h5>Чек</h5>
                      <div class="ibox-tools">
                        <a class="collapse-link">
                          <i class="fa fa-chevron-up"></i>
                        </a>
                      </div>
                    </div>
                    <div class="ibox-content">
                      <div class="row" v-if="_.get(receipt, 'model.fns_receipt', null) !== null">
                        <div class="col-lg-12">
                          <div class="m-b-lg">
                            <p v-if="_.has(receipt, 'model.fns_receipt.data.content.user')"><strong>Юридическое лицо: </strong>{{ _.get(receipt, 'model.fns_receipt.data.content.user') }}</p>
                            <p v-if="_.has(receipt, 'model.fns_receipt.data.content.userInn')"><strong>ИНН: </strong>{{ _.get(receipt, 'model.fns_receipt.data.content.userInn') }}</p>
                            <p v-if="_.has(receipt, 'model.fns_receipt.data.content.retailPlace')"><strong>Место покупки: </strong>{{ _.get(receipt, 'model.fns_receipt.data.content.retailPlace') }}</p>
                            <p v-if="_.has(receipt, 'model.fns_receipt.data.content.retailPlaceAddress')"><strong>Адрес: </strong>{{ _.get(receipt, 'model.fns_receipt.data.content.retailPlaceAddress') }}</p>
                          </div>

                          <div class="m-b-lg" v-if="_.has(receipt, 'model.fns_receipt.data.content.dateTime')">
                            <p><strong>Дата покупки: </strong>{{ formatDate(_.get(receipt, 'model.fns_receipt.data.content.dateTime'), 'd.m.Y') }}</p>
                          </div>
                        </div>
                      </div>
                      <div class="row" v-if="_.get(receipt, 'model.fns_receipt', null) === null">
                        <div class="col-lg-12">
                          <div class="ibox-content" style="padding: 15px 20px 20px 0px;border-width: 0px 0;">
                            <base-input-text
                                label="Место покупки"
                                name="retailPlaceAddress"
                                v-bind:value="_.get(receipt, 'model.additional_info.retailPlaceAddress', '')"
                                v-on:update:value="_.set(receipt, 'model.additional_info.retailPlaceAddress', $event)"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <div class="ibox border-bottom collapsed">
                    <div class="ibox-title">
                      <h5>Продукты</h5>
                      <div class="ibox-tools">
                        <a class="collapse-link">
                          <i class="fa fa-chevron-up"></i>
                        </a>
                      </div>
                    </div>
                    <div class="ibox-content" style="display: none;">
                      <div>
                        <receipts-contest-products-list
                            v-bind:receipt-id-prop="_.get(receipt, 'model.id', '')"
                            v-bind:products-prop="_.get(receipt, 'model.products', [])"
                            v-on:update:products="updateProducts($event)"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-12">
                  <div class="ibox border-bottom collapsed">
                    <div class="ibox-title">
                      <h5>Призы</h5>
                      <div class="ibox-tools">
                        <a class="collapse-link">
                          <i class="fa fa-chevron-up"></i>
                        </a>
                      </div>
                    </div>
                    <div class="ibox-content" style="display: none;">
                      <div>
                        <receipts-contest-prizes-list
                            v-bind:prizes-prop="_.get(receipt, 'model.prizes', [])"
                            v-on:update:prizes="updatePrizes($event)"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="save btn btn-primary" v-on:click.prevent.stop="save">Сохранить</button>
          <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ReceiptsContestReceiptForm',
  data() {
    return {
      receipt: {}
    };
  },
  computed: {
    _() {
      return _;
    },
    storeReceipt() {
      return window.Admin.vue.stores['receipts_contest_receipts'].state.receipt;
    }
  },
  watch: {
    'receipt.model': {
      handler: function(newValue, oldValue) {
        let component = this;

        component.receipt.hash = window.hash(newValue);
      },
      deep: true
    },
    storeReceipt: {
      handler: function(newValue, oldValue) {
        let component = this;

        component.receipt = JSON.parse(JSON.stringify(newValue));
      },
      deep: true
    }
  },
  methods: {
    updatePrizes(data) {
      let component = this;

      if (data) {
        component.receipt.model.prizes = _.map(data.prizes, function (prize) {
          if (prize.hasOwnProperty('model')) {
            return prize.model;
          }

          return prize;
        });
      }
    },
    updateProducts(data) {
      let component = this;

      if (data) {
        component.receipt.model.products = _.map(data.products, function (product) {
          if (product.hasOwnProperty('model')) {
            return product.model;
          }

          return product;
        });
      }
    },
    save() {
      let component = this;

      let container = $(component.$refs.modal).find('.ibox-content');
      container.addClass('sk-loading');

      let url = (component.receipt.model.id)
          ? route('back.receipts-contest.receipts.update', {receipt: component.receipt.model.id})
          : route('back.receipts-contest.receipts.store');

      let data = component.receipt.model;
      if (component.receipt.model.id) {
        data._method = 'PUT';
      }

      axios.post(url, data)
          .then(response => {
            container.removeClass('sk-loading');

            if (response.status !== 200) {
              throw new Error(response.statusText);
            }

            let item = response.data;

            let row = $('#receipt_row_'+item.id);

            for (let column in item){
              if (item.hasOwnProperty(column)) {
                row.find('.receipt-'+column).html(item[column]);
              }
            }

            $(component.$refs.modal).modal('hide');
          })
          .catch(error => {
            container.removeClass('sk-loading');

            swal.fire({
              title: 'Ошибка',
              text: 'При сохранении произошла ошибка',
              type: 'error'
            });
          });
    },
    formatDate(dateTime, toFormat) {
      dateTime = new Date(dateTime * 1000);

      return dateTime ? flatpickr.formatDate(dateTime, toFormat) : null;
    }
  }
};
</script>

<style scoped>
</style>
