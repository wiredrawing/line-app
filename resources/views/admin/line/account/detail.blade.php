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
    @foreach ($line_account->line_members as $index => $member)
    <tr>
      <td>{{$member->id}}</td>
      {{-- <td>{{$member->access_token}}</td> --}}
      {{-- <td>{{$member->id_token}}</td> --}}
      <td>{{$member->token_type}}</td>
      <td>{{$member->email}}</td>
      <td><img width="50px" src="{{$member->picture}}"></td>
      <td>{{$member->name}}</td>
      <td>{{$member->sub}}</td>
      <td>{{$member->aud}}</td>
      <td>
        {{$member->created_at->format("Y-m-d H:i:s")}}
      </td>
      {{-- <td>{{$member->api_token}}</td> --}}
      <td><a href='{{route("admin.line.member.detail", [
        "line_member_id" => $member->id,
      ])}}'>詳細</a></td>
    </tr>
    @endforeach
  </tbody>
</table>

@include("admin.common.footer")
