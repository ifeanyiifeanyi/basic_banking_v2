@extends('admin.layouts.admin')

@section('title', 'Create Currency')

@section('css')

@endsection


@section('admin')
    <div class="container">
        <div class="mb-3">
            <a href="{{ route('admin.currency.index') }}" class="btn btn-link">Back to Currency Manager</a>
        </div>
        <h2>Create Currency</h2>
        <div class="row">
            <div class="mx-auto col-md-8">
                <div class="px-3 py-3 shadow card">
                    <form method="POST" action="{{ route('admin.currency.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="code">Currency Code</label>
                            <input type="text" id="code" name="code" class="form-control"  value="{{ old('code') }}">
                            @error('code')
                                <div class="text-danger">
                                   {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="symbol">Currency Symbol</label>
                            <input type="text" id="symbol" name="symbol" class="form-control"  value="{{ old('symbol') }}">
                            @error('symbol')
                                <div class="text-danger">
                                   {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="currency">Currency Name</label>
                            <input type="text" id="currency" name="currency" class="form-control"  value="{{ old('currency') }}">
                            @error('currency')
                                <div class="text-danger">
                                   {{ $message }}
                                </div>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label for="interest_rate">Interest Rate</label>
                            <input type="number" id="interest_rate" name="interest_rate" class="form-control"  value="{{ old('interest_rate') }}">
                            @error('interest_rate')
                            <div class="text-danger">
                               {{ $message }}
                            </div>
                        @enderror

                        </div>
                        <div class="form-group">
                            <label for="is_active">Status</label>
                            <select id="is_active" name="is_active" class="form-control" >
                                <option value="" disabled selected>Select Status</option>
                                <option{{ old('true') == 1 ? 'selected' : '' }} value="1">Active</option>
                                <option {{ old('false') == 0 ? 'selected' : '' }} value="0">Inactive</option>
                            </select>
                            @error('is_active')
                                <div class="text-danger">
                                   {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" >{{ old('description') }}</textarea>
                            @error('discription')
                                <div class="text-danger">
                                   {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">Create Currency</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('javascript')
