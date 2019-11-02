@if (! isset($item['fnsReceipt']['receipt']['document']['receipt']['retailPlaceAddress']))
    {!! Form::string('receipt_data[address]', $item['receipt_data']['address'] ?? '', [
        'label' => [
         'title' => 'Адрес',
        ],
        'field' => [
            'autocomplete' => 'off'
        ],
    ]) !!}
@endif
