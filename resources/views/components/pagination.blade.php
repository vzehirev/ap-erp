@if ($lengthAwarePaginator->lastPage() > 1)
    <nav class="d-flex justify-content-center">
        <ul class="pagination">
            <li class="page-item">
                <a class="page-link" href="?page=1" aria-label="Previous">
                    <span>Първа</span>
                </a>
            </li>
            @for ($page = $lengthAwarePaginator->currentPage() - 2; $page <= $lengthAwarePaginator->currentPage() + 2; $page++)
                @if ($page <= 0 || $page > $lengthAwarePaginator->lastPage())
                    @continue
                @endif
                <li class="page-item @if ($lengthAwarePaginator->currentPage() === $page) active @endif"><a class="page-link"
                        href="?page={{ $page }}">{{ $page }}</a>
                </li>
            @endfor
            <li class="page-item">
                <a class="page-link" href="?page={{ $lengthAwarePaginator->lastPage() }}" aria-label="Next">
                    <span aria-hidden="true">Последна</span>
                </a>
            </li>
        </ul>
    </nav>
@endif
