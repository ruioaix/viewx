$(document).ready(function() {

    document.getElementById("p_fix_container").innerHTML = "<h2> {{ count($proxy_bl) }} proxy records shourld be fixed:  </h2> @foreach ($proxy_bl as $values) <li><strong>{{ $values }}</strong></li> @endforeach ";
    @if (count($proxy_er))
        document.getElementById("p_error").innerHTML = "<h2> error list </h2> @foreach ($proxy_er as $values) <li>{{ $values }}</li> @endforeach ";
    @endif

    document.getElementById("a_fix_container").innerHTML = "<h2> {{ count($account_bl) }} account records shourld be fixed:  </h2> @foreach ($account_bl as $values) <li><strong>{{ $values }}</strong></li> @endforeach ";
    @if (count($account_er))
        document.getElementById("a_error").innerHTML = "<h2> error list </h2> @foreach ($account_er as $values) <li>{{ $values }}</li> @endforeach ";
    @endif

    document.getElementById("msg").innerHTML = "";
});
