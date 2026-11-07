@props([
  'searchAction' => '',
  'searchValue' => request('search'),
  'searchPlaceholder' => 'Cari data...',
  'filters' => null,
  'actions' => null
])

<div class="row" style="margin-bottom: 15px;">
  <div class="col-md-6 col-sm-12">
    <form action="{{ $searchAction }}" method="GET" class="form-inline">
      <div class="input-group" style="width: 280px;">
        <input type="text" name="search" value="{{ $searchValue }}" class="form-control" placeholder="{{ $searchPlaceholder }}">
        <span class="input-group-btn">
          <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
        </span>
      </div>
      @if($searchValue)
        <a href="{{ $searchAction }}" class="btn btn-default text-muted" title="Reset filter"><i class="fa fa-times"></i></a>
      @endif

      @if($filters)
        <span style="margin-left: 10px;">
          {!! $filters !!}
        </span>
      @endif
    </form>
  </div>
  <div class="col-md-6 col-sm-12 text-right">
    @if($actions)
      {!! $actions !!}
    @endif
  </div>
</div>

