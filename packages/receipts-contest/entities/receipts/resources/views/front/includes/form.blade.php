<div id="reg-check" class="reg-check">
    <div class="reg-check__wr">
        <div class="reg-check__header">
            <span>Зарегистрировать чек</span>
        </div>
        <form id="form-check-file" action="{{ route('front.receipts-contest.receipts.send') }}" method="post">
            {{ csrf_field() }}
            <div class="reg-check__body">
                <h3>1. Личные данные</h3>
                <div class="reg-check__form">
                    <div class="row">
                        <div class="row-span row-span--6">
                            <input type="text" class="inp-st" placeholder="Имя*"
                                   name="additional_info[name]"
                                   autocomplete="off"
                                   required
                                   data-msg="Заполните поле"
                                   data-rule-minlength="2"
                                   data-msg-minlength="Минимум 2 символа">
                        </div>
                        <div class="row-span row-span--6">
                            <input type="tel" class="inp-st mask" placeholder="Телефон*"
                                   name="additional_info[phone]"
                                   data-mask="+7 (999) 999 99 99"
                                   autocomplete="off"
                                   required
                                   data-msg-required="Заполните поле">
                        </div>
                    </div>

                    <div class="reg-check__form-info">
                        *— поля обязательны для заполнения
                    </div>

                    <div class="fancybox-wr__check">
                        <div class="reg-check__check">
                            <div class="checkbox-span">
                                <input type="checkbox" name="additional_info[condition]" id="hl-condition" required data-msg="Заполните поле">
                                <label for="hl-condition">
                                    Я&nbsp;подтверждаю, что мне исполнилось 18 лет, Я&nbsp;ознакомлен (-а) и&nbsp;согласен (-а) с&nbsp;условиями акции, указанных в&nbsp;<span>&laquo;<a href="{{ asset('files/rules.pdf') }}" target="_blank">Правилах акции</a>&raquo;</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="reg-check__file-check">
                    <h3>2. Загрузите скан-копию или фотографию чека</h3>
                    <div class="reg-check__images">
                        <div class="reg-check__images-h">
                            <a href="#">Как правильно загрузить чек</a>
                        </div>
                        <div class="reg-check__images-open">
                            <img data-src="{{ asset('assets/img/check.png') }}" class="check-images lazy" alt="">
                            <img data-src="{{ asset('assets/img/check-sm.png') }}" class="check-images-sm lazy" alt="">
                        </div>
                    </div>

                    <div class="reg-check__file-btn">
                        <a href="#" class="dm-btn-st"><span>Выберите файл</span></a>
                    </div>
                    <div class="reg-check__file-hide">
                        <div class="reg-check__file-row">
                            <div class="reg-check__file-close"><a href="#"></a></div>
                            <div class="reg-check__file-name">
                                Название файла.jpg
                            </div>
                        </div>
                    </div>
                    <input type="file" id="check-file" name="receipt_image" class="reg-check__file-file">

                </div>
                <div class="form-check__send">
                    <a href="#" class="btn-st"><span>зарегистрировать чек</span></a>
                </div>
            </div>
        </form>
    </div>
    <div class="hl-border"><i></i><i></i><i></i><i></i></div>
</div>
