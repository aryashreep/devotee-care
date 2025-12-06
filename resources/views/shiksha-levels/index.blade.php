@extends('layouts.app')

@section('title', 'Shiksha Level')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center mb-4">
    <h1 class="text-2xl font-bold mb-4 md:mb-0">Shiksha Level</h1>
    <a href="{{ route('shiksha-levels.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create New Shiksha Level</a>
</div>
<div class="bg-white shadow-md rounded overflow-x-auto">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($shikshaLevels as $shikshaLevel)
            <tr>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $shikshaLevel->name }}</td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <a href="{{ route('shiksha-levels.show', $shikshaLevel->id) }}" class="text-blue-600 hover:text-blue-900"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('shiksha-levels.edit', $shikshaLevel->id) }}" class="text-indigo-600 hover:text-indigo-900 ml-4"><i class="fas fa-pencil-alt"></i></a>
                    <form action="{{ route('shiksha-levels.destroy', $shikshaLevel->id) }}" method="POST" class="inline-block ml-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
