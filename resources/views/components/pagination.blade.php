@if ($lengthAwarePaginator->lastPage() > 1)
    <nav class="d-flex justify-content-center">
        <ul class="pagination">
            <li class="page-item">
                <a class="page-link" href="{{ durl(Request::getPathInfo(), ['page' => 1]) }}">
                    <span>{{ __('app.pagination.first') }}</span>
                </a>
            </li>
            @for ($page = $lengthAwarePaginator->currentPage() - 2; $page <= $lengthAwarePaginator->currentPage() + 2; $page++)
                @if ($page <= 0 || $page > $lengthAwarePaginator->lastPage())
                    @continue
                @endif
                <li class="page-item @if ($lengthAwarePaginator->currentPage() === $page) active @endif">
                    <a class="page-link"
                        href="{{ durl(Request::getPathInfo(), ['page' => $page]) }}">{{ $page }}</a>
                </li>
            @endfor
            <li class="page-item">
                <a class="page-link" href="{{ durl(Request::getPathInfo(), ['page' => $lengthAwarePaginator->lastPage()]) }}">
                    <span>{{ __('app.pagination.last') }}</span>
                </a>
            </li>
        </ul>
    </nav>
@endif
