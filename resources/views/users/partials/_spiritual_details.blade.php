<div id="spiritual-details" class="tab-content hidden">
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="initiated">Initiated</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="initiated" name="initiated" type="text" value="{{ $user->initiated ? 'Yes' : 'No' }}" disabled>
    </div>
    @if($user->initiated)
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="spiritual_master">Spiritual Master</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="spiritual_master" name="spiritual_master" type="text" value="{{ $user->spiritual_master }}" disabled>
    </div>
    @else
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="rounds">Rounds</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="rounds" name="rounds" type="text" value="{{ $user->rounds }}" disabled>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Shiksha Levels</label>
        <div class="flex flex-wrap">
            @foreach($user->shikshaLevels as $level)
                <span class="bg-gray-200 text-gray-700 text-sm font-semibold mr-2 px-2.5 py-0.5 rounded-full">{{ $level->name }}</span>
            @endforeach
        </div>
    </div>
    @endif
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="second_initiation">Second Initiation</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="second_initiation" name="second_initiation" type="text" value="{{ $user->second_initiation ? 'Yes' : 'No' }}" disabled>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="bhakti_sadan">Bhakti Sadan</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="bhakti_sadan" name="bhakti_sadan" type="text" value="{{ $user->bhaktiSadan->name ?? '' }}" disabled>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="life_membership">Life Membership</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="life_membership" name="life_membership" type="text" value="{{ $user->life_membership ? 'Yes' : 'No' }}" disabled>
    </div>
    @if($user->life_membership)
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="life_member_no">Life Member No</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="life_member_no" name="life_member_no" type="text" value="{{ $user->life_member_no }}" disabled>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="temple">Temple</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="temple" name="temple" type="text" value="{{ $user->temple }}" disabled>
    </div>
    @endif
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Services</label>
        <div class="flex flex-wrap">
            @foreach($user->sevas as $seva)
                <span class="bg-gray-200 text-gray-700 text-sm font-semibold mr-2 px-2.5 py-0.5 rounded-full">{{ $seva->name }}</span>
            @endforeach
        </div>
    </div>
</div>
