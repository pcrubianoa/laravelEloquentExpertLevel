@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Books</div>

                <div class="card-body">
                    <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">Book</th>
                                <th scope="col">Author</th>
                                <th scope="col">cover image</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($books as $book)
                                    <tr>
                                        <td>{{ $book->title }}</td>
                                        <td>{{ $book->author->name }}</td>
                                        <td>
                                            <img src="{{ $book->getFirstMediaUrl('cover_images', 'thumb') }}">
                                        </td>
                                        </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection