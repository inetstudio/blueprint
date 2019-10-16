{!! Form::open(['url' => (! $item['id']) ? route('back.checks-contest.checks.store') : route('back.checks-contest.checks.update', [$item['id']]), 'id' => 'mainForm', 'enctype' => 'multipart/form-data']) !!}
    @if ($item['id'])
        {{ method_field('PUT') }}
    @endif

    <div class="m-b-lg">

        {!! Form::string('receipt_data[discountSum]', $item->getJSONData('receipt_data', 'discountSum', 0), [
             'label' => [
                 'title' => 'Сумма скидки',
             ],
            'field' => [
                'autocomplete' => 'off'
            ],
         ]) !!}

        {!! Form::string('receipt_data[cityName]', $item->getJSONData('receipt_data', 'cityName', ''), [
             'label' => [
                 'title' => 'Город',
             ],
            'field' => [
                'autocomplete' => 'off'
            ],
         ]) !!}

        {!! Form::string('receipt_data[denyReason]', $item->getJSONData('receipt_data', 'denyReason', ''), [
             'label' => [
                 'title' => 'Причина отмены',
             ],
            'field' => [
                'autocomplete' => 'off'
            ],
         ]) !!}

        @if ($item->hasJSONData('receipt_data', 'receipt.user'))
            <p><strong>Юридическое лицо: </strong>{{ $item->getJSONData('receipt_data', 'receipt.user', '') }}</p>
        @endif

        @if ($item->hasJSONData('receipt_data', 'receipt.userInn'))
            <p><strong>ИНН: </strong>{{ $item->getJSONData('receipt_data', 'receipt.userInn', '') }}</p>
        @endif

        @if ($item->hasJSONData('receipt_data', 'receipt.retailPlace'))
            <p><strong>Место покупки: </strong>{{ $item->getJSONData('receipt_data', 'receipt.retailPlace', '') }}</p>
        @endif

        @if ($item->hasJSONData('receipt_data', 'receipt.retailPlaceAddress'))
            <p><strong>Адрес: </strong>{{ $item->getJSONData('receipt_data', 'receipt.retailPlaceAddress', '') }}</p>
        @endif
    </div>

    @if ($item->hasJSONData('receipt_data', 'receipt.dateTime'))
        @php
            $receiptDate = \Illuminate\Support\Carbon::parse($item->getJSONData('receipt_data', 'receipt.dateTime'));
        @endphp
        <div class="m-b-lg">
            <p><strong>Дата покупки: </strong>{{ $receiptDate->format('d.m.Y') }}</p>
        </div>
    @endif

    <div>
        <products-list
            v-bind:products-prop="{{ json_encode($item->products->toArray()) }}"
            v-bind:fns-receipt-id-prop="{{ $item['fns_receipt_id'] }}"
            v-bind:receipt-id-prop="{{ $item['id'] }}"
        />
    </div>

@foreach ($item->prizes as $prize)
    <input name="prizes[{{ $prize->id }}][date_start]" type="hidden" value="{{ ($prize->pivot->date_start) ? (new \Carbon\Carbon($prize->pivot->date_start))->format('d.m.Y') : '' }}">
    <input name="prizes[{{ $prize->id }}][date_end]" type="hidden" value="{{ ($prize->pivot->date_end) ? (new \Carbon\Carbon($prize->pivot->date_end))->format('d.m.Y') : '' }}">
    <input name="prizes[{{ $prize->id }}][confirmed]" type="hidden" value="{{ $prize->pivot->confirmed }}">
@endforeach
{!! Form::close()!!}
