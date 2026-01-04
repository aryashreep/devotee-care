@if($view === 'view')
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Name
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Age
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Gender
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Date of Birth
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($user->dependants as $dependant)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $dependant->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $dependant->age }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $dependant->gender }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $dependant->dob ? $dependant->dob->format('d-m-Y') : 'N/A' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        No dependants found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@else
<div id="dependants-container">
    @foreach(old('dependants', $user->dependants) as $index => $dependant)
    <div class="dependant-group border-b pb-4 mb-4">
        <input type="hidden" name="dependants[{{ $index }}][id]" value="{{ $dependant->id ?? '' }}">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="dependants[{{ $index }}][name]" value="{{ $dependant->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Age</label>
                <input type="number" name="dependants[{{ $index }}][age]" value="{{ $dependant->age }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Gender</label>
                <select name="dependants[{{ $index }}][gender]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="Male" {{ $dependant->gender == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ $dependant->gender == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                <input type="date" name="dependants[{{ $index }}][dob]" value="{{ $dependant->dob ? $dependant->dob->format('Y-m-d') : '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="flex items-end">
                <button type="button" class="remove-dependant-btn bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Remove</button>
            </div>
        </div>
    </div>
    @endforeach
</div>
<button type="button" id="add-dependant-btn" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Dependant</button>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('dependants-container');
    const addButton = document.getElementById('add-dependant-btn');
    let dependantIndex = {{ count(old('dependants', $user->dependants)) }};

    addButton.addEventListener('click', function () {
        const newDependant = document.createElement('div');
        newDependant.classList.add('dependant-group', 'border-b', 'pb-4', 'mb-4');
        newDependant.innerHTML = `
            <input type="hidden" name="dependants[${dependantIndex}][id]" value="">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="dependants[${dependantIndex}][name]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Age</label>
                    <input type="number" name="dependants[${dependantIndex}][age]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Gender</label>
                    <select name="dependants[${dependantIndex}][gender]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                    <input type="date" name="dependants[${dependantIndex}][dob]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="flex items-end">
                    <button type="button" class="remove-dependant-btn bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Remove</button>
                </div>
            </div>
        `;
        container.appendChild(newDependant);
        dependantIndex++;
    });

    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-dependant-btn')) {
            e.target.closest('.dependant-group').remove();
        }
    });
});
</script>
@endpush
@endif