@if ($paginator->hasPages())
<ul>
    @if ($paginator->onFirstPage())
    <li>
        <a>
            <i class="lni lni-chevron-left"></i>
        </a>
    </li>

    @else
        <li>
            <a href="{{ $paginator->previousPageUrl() }}">
                <i class="lni lni-chevron-left"></i>
            </a>
        </li>
    @endif

    @foreach ($elements as $element)
        @if(is_array($element) || is_object($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li class="active disabled"><a>{{ $page }}</a></li>
                @endif
            @endforeach
        @endif
    @endforeach

    <li><a>{{ $paginator->lastPage() }}</a></li>

    @if ($paginator->hasMorePages())
        <li>
            <a href="{{ $paginator->nextPageUrl() }}">
                <i class="lni-chevron-right"></i>
            </a>
        </li>
    @else
        <li>
            <a>
                <i class="lni-chevron-right"></i>
            </a>
        </li>
    @endif
</ul>
@endif
