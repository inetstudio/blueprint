<div class="hl-winners animation-block">
    <div id="winners" class="hl-winners-ancor"></div>
    <div class="hl-winners__wr">
        <div class="hl-art"></div>
        <div class="hl-winners__head">
            <h2 class="js-block-ancor">
                <div class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0s">ПОБЕДИТЕЛИ</div>
            </h2>
            <div class="hl-winners__search">
                <form id="search-phone" action="{{ route('front.checks-contest.checks.search', ['field' => 'phone', 'type' => 'winner']) }}" method="POST">
                    {{ csrf_field() }}
                    <input type="text" name="query" class="hl-winners__search-inp" placeholder="Введите номер телефона">
                    <input type="submit" class="hl-winners__search-sub">
                    <a href="#" class="hl-winners__search-close"></a>
                </form>
            </div>
        </div>
        <div class="hl-winners__body">
            <ul class="hl-winners__tab">
                <li><a href="#" class="active">Еженедельные</a></li>
                <li><a href="#">Супер приз</a></li>
            </ul>
            <div class="hl-winners__items">
                <div class="hl-winners__item">
                    <div class="hl-winners__scroll">
                        <div class="hl-winners__in">
                            @if ($stages['prizes']['certificate']['totalWinners'] > 0)
                                @foreach ($stages['prizes']['certificate']['stages'] as $stage)
                                    @if (count($stage['winners']) > 0)
                                        <div class="hl-winners__date">
                                            @if (isset($stage['date']['start']) && isset($stage['date']['end']))
                                                <h4>{{ $stage['date']['start'].' - '.$stage['date']['end'] }}</h4>
                                            @endif
                                            @foreach ($stage['winners'] as $winner)
                                                @if (isset($winner['id']) && isset($winner['name']) && isset($winner['phone']))
                                                    <p data-id="{{ $winner['id'] }}">{{ $winner['name'] }} <span>{{ $winner['phone'] }}</span></p>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <p>Победители еще не&nbsp;определены</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="hl-winners__item" style="display: none">
                    <div class="hl-winners__scroll">
                        <div class="hl-winners__in">
                            @if ($stages['prizes']['main']['totalWinners'] > 0)
                                @foreach ($stages['prizes']['main']['stages'] as $stage)
                                    @if (count($stage['winners']) > 0)
                                        <div class="hl-winners__date">
                                            @foreach ($stage['winners'] as $winner)
                                                @if (isset($winner['id']) && isset($winner['name']) && isset($winner['phone']))
                                                    <p data-id="{{ $winner['id'] }}">{{ $winner['name'] }} <span>{{ $winner['phone'] }}</span></p>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p>Победители еще не&nbsp;определены</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hl-border"><i></i><i></i><i></i></div>
    </div>
</div>
