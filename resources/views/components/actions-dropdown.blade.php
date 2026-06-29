{{-- 
  Reusable Actions Dropdown Component
  Usage: 
  @include('components.actions-dropdown', ['items' => [
      ['url' => route('...'), 'icon' => 'ti ti-edit', 'label' => 'Edit'],
      ['divider' => true],
      ['url' => route('...'), 'icon' => 'ti ti-trash', 'label' => 'Delete', 'class' => 'text-danger', 'method' => 'DELETE', 'confirm' => 'Are you sure?'],
  ]])
--}}
<div class="dropdown">
    <button class="btn btn-sm btn-light border dropdown-actions-btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Actions">
        <i class="ti ti-dots-vertical fs-5"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        @foreach($items as $item)
            @if(isset($item['divider']) && $item['divider'])
                <li><hr class="dropdown-divider"></li>
            @elseif(isset($item['url']))
                <li>
                    @if(isset($item['method']) && $item['method'] === 'DELETE')
                        <form action="{{ $item['url'] }}" method="POST" onsubmit="return confirm('{{ $item['confirm'] ?? 'Are you sure?' }}');">
                            @csrf @method('DELETE')
                            <button type="submit" class="dropdown-item {{ $item['class'] ?? '' }}">
                                <i class="{{ $item['icon'] }} me-2"></i> {{ $item['label'] }}
                            </button>
                        </form>
                    @else
                        <a class="dropdown-item {{ $item['class'] ?? '' }}" href="{{ $item['url'] }}">
                            <i class="{{ $item['icon'] }} me-2"></i> {{ $item['label'] }}
                        </a>
                    @endif
                </li>
            @elseif(isset($item['html']))
                <li>{!! $item['html'] !!}</li>
            @endif
        @endforeach
    </ul>
</div>