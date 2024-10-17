@extends('admin.layouts.admin')

@section('title', 'Edit Currency')

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
                    {{-- @dd($currency) --}}
                    <form method="POST" action="{{ route('admin.update.currency', $currency) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="code">Currency Code</label>
                            <input type="text" id="code" name="code" class="form-control"
                                value="{{ old('code', $currency->code ?? '') }}">
                            @error('code')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="symbol">Currency Symbol</label>
                            <input type="text" id="symbol" name="symbol" class="form-control"
                                value="{{ old('symbol', $currency->symbol ?? '') }}">
                            @error('symbol')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="currency">Currency Name</label>
                            <input type="text" id="currency" name="currency" class="form-control"
                                value="{{ old('currency', $currency->currency ?? '') }}">
                            @error('currency')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label for="exchange_rate">Interest Rate</label>
                            <input type="number" id="exchange_rate" name="exchange_rate" class="form-control"
                                value="{{ old('exchange_rate', $currency->exchange_rate ?? '') }}">
                            @error('exchange_rate')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="is_active">Status</label>
                            <select id="is_active" name="is_active" class="form-control">
                                <option value="" disabled selected>Select Status</option>
                                <option{{ old('1') == $currency->is_active ? 'selected' : '' }} value="1">Active
                                    </option>
                                    <option {{ old('0') == $currency->is_active ? 'selected' : '' }} value="0">
                                        Inactive</option>
                            </select>
                            @error('is_active')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">Update Currency</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('javascript')
