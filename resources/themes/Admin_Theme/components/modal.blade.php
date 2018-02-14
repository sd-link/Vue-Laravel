
    <div class="modal" id="{{ $id }}">
        <div class="vertical-alignment-helper">
            <div class="{{ $class }} vertical-align-center">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">{{ $title }}</h4>
                    </div>
                    <div class="modal-body" id="modalBody">
                        {{ $content }}
                    </div>
                    @if(isset($footer))
                        <div class="modal-footer">
                            {{ $footer }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    @if(isset($script))
        {{ $script }}
    @endif
@endpush
