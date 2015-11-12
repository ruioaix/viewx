@extends('index')

@section('title', 'ErrorType of Proxy')
@endsection

@section('content')
<h1> Proxy Error Type </h1>
<ul>
<li> success: login xueqiu success.</li>

<li> unreached proxy error:</li>
<ul>
<li> CE-xxxxx: no response from proxy, error happened when requests raise <strong>requests.exceptions.ConnectionError.</strong></li>
<li> TO: no response from proxy, error happened when requests raise <strong>requests.exceptions.Timeout</strong></li>
<li> Socket: no response from proxy, error happened when requests raise <strong>socket.error</strong></li>
<li> Unknown: no response from proxy, error happened when requests raise Exception which is not any of above.</li>
</ul>

<li> reach error proxy:</li>
<ul>
<li> 307-ProxyError: proxy return 307</li>
<li> 403-ProxyError: proxy return 403</li>
<li> 404-GarbageResp: proxy return 404, proxy return garbage text</li>
<li> 407-ProxyError: proxy return 407, most likely the proxy reqires authentication</li>
<li> 411-ProxyError: proxy return 411</li>
<li> 500-ProxyError: proxy return 500</li>
<li> 502-ProxyError: proxy return 502</li>
<li> 503-ProxyError: proxy return 503</li>
<li> 504-ProxyError: proxy return 504</li>
</ul>

<li> xueqiu error:</li>
<ul>
<li> 200-Verification: the ip is exhausted, xueqiu need the verification. </li> 
<li> 400-WrongContent: json request, xueqiu return wrong content, most likely this is xueqiu's new firewall. </li> 
</ul>

<li> not dealing specially: </li>
<ul>
<li> ADJUST-NewHttpStatusCode: the error get when fetch xueqiu adjust data, but most likely it means proxy not available any more. </li>
<li> LOGIN-NewHttpStatusCode: the error get when login xueqiu, most likely, it means reach proxy error. </li>
</ul>

<li> discard: </li>
<ul>
<li> 302-MaxExcedMaxTry </li>
</ul>

</ul>
</ul>

@endsection
