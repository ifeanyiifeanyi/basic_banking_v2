<div class="requirement-form mb-3">
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Field Name</label>
                <input type="text" class="form-control" name="requirements[{{ $index }}][field_name]"
                    value="{{ old("requirements.$index.field_name", $requirement->field_name ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Field Type</label>
                <select class="form-control" name="requirements[{{ $index }}][field_type]" required>
                    @php
                        $types = ['text', 'number', 'file', 'select'];
                        $selectedType = old("requirements.$index.field_type", $requirement->field_type ?? '');
                    @endphp
                    @foreach ($types as $type)
                        <option value="{{ $type }}" {{ $selectedType == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if (isset($requirement) && $requirement->field_type === 'select')
                <div class="mb-3 field-options">
                    <label class="form-label">Options (one per line)</label>
                    <textarea class="form-control" name="requirements[{{ $index }}][field_options]" rows="3">{{ isset($requirement->field_options) ? implode("\n", $requirement->field_options) : '' }}</textarea>
                </div>
            @endif

            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input"
                        name="requirements[{{ $index }}][is_required]" value="1"
                        {{ old("requirements.$index.is_required", $requirement->is_required ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label">Required</label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="requirements[{{ $index }}][description]" rows="2">{{ old("requirements.$index.description", $requirement->description ?? '') }}</textarea>
            </div>

            @if ($index > 0)
                <button type="button" class="btn btn-danger btn-sm remove-requirement">Remove</button>
            @endif
        </div>
    </div>
</div>
