@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl leading-tight">
            <i class="fas fa-users mr-2"></i>Users
        </h2>
        <a href="{{ route('users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-plus mr-2"></i>New User
        </a>
    </div>

    <div class="my-4 flex justify-between items-center">
        <form action="{{ route('users.index') }}" method="GET" class="flex items-center">
            <input name="search" placeholder="Search"
                   class="appearance-none border border-r-0 border-gray-300 rounded-l py-2 px-4 bg-white text-sm placeholder-gray-400 text-gray-700 focus:outline-none"
                   value="{{ $search ?? '' }}"
                   style="width: 200px;" />
            <input type="hidden" name="per_page" value="{{ $perPage ?? 10 }}">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
        <form action="{{ route('users.index') }}" method="GET">
            <input type="hidden" name="search" value="{{ $search ?? '' }}">
            <select name="per_page" class="appearance-none rounded border bg-white border-gray-300 text-gray-700 py-2 px-4 pr-8 leading-tight focus:outline-none" onchange="this.form.submit()">
                <option value="10" @if($perPage == 10) selected @endif>10</option>
                <option value="25" @if($perPage == 25) selected @endif>25</option>
                <option value="50" @if($perPage == 50) selected @endif>50</option>
            </select>
        </form>
    </div>

    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                <table class="min-w-full leading-normal">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-5 py-3 border-t border-b border-gray-200 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-5 py-3 border-t border-b border-gray-200 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-5 py-3 border-t border-b border-gray-200 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mobile Number</th>
                            <th scope="col" class="px-5 py-3 border-t border-b border-gray-200 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Enabled</th>
                            <th scope="col" class="px-5 py-3 border-t border-b border-gray-200 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($users as $user)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $user->id }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $user->name }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $user->mobile_number }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <label class="switch">
                                    <input type="checkbox" data-user-id="{{ $user->id }}" @if($user->enabled) checked @endif>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <a href="{{ route('users.show', $user->id) }}" class="text-gray-600 hover:text-gray-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
        <span class="text-xs xs:text-sm text-gray-900">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} Entries
        </span>
        <div class="inline-flex mt-2 xs:mt-0">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleSwitches = document.querySelectorAll('.switch input[type="checkbox"]');
        toggleSwitches.forEach(function (switchElement) {
            switchElement.addEventListener('change', function () {
                const userId = this.dataset.userId;
                const url = `/users/${userId}/toggle-enabled`;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        // Revert the switch if the request fails
                        this.checked = !this.checked;
                    }
                })
                .catch(() => {
                    // Revert the switch if there's a network error
                    this.checked = !this.checked;
                });
            });
        });
    });
</script>
@endpush
