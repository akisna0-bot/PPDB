@props(['steps' => [], 'currentStep' => 1])

<div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-200">
    <div class="flex items-center justify-between">
        @foreach($steps as $index => $step)
            <div class="flex items-center {{ $index < count($steps) - 1 ? 'flex-1' : '' }}">
                <!-- Step Circle -->
                <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $index + 1 <= $currentStep ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }} font-semibold text-sm">
                    {{ $index + 1 }}
                </div>
                
                <!-- Step Label -->
                <span class="ml-2 text-sm font-medium {{ $index + 1 <= $currentStep ? 'text-blue-600' : 'text-gray-500' }}">
                    {{ $step }}
                </span>
                
                <!-- Progress Line -->
                @if($index < count($steps) - 1)
                    <div class="flex-1 h-0.5 mx-4 {{ $index + 1 < $currentStep ? 'bg-blue-600' : 'bg-gray-300' }}"></div>
                @endif
            </div>
        @endforeach
    </div>
</div>