@include("admin.common.header")




<table class="table">
  <thead class="table-light">
    <tr>
      <th>メッセージID</th>
      <th>予約ID</th>
      <th>メッセージ内容</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($reserve->line_messages as $index => $message)
    <tr>
      <td>{{$message->id}}</td>
      <td>{{$message->line_reserve_id}}</td>
      <td>{{$message->text}}</td>
    </tr>
    @endforeach
  </tbody>


</table>
@include("admin.common.footer")
