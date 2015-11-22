$(document).ready(function() {

    @if (count($proxy_bl))
        document.getElementById("p_fix_container").innerHTML = "<h3> {{ count($proxy_bl) }} proxy records shourld be fixed:  </h3> @foreach ($proxy_bl as $values) <li><strong>{{ $values }}</strong></li> @endforeach ";
    @else
        document.getElementById("p_fix_container").innerHTML = "<h3> In proxy, nothing need to fix. </h3>";
    @endif
    @if (count($proxy_er))
        document.getElementById("p_error").innerHTML = "<h3> error list </h3> @foreach ($proxy_er as $values) <li>{{ $values }}</li> @endforeach ";
    @else
        document.getElementById("p_error").innerHTML = "";
    @endif

    @if (count($account_bl)) 
        document.getElementById("a_fix_container").innerHTML = "<h3> {{ count($account_bl) }} account records shourld be fixed:  </h3> @foreach ($account_bl as $values) <li><strong>{{ $values }}</strong></li> @endforeach ";
    @else
        document.getElementById("a_fix_container").innerHTML = "<h3> In account, nothing need to fix. </h3>";
    @endif
    @if (count($account_er))
        document.getElementById("a_error").innerHTML = "<h3> error list </h3> @foreach ($account_er as $values) <li>{{ $values }}</li> @endforeach ";
    @else
        document.getElementById("a_error").innerHTML = "";
    @endif

    document.getElementById("msg").innerHTML = "";
});
