@extends('layouts.app')

@section('content')
    <div class="container">
        <converter placeholder="{{ __('Choose file') }}" inline-template>
            <form method="post" action="{{ route('converts.store') }}" class="card mb-5" enctype="multipart/form-data">
                @csrf
                <div class="card-header">{{ __('New Convert') }}</div>
                <div class="card-body">
                    <div class="form-group">
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

        @foreach($converts as $convert)
            <audio class="w-100 mt-4" controls src="{{ $convert->url }}"></audio>
        @endforeach
    </div>
@endsection
