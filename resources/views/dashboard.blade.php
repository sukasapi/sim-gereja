@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mx-auto">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Selamat Datang</h2>
            <p class="text-gray-600">Selamat datang di dashboard {{ config('app.name') }}.</p>
        </div>
    </div>
</div>
@endsection
