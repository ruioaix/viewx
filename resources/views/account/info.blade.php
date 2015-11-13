@extends('index')

@section('title', 'XQ Accounts Info')
@endsection

@section('content')
<div id="accounts">
  <input class="search" placeholder="Search" />

  <table  class="table table-striped table-hover">
    <!-- IMPORTANT, class="list" have to be at tbody -->
    <thead>
      <tr>
        <th> <button class="sort" data-sort="id"> Id </button> </th>
        <th> <button class="sort" data-sort="username"> Username </button> </th>
        <th> <button class="sort" data-sort="success"> Success </button> </th>
        <th> <button class="sort" data-sort="cookies"> CookiesErr </button> </th>
        <th> <button class="sort" data-sort="rf"> ReturnFalse </button> </th>
        <th> <button class="sort" data-sort="other"> OtherErr </button> </th>
      </tr>
    </thead>
    <tbody class="list">
    @foreach ($res as $key => $account) 
      <tr>
        <td class="id">  {{ $key }} </td>
        <td class="username"><a href="{{ action('AccountController@info', [$account[0]]) }}">{{ $account[0] }}</a></td>
        <td class="success">{{ $account[1] }}</td>
        <td class="cookies">{{ $account[2] }}</td>
        <td class="rf">{{ $account[3] }}</td>
        <td class="other">{{ $account[4] }}</td>
      </tr>
    @endforeach
    </tbody>
  </table>

</div>

<script type="text/javascript">
$(document).ready(function() {
    var options = {
        valueNames: [ 'username', 'success', 'cookies', 'rf', 'other' ],
        page: 1000
    };

    var userList = new List('accounts', options);
});
</script>

@endsection
