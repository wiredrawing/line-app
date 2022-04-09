<table>


  @foreach ($line_members as $index => $member)


  <tr>
    <td>{{$member->id}}</td>
    <td>{{$member->token_type}}</td>
    <td>{{$member->email}}</td>
    <td><img width="50px" src="{{$member->picture}}"></td>
    <td>{{$member->name}}</td>
    <td><a href='{{route("admin.line.member.detail", [
      "line_member_id" => $member->id,
    ])}}'>詳細</a></td>
    <td>{{$member->line_account->id}}</td>
    <td>{{$member->line_account->application_key}}</td>
  </tr>

  @endforeach

</table>
