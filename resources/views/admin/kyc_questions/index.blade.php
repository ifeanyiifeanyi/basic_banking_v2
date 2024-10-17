@extends('admin.layouts.admin')

@section('title', 'KYC Questions')

@section('css')

@endsection


@section('admin')
    <div class="container">
        <x-alert-info/>
        <h2>KYC Questions</h2>
        <div class="mb-3">
            <a href="{{ route('admin.kyc_questions.create') }}" class="btn btn-primary">Add New Question</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Question</th>
                        <th>Response Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($questions as $question)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $question->question }}</td>
                            <td>{{ $question->response_type }}</td>
                            <td>
                                <a href="{{ route('admin.kyc_questions.edit', $question) }}"
                                    class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('admin.kyc_questions.destroy', $question) }}" method="POST" style="display: inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Are you sure of this action ?')" type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection


@section('javascript')
