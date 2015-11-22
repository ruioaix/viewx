@extends('index')

@section('title', 'XQ Accounts Info')
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/magnific-popup.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/jquery.magnific-popup.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    var options = {
        valueNames: ['id', 'username', 'lsuccess', 'cookies', 'rf', 'other', 'interval', 'alive' ],
        page: 1000
    };
    var userList = new List('accounts', options);
    $('.account-ajax-popup').magnificPopup({
        type: 'ajax',
        showCloseBtn: false,
    });
});
</script>
@endsection

@section('content')
<div id="accounts">
  <input class="search form-control" placeholder="Search" />

  <table  class="table table-striped table-hover">
    <!-- IMPORTANT, class="list" have to be at tbody -->
    <thead>
      <tr>
        <th> <button class="btn btn-default sort" data-sort="id"> Id </button> </th>
        <th> <button class="btn btn-default sort" data-sort="username"> Username </button> </th>
        <th> <button class="btn btn-default sort" data-sort="lsuccess"> Success </button> </th>
        <th> <button class="btn btn-default sort" data-sort="cookies"> CookiesErr </button> </th>
        <th> <button class="btn btn-default sort" data-sort="rf"> ReturnFalse </button> </th>
        <th> <button class="btn btn-default sort" data-sort="other"> OtherErr </button> </th>
        <th> <button class="btn btn-default sort" data-sort="interval"> ReuseInterval</button> </th>
        <th> <button class="btn btn-default sort" data-sort="alive"> AliveDuration</button> </th>
      </tr>
    </thead>
    <tbody class="list">
    @foreach ($res as $key => $account) 
    @if (isset($aliving[$account[0]]))
      <tr class="success">
    @else
      <tr>
    @endif
        <td class="id">  {{ $key }} </td>
        <td class="username"><a class="account-ajax-popup" href="{{ action('AccountController@infoe', [str_replace('.', '_', $account[0])]) }}">{{ $account[0] }}</a></td>
        <td class="lsuccess">{{ $account[1] }}</td>
        <td class="cookies">{{ $account[2] }}</td>
        <td class="rf">{{ $account[3] }}</td>
        <td class="other">{{ $account[4] }}</td>
        <td class="interval"><a class="account-ajax-popup" href="{{ action('AccountController@infoi', [$account[5].'-'.str_replace('.', '_', $account[0])]) }}">{{ $account[5] }}</a></td>
        <td class="alive"><a class="account-ajax-popup" href="{{ action('AccountController@infoa', [$account[6].'-'.str_replace('.', '_', $account[0])]) }}">{{ $account[6] }}</a></td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
@endsection
