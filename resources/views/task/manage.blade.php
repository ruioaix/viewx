@extends('index')

@section('title', 'Task manage')
@endsection

@section('script')
@endsection

@section('content')
<div class="col-xs-6">
<form class="form-horizontal" action="{{ action('TaskController@manageupdate') }}" method="POST">
  <div class="form-group">
    <label for="infoprob" class="col-sm-6 control-label">info Prob</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" name="infoprob" value="{{ $info }}">
    </div>
  </div>
  <div class="form-group">
    <label for="gainprob" class="col-sm-6 control-label">gain prob</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="gainprob" value="{{ $gain }}">
    </div>
  </div>
  <div class="form-group">
    <label for="adjustprob" class="col-sm-6 control-label">adjust prob</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="adjustprob" value="{{ $adjust_common }}">
    </div>
  </div>
  <div class="form-group">
    <label for="adjustlinkprob" class="col-sm-6 control-label">adjust list prob</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="adjustlinkprob" value="{{ $adjust_list }}">
    </div>
  </div>
  <div class="form-group">
    <label for="adjustcmplprob" class="col-sm-6 control-label">adjust complete prob</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="adjustcmplprob" value="{{ $adjust_complete }}">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-6 col-sm-10">
      <button type="submit" class="btn btn-default">Update</button>
    </div>
  </div>
</form>
</div>
@endsection
