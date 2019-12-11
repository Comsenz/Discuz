@extends('install.app')

@section('content')
<h2>环境检测</h2>

<p> 服务器信息，请解决以下问题然后刷新页面：可看到安装文档 <a href="https://www.discuz.net/docs/install.html" target="_blank">Discuz Q 官方文档</a>.</p>

<div class="Problems">
    @foreach($problems as $problem)
        <div class="Problem">
            <h3 class="Problem-message">{{$problem['message']}}</h3>
            @isset($problem['detail'])
                <p class="Problem-detail">{{$problem['detail']}}</p>
            @endisset
        </div>
    @endforeach
</div>
@endsection
