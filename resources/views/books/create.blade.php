@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add new book</div>

                <div class="card-body">
                    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        Book title:
                        <input type="text" class="form-control" name="title" />
                        <br />

                        Author:
                        <select name="author_id" class="form-control">
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}">{{ $author->name }}</option>
                            @endforeach
                        </select>

                        Cover image:
                        <br />
                        <input type="file" name="cover_image" />
                        <br /><br />

                        <input type="submit" value="Save book" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection