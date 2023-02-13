<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
        @foreach ($data as $key=> $bc)
        @if(count($data)-1==$key)
            <li class="breadcrumb-item active" aria-current="page"><span>{{$bc}}</span></li>
        @else
        <li class="breadcrumb-item"><a>{{$bc}}</a></li>
        @endif
        @endforeach
    </ol>
</nav>