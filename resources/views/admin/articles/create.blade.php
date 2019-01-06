@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.articles.title')</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.articles.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('en_title', trans('global.articles.title').' [EN]', ['class' => 'control-label']) !!}
                    {!! Form::text('en_title', old('en_title'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('en_title'))
                        <p class="help-block">
                            {{ $erros->first('en_title') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('en_article_text', trans('global.articles.title').' [EN]', ['class' => 'control-label']) !!}
                    {!! Form::text('en_article_text', old('en_article_text'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('en_article_text'))
                        <p class="help-block">
                            {{ $erros->first('en_article_text') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('es_title', trans('global.articles.title').' [ES]', ['class' => 'control-label']) !!}
                    {!! Form::text('es_title', old('es_title'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('es_title'))
                        <p class="help-block">
                            {{ $erros->first('es_title') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('es_article_text', trans('global.articles.title').' [ES]', ['class' => 'control-label']) !!}
                    {!! Form::text('es_article_text', old('es_article_text'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('es_article_text'))
                        <p class="help-block">
                            {{ $erros->first('es_article_text') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
