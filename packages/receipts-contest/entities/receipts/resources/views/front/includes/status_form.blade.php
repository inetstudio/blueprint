<div id="check-status" class="reg-check reg-check--check-status">
    <div class="contacts-wr">
        <div class="fancybox-wr__header">Узнать статус чека</div>
        <div class="fancybox-wr__cont-comment">
            <span>Введите номер телефона,</span> чтобы узнать статус вашего чека
        </div>
        <form id="form-check-status" action="{{ route('front.receipts-contest.receipts.search', ['field' => 'phone', 'type' => 'status']) }}" method="POST">
            {{ csrf_field() }}
            <div class="fancybox-wr__inputs">
                <div class="row">
                    <div class="row-span row-span--6">
                        <input type="tel" class="inp-st mask" placeholder="Телефон*"
                               name="query"
                               data-mask="+7 (999) 999 99 99"
                               autocomplete="off"
                               required
                               data-msg-required="Заполните поле">
                    </div>
                    <div class="row-span row-span--6 row-span--btn">
                        <a href="#" class="btn-st check-status-btn"><span>узнать статус</span></a>
                    </div>
                </div>
            </div>
        </form>
        <div class="check-status-result">
            <h3>статус чека</h3>
            <table>
            </table>
        </div>
    </div>
    <div class="hl-border"><i></i><i></i><i></i><i></i></div>
</div>
