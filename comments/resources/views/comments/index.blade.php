@php use App\Models\Post;use Illuminate\Support\Str; @endphp

<x-app-layout>
{{--    <x-comments::form  modelClass="{{Post::class}}" modelId="{{$post->getKey()}}"/>--}}
    <br><br><br>
    <div class="m-4">
        <x-comments::index :model="$post" />
    </div>
</x-app-layout>
