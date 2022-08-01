@if (isset($line_accounts) && $line_accounts->count() > 0)
  @foreach ($line_accounts as $index => $account)
    <div id="all-line-accounts-box">
      <p>line_account_id {{$account->id}}</p>
      <p><a href="{{route("line.login.detail", [
      "line_account_id" => $account->id,
      "application_key" => $account->application_key
    ])}}">LINEログインURLへ</a></p>
    </div>
  @endforeach
@else
  <div id="all-line-accounts-box">
    <p>有効なLineログイン可能なアカウントがありません.</p>
  </div>
@endif
