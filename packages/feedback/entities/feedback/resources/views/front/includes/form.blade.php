<div id="contacts" class="reg-check">
    <div class="contacts-wr">
        <div class="fancybox-wr__header">Обратная связь</div>
        <div class="fancybox-wr__cont-comment">
            Если у&nbsp;Вас возникли вопросы или&nbsp;предложения, пожалуйста, свяжитесь <br>с&nbsp;нами с&nbsp;помощью формы
        </div>
        <form id="form-contacts" action="{{ route('front.feedback.send') }}" method="POST">
            {{ csrf_field() }}
            <div class="fancybox-wr__inputs">
                @guest
                    <div class="row">
                        <div class="row-span row-span--6">
                            <input type="text" class="inp-st" placeholder="Имя" name="name" required
                                   data-msg="Заполните поле"
                                   data-rule-minlength="2"
                                   data-msg-minlength="Минимум 2 символа"
                                   data-rule-maxlength="20"
                                   data-msg-maxlength="Максимально 20 символов">
                        </div>
                        <div class="row-span row-span--6">
                            <input type="text" class="inp-st" placeholder="E-mail" name="email" required
                                   data-msg="Заполните поле" data-rule-minlength="2" data-msg-minlength="Минимум 2 символа"
                                   data-rule-email="true" data-msg-email="Неверный e-mail">
                        </div>
                    </div>
                @endguest
                <div class="row">
                    <div class="row-span">
                            <textarea name="message" id="" cols="30" rows="3" placeholder="Сообщение" class="textarea-style"
                                      required data-msg="Заполните поле" data-rule-minlength="10"
                                      data-msg-minlength="Минимум 10 символов"></textarea>
                    </div>
                </div>
            </div>

            @guest
                {!! no_captcha('v2')->display() !!}
            @endguest

            <div class="nv-btn-center">
                <a href="#" class="btn-st form-contacts-send"><span>отправить сообщение</span></a>
            </div>
        </form>
    </div>
    <div class="hl-border"><i></i><i></i><i></i><i></i></div>
</div>
