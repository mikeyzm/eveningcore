@extends('layouts.app')

@section('content')
    <div class="container">
        <converter placeholder="{{ __('convert.choose_file') }}" inline-template>
            <form method="post" action="{{ route('converts.store') }}" class="card mb-4" enctype="multipart/form-data">
                @csrf
                <div class="card-header">{{ __('convert.new_convert') }}</div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="list-unstyled mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group mb-4">
                        <label for="source">{{ __('convert.source') }}</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="source" name="source" @change="onFileChange" accept="{{ $allowed_exts }}" aria-describedby="sourceHelpBlock">
                            <label class="custom-file-label" for="source" data-browse="{{ __('convert.browse') }}" v-text="filename"></label>
                            <small id="sourceHelpBlock" class="form-text text-muted">
                                {{ __('convert.source_help', ['allowed_exts' => $allowed_exts, 'expire_time' => config('convert.expire_time')]) }}
                            </small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="between-box" for="tempo">
                            {{ __('convert.tempo') }}
                            <span>@{{ 'x' + options.tempo }}</span>
                        </label>
                        <input type="range" class="custom-range" min="1" max="2" step="0.01" id="tempo" name="options[tempo]" v-model="options.tempo">
                    </div>
                    <div class="form-group">
                        <label class="between-box" for="pitch">
                            {{ __('convert.pitch') }}
                            <span>@{{ 'x' + options.pitch }}</span>
                        </label>
                        <input type="range" class="custom-range" min="1" max="2" step="0.01" id="pitch" name="options[pitch]" v-model="options.pitch">
                    </div>
                    <div class="form-group mb-0">
                        <label class="between-box" for="volume">
                            {{ __('convert.volume') }}
                            <span>@{{ 'x' + options.volume }}</span>
                        </label>
                        <input type="range" class="custom-range" min="0.1" max="2" step="0.01" id="volume" name="options[volume]" v-model="options.volume">
                    </div>
                </div>
                <div class="card-footer">
                    <p v-if="uploading">{{ __('convert.uploading') }}</p>
                    <button class="btn btn-primary" @click="uploading = true" :disabled="uploading">{{ __('convert.convert_to_nightcore') }}</button>
                </div>
            </form>
        </converter>

        @if($converts->isNotEmpty())
            <h2 class="h5 mb-4 font-weight-bold">{{ __('convert.recent_converts') }}</h2>
            @foreach($converts as $convert)
                <convert :_convert="{{ $convert->toJson() }}" inline-template>
                    <div class="card mb-3" v-if="!removed">
                        <div class="card-header">
                            <h2 class="original-name">{{ $convert->original_name }}</h2>
                        </div>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item between-box">
                                {{ __('convert.status') }}
                                <span :class="['badge', statusClass]" v-text="convert.status_desc"></span>
                            </div>
                            <div class="list-group-item between-box" v-if="convert.expired_at">
                                {{ __('convert.expired_at') }}
                                <time class="badge badge-light" v-text="expiredAt"></time>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="text-monospace">
                                <h3 class="h6 mb-3">{{ __('convert.options') }}</h3>
                                @foreach($convert->options as $option)
                                    <p class="mb-1">
                                        {{ __('convert.' . $option->name) }}:
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
                                {{ __('convert.download') }}
                            </a>
                        </div>
                    </div>
                </convert>
            @endforeach
            {{ $converts->links() }}
        @endif
    </div>
@endsection
