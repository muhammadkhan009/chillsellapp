@if($data and isset($data['cat_id']) and isset($data['sub_limit']))
@if (isset($categories) and $categories->count() > 0)
@includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.spacer', 'home.inc.spacer'])
@php
$categoriesAll = array();
foreach ($categories as $key => $cols)
{
    foreach ($cols as $iCat) {
        $categoriesAll[] = $iCat;
    }
}
@endphp
<div class="container">
    <div class="col-xl-12 popular_category">
        <div class="row">
            <div class="col-xl-9">
                <h2>
                    <span>Popular in {{$categoriesAll[$data['cat_id']]->name}}</span>
                    <span class="float-right"><a href="{{ \App\Helpers\UrlGen::category($categoriesAll[$data['cat_id']]) }}">Browse all {{$categoriesAll[$data['cat_id']]->name}}</a></span>
                </h2>
            </div>
            <div class="col-xl-3"></div>
        </div>
        <div class="row">
            <div class="col-xl-9">
                <div class="row">
                @if (isset($subCategories) and $subCategories->has($categoriesAll[$data['cat_id']]->id))
                    @foreach ($subCategories->get($categoriesAll[$data['cat_id']]->id)->slice(0, $data['sub_limit']) as $iSubCat)
                        <div class="col-xl-4 col-md-4 col-lg-4 col-sm-12 mb-3">
                            <a href="{{ \App\Helpers\UrlGen::category($iSubCat) }}">
                            <div class="cat-image">
                                <img src="{{ imgUrl($iSubCat->picture, 'cat') }}" alt="{{$iSubCat->name}}">
                                <h2 class="cat-title">{{ $iSubCat->name }}</h2>
                            </div>
                            </a>
                        </div>
                    @endforeach
                @endif
                </div>
            </div>
            <div class="col-xl-3"></div>
        </div>
    </div>
</div>
@endif
@endif