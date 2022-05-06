<form action="{{ route("admin.password.postRenew") }}" method="post">
  <input type="text" name="email" value="">

  <input type="submit" value="パスワード再発行URLの送信">
  @csrf
</form>


