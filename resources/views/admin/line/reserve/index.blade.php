@include("admin.common.header")
<table class="table">
  <thead class="table-light">
    <tr>
      <th>予約ID</th>
      <th>LINEチャンネル</th>
      <th>送信状態</th>
      <th>メッセージ件数</th>
      <th>詳細</th>
      <th>配信日/作成日</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($reserves as $index => $reserve)
    <tr>
      <td>{{$reserve->id}}</td>
      <td>{{$reserve->line_account_id}}</td>
      <td>{{$reserve->is_sent}}</td>
      <td>{{$reserve->line_messages->count()}}</td>
      <td><a href='{{route("admin.line.reserve.detail", [
        "line_reserve_id" => $reserve->id,
      ])}}'class="btn btn-primary">詳細</a></td>
      <td>
        <small>{{$reserve->delivery_datetime->format("Y-m-d H:i:s")}}</small><br>
        <small>{{$reserve->created_at->format("Y-m-d H:i:s")}}</small>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@include("admin.common.footer")
