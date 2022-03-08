@foreach ($line_accounts as $index => $account)

<div>
  <p>line_account_id {{$account->id}}</p>
  <p><a href="{{route("line.login.detail", [
      "line_account_id" => $account->id,
      "application_key" => $account->application_key
    ])}}">LINEログインURLへ</a></p>
</div>

@endforeach
