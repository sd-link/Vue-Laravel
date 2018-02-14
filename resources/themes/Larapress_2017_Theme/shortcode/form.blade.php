

    @foreach ($form->fields as $key => $field)
        @php
            $type = $field['type'];
        @endphp

        @if($type == 'text')
            <div class="form-group">
                <label for="">{{ $field['label'] }} @if($field['required'] == 'true')<span class="required">*</span>@endif</label>
                <input type="text" name="{{ $field['id'] }}" class="form-control">
            </div>

        @elseif($type == 'textarea')
            <div class="form-group">
                <label for="">{{ $field['label'] }} @if($field['required'] == 'true')<span class="required">*</span>@endif</label>
                <textarea name="{{ $field['id'] }}" class="form-control"></textarea>
            </div>
        @endif

    @endforeach
