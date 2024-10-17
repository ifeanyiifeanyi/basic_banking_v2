<div class="card mb-4">
    <div class="card-body">
        <div class="mb-3">
            <label for="name" class="form-label">Bank Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                   value="{{ old('name', $bank->name ?? '') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="code" class="form-label">Bank Code</label>
            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code"
                   value="{{ old('code', $bank->code ?? '') }}" required>
            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="swift_code" class="form-label">Swift Code</label>
            <input type="text" class="form-control @error('swift_code') is-invalid @enderror" id="swift_code" name="swift_code"
                   value="{{ old('swift_code', $bank->swift_code ?? '') }}">
            @error('swift_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                       {{ old('is_active', $bank->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $bank->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Bank Requirements</h5>
        <div id="requirements-container">
            @if(isset($bank) && $bank->requirements->isNotEmpty())
                @foreach($bank->requirements as $index => $requirement)
                    @include('admin.banks.requirement-form', ['index' => $index, 'requirement' => $requirement])
                @endforeach
            @else
                @include('admin.banks.requirement-form', ['index' => 0])
            @endif
        </div>
        <button type="button" class="btn btn-secondary" id="add-requirement">Add Requirement</button>
    </div>
</div>


