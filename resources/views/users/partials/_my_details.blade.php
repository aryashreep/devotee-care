@if($view === 'view')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <p class="text-gray-700 font-bold">Full Name:</p>
        <p>{{ $user->name }}</p>
    </div>
    <div>
        <p class="text-gray-700 font-bold">Gender:</p>
        <p>{{ $user->gender }}</p>
    </div>
    <div>
        <p class="text-gray-700 font-bold">Date of Birth:</p>
        <p>{{ $user->date_of_birth ? $user->date_of_birth->format('d-m-Y') : 'N/A' }}</p>
    </div>
    <div>
        <p class="text-gray-700 font-bold">Marital Status:</p>
        <p>{{ $user->marital_status }}</p>
    </div>
    @if($user->marital_status == 'Married')
    <div>
        <p class="text-gray-700 font-bold">Marriage Anniversary Date:</p>
        <p>{{ $user->marriage_anniversary_date ? $user->marriage_anniversary_date->format('d-m-Y') : 'N/A' }}</p>
    </div>
    @endif
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Gender</label>
        <div class="mt-2">
            <label class="inline-flex items-center">
                <input type="radio" name="gender" value="Male" class="form-radio" {{ old('gender', $user->gender) == 'Male' ? 'checked' : '' }}>
                <span class="ml-2">Male</span>
            </label>
            <label class="inline-flex items-center ml-6">
                <input type="radio" name="gender" value="Female" class="form-radio" {{ old('gender', $user->gender) == 'Female' ? 'checked' : '' }}>
                <span class="ml-2">Female</span>
            </label>
        </div>
    </div>
    <div>
        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="marital_status" class="block text-sm font-medium text-gray-700">Marital Status</label>
        <input type="text" name="marital_status" id="marital_status" value="{{ old('marital_status', $user->marital_status) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="marriage_anniversary_date" class="block text-sm font-medium text-gray-700">Marriage Anniversary Date</label>
        <input type="date" name="marriage_anniversary_date" id="marriage_anniversary_date" value="{{ old('marriage_anniversary_date', $user->marriage_anniversary_date ? $user->marriage_anniversary_date->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
</div>
@endif
