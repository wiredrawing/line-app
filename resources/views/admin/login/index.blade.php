<form action="{{ route("admin.login.authenticate") }}" method="post">
  <input type="text" name="email" value="">

  <input type="password" name="password" value="">


  <input type="submit" value="ログイン">
  @csrf
</form>


