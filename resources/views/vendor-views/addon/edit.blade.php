@extends('layouts.vendor.app')

@section('title',translate('Update addon'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('assets/admin/img/edit.png')}}" class="w--22" alt="">
                </span>
                <span> {{translate('messages.addon_update')}}</span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-body">
                <form action="{{route('vendor.addon.update',[$addon['id']])}}" method="post">
                    @csrf
                    @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                    @php($language = $language->value ?? null)
                    @php($defaultLang = str_replace('_', '-', app()->getLocale()))

                    @if($language)
                        @php($defaultLang = json_decode($language)[0])
                        <ul class="nav nav-tabs mb-4 border-0">
                            <li class="nav-item">
                                <a class="nav-link lang_link active"
                                href="#"
                                id="default-link">{{translate('messages.default')}}</a>
                            </li>
                            @foreach (json_decode($language) as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link"
                                        href="#"
                                        id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($language)
                    <div class="form-group lang_form" id="default-form">
                        <label class="input-label" for="name">{{translate('messages.name')}} ({{ translate('messages.Default') }})</label>
                        <input type="text" id="name" name="name[]" class="form-control" placeholder="{{translate('messages.new_addon')}}" maxlength="191" value="{{$addon->getRawOriginal('name')}}"  >
                    </div>
                    <input type="hidden" name="lang[]" value="{{$lang}}">
                        @foreach(json_decode($language) as $lang)
                            <?php
                                if(count($addon['translations'])){
                                    $translate = [];
                                    foreach($addon['translations'] as $t)
                                    {
                                        if($t->locale == $lang && $t->key=="name"){
                                            $translate[$lang]['name'] = $t->value;
                                        }
                                    }
                                }
                            ?>
                            <div class="form-group d-none lang_form" id="{{$lang}}-form">
                                <label class="input-label" for="name{{$lang}}">{{translate('messages.name')}} ({{strtoupper($lang)}})</label>
                                <input id="name{{$lang}}" type="text" name="name[]" class="form-control" placeholder="{{translate('messages.new_addon')}}" maxlength="191" value="{{$translate[$lang]['name']??''}}"  >
                            </div>
                            <input type="hidden" name="lang[]" value="{{$lang}}">
                        @endforeach
                    @else
                        <div class="form-group">
                            <label class="input-label" for="name">{{translate('messages.name')}}</label>
                            <input id="name" type="text" name="name" class="form-control" placeholder="{{translate('messages.new_addon')}}" value="{{ $attribute['name'] }}" required maxlength="191">
                        </div>
                        <input type="hidden" name="lang[]" value="default">
                    @endif

                        <div class="form-group">
                            <label class="input-label" for="price">{{translate('messages.price')}}</label>
                            <input id="price" type="number" min="0" max="999999999999.99" step="0.01" name="price" value="{{$addon['price']}}" class="form-control" placeholder="200" required>
                        </div>

                        <div class="btn--container justify-content-end">
                            <button type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                            <button type="submit" class="btn btn--primary">{{translate('messages.update')}}</button>
                        </div>
                </form>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection


