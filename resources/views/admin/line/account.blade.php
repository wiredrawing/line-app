

@foreach ($line_accounts as $key => $value)

<p>{{print_r($value->toArray())}}</p>

@endforeach
