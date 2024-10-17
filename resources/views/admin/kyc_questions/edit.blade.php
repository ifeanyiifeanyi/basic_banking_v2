@extends('admin.layouts.admin')

@section('title', 'Edit KYC Question')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('admin')
    <div class="container">
        <x-alert-info />
        <h2>Edit KYC Question</h2>
        <div class="mb-3">
            <a href="{{ route('admin.kyc_questions.index') }}" class="btn btn-primary">All Questions</a>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.kyc_questions.update', $question->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="question">Question</label>
                        <input type="text" name="question" id="question" class="form-control" value="{{ old('question', $question->question) }}" required>
                        <small id="questionHelp" class="form-text text-muted">A clear and concise question for KYC verification.</small>
                        @error('question')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="response_type">Response Type</label>
                        <select name="response_type" id="response_type" class="form-control" required>
                            <option value="text" {{ old('response_type', $question->response_type) == 'text' ? 'selected' : '' }}>Text</option>
                            <option value="select" {{ old('response_type', $question->response_type) == 'select' ? 'selected' : '' }}>Select</option>
                            <option value="file" {{ old('response_type', $question->response_type) == 'file' ? 'selected' : '' }}>File Upload</option>
                            <option value="multiple_files" {{ old('response_type', $question->response_type) == 'multiple_files' ? 'selected' : '' }}>Multiple Files Upload</option>
                        </select>
                        @error('response_type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group" id="options-group" style="{{ old('response_type', $question->response_type) == 'select' ? 'display: block;' : 'display: none;' }}">
                        <label for="options">Options</label>
                        <select name="options[]" id="options" class="form-control select2-multiple" multiple>
                            @foreach(json_decode($question->options, true) ?? [] as $option)
                                <option value="{{ $option }}" selected>{{ $option }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Enter options and press Enter to add them.</small>
                        @error('options')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="is_required">Required</label>
                        <select name="is_required" id="is_required" class="form-control" required>
                            <option value="1" {{ old('is_required', $question->is_required) ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ old('is_required', $question->is_required) ? '' : 'selected' }}>No</option>
                        </select>
                        @error('is_required')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="order">Order</label>
                        <input type="number" name="order" id="order" class="form-control" value="{{ old('order', $question->order) }}" required>
                        @error('order')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update Question</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#response_type').change(function() {
                var optionsGroup = $('#options-group');
                optionsGroup.toggle(this.value === 'select');
            });

            $('.select2-multiple').select2({
                tags: true,
                tokenSeparators: [',', ' '],
                placeholder: "Add options",
                allowClear: true
            });
        });
    </script>
@endsection
