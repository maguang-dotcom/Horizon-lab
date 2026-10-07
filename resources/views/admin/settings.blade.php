@extends('admin.layout')

@section('title', 'Settings')

@section('content')
    <div class="mb-6">
        <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">Horizon Lab Admin</p>
        <h1 class="mt-1 text-3xl font-bold text-slate-900">Account Settings</h1>
        <p class="mt-2 text-sm text-slate-600">Update the name and email used for your administrator account.</p>
    </div>

    <section class="max-w-2xl rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">Full name</label>
                <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}" required maxlength="255" autocomplete="name" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                @error('name')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email address</label>
                <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required maxlength="255" autocomplete="email" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                @error('email')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
            </div>
            <div class="border-t border-slate-100 pt-5">
                <p class="text-sm text-slate-600">Account role</p>
                <p class="mt-1 font-semibold capitalize text-slate-900">{{ auth()->user()->role }}</p>
            </div>
            <button type="submit" class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">Save changes</button>
        </form>
    </section>
@endsection