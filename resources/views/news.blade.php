@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        @foreach ($news as $newsItem)    
        <div class="card" style="width: 18rem;">
            @foreach ($newsItem->newsMedia as $media) 
                <img loading="lazy" src="{{asset('storage/' .$media->link)}}" class="card-img-top" alt="...">
                @break
            @endforeach
            
            <div class="card-body">
                <h5 class="card-title">{{$newsItem->title}}</h5>
                <p><small class="text-muted">Дата: {{$newsItem->published_at}}</small></p>
                <p class="card-text">{{$newsItem->description}}</p>
                @isset($newsItem->author)
                    <p>
                    <small class="text-muted">Автор: {{$newsItem->author}}</small>
                    </p>
                @endisset                
                <a href="{{$newsItem->link}}" target="_blank" class="btn btn-primary">В источник</a>
            </div>
        </div>                        

        @endforeach
    </div>


    </div>
</div>
@endsection
