@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-navy-50">
    @include('partials.dashboard.sidebar')

    <div class="pl-72">
        @include('partials.dashboard.topbar')
        <main class="px-8 py-8 flex flex-col gap-8">
            @yield('pageContent')
        </main>
    </div>
</div>
@endsection