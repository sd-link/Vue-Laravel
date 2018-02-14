<div class="form-group">
    <label for="{{$tags}}">{{ $label }}</label>
    <select id="{{$tags}}" multiple="multiple" class="form-control js-states" name="{{$tags}}[]">
        @foreach ($options as $option)
            <option value="{{ $option->id }}">{{ $option->title }}</option>
        @endforeach
    </select>
</div>
