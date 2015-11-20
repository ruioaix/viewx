<form class="form-inline">
  <div class="form-group">
     <div class="input-group">
      <div class="input-group-addon">Period: from</div>
      <input type="text" class="form-control" id="fromhour" placeholder="0">
      <div class="input-group-addon">hours ago</div>
     </div>
     <div class="input-group">
      <div class="input-group-addon">to</div>
      <input type="text" class="form-control" id="tohour" placeholder="0">
      <div class="input-group-addon">hours ago</div>
     </div>
  </div>
  <button id='viewbutton' type="button" class="btn btn-primary" onclick="loadchart()">VIEW</button>
  <span class="" id="msg"> </span>
</form>
