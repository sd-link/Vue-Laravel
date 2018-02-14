<div class="form-group title-group">
    <label for="{{ $id }}">{{ $label }}</label>
    @if($mode == 'edit')
        <input type="text" placeholder="{{ $placeholder }}" value="{{ $value }}" id="{{ $id }}" class="form-control">
    @else
        <input type="text" placeholder="{{ $placeholder }}" id="{{ $id }}" class="form-control">
    @endif

    <span class="help-block">
        <small class="{{ $name }}-feedback"></small>
    </span>
</div>
