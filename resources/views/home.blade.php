@extends('layouts.app')

@section('content')
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <converter placeholder="{{ __('Choose file') }}" inline-template>
            <form method="post" action="{{ route('converts.store') }}" class="card mb-5" enctype="multipart/form-data">
                @csrf
                <div class="card-header">{{ __('New Convert') }}</div>
                <div class="card-body">
                    <div class="form-group mb-4">
                        <label for="source">{{ __('Source Audio') }}</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="source" name="source" @change="onFileChange">
                            <label class="custom-file-label" for="source" data-browse="{{ __('Browse') }}" v-text="filename"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="between-box" for="tempo">
                            {{ __('Tempo') }}
                            <span>@{{ 'x' + options.tempo }}</span>
                        </label>
                        <input type="range" class="custom-range" min="1" max="2" step="0.01" id="tempo" name="options[tempo]" v-model="options.tempo">
                    </div>
                    <div class="form-group">
                        <label class="between-box" for="pitch">
                            {{ __('Pitch') }}
                            <span>@{{ 'x' + options.pitch }}</span>
                        </label>
                        <input type="range" class="custom-range" min="1" max="2" step="0.01" id="pitch" name="options[pitch]" v-model="options.pitch">
                    </div>
                    <div class="form-group mb-0">
                        <label class="between-box" for="volume">
                            {{ __('Volume') }}
                            <span>@{{ 'x' + options.volume }}</span>
                        </label>
                        <input type="range" class="custom-range" min="0.1" max="2" step="0.01" id="volume" name="options[volume]" v-model="options.volume">
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary">{{ __('Convert to Nightcore') }}</button>
                </div>
            </form>
        </converter>

        @if($converts)
            <h2 class="h5 mb-4">Last 10 converted audios</h2>
            @foreach($converts as $convert)
                <convert :_convert="{{ $convert->toJson() }}" inline-template>
                    <div class="card mb-3" v-if="!removed">
                        <div class="card-header">
                            <h2 class="original-name">{{ $convert->original_name }}</h2>
                        </div>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item between-box">
                                Status
                                <span :class="['badge', statusClass]" v-text="convert.status_desc"></span>
                            </div>
                            <div class="list-group-item between-box" v-if="convert.expired_at">
                                {{ __('Expired at') }}
                                <time class="badge badge-light" v-text="convert.expired_at" ref="expiredAt"></time>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="text-monospace">
                                <h3 class="h6 mb-3">Convert options</h3>
                                @foreach($convert->options as $option)
                                    <p class="mb-1">
                                        {{ __(title_case($option->name)) }}:
                                        @switch($option->name)
                                            @case('tempo')
                                            @case('pitch')
                                            @case('volume')
                                            x{{ $option->value }}
                                            @break
                                            @default
                                            {{ $option->value }}
                                        @endswitch
                                    </p>
                                @endforeach
                            </div>
                            <audio class="w-100 mt-4" controls :src="convert.url" v-if="convert.status === 2"></audio>
                        </div>
                        <div class="card-footer">
                            <a :href="convert.url" :download="convert.original_name" :class="['btn', 'btn-primary', {disabled: convert.status !== 2}]">
                                {{ __('Download') }}
                            </a>
                        </div>
                    </div>
                </convert>
            @endforeach
        @endif
    </div>
@endsection
