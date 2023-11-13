@include('partials.navbar')

<h1 class="text-center">{{$exception->getStatusCode()}}</h1>
<h2 class="text-center text-2xl font-bold">{{$exception->getMessage()}}</h2>
