@if ($paginator->hasPages())
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        {{-- Pagination Info --}}
        <div class="pagination-info mb-2 mb-md-0">
            <small class="text-muted">
                <i class="fas fa-info-circle"></i>
                Menampilkan {{ $paginator->firstItem() }} sampai {{ $paginator->lastItem() }} 
                dari {{ $paginator->total() }} entri
            </small>
        </div>

        {{-- Pagination Navigation --}}
        <nav aria-label="Pagination Navigation">
            <ul class="pagination pagination-sm m-0">
                {{-- First Page Link --}}
                @if ($paginator->currentPage() > 3)
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url(1) }}" title="Halaman Pertama">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                        <span class="page-link" aria-hidden="true" title="Halaman Sebelumnya">
                            <i class="fas fa-angle-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')" title="Halaman Sebelumnya">
                            <i class="fas fa-angle-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link bg-primary border-primary">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}" title="Halaman {{ $page }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" title="Halaman Selanjutnya">
                            <i class="fas fa-angle-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <span class="page-link" aria-hidden="true" title="Halaman Selanjutnya">
                            <i class="fas fa-angle-right"></i>
                        </span>
                    </li>
                @endif

                {{-- Last Page Link --}}
                @if ($paginator->currentPage() < $paginator->lastPage() - 2)
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" title="Halaman Terakhir">
                            <i class="fas fa-angle-double-right"></i>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@else
    {{-- Show info even when no pagination --}}
    <div class="pagination-info">
        <small class="text-muted">
            <i class="fas fa-info-circle"></i>
            @if($paginator->total() > 0)
                Menampilkan {{ $paginator->total() }} entri
            @else
                Tidak ada data untuk ditampilkan
            @endif
        </small>
    </div>
@endif