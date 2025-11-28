@props(['size' => 'md', 'color' => 'blue'])

@php
$sizeClasses = [
    'sm' => 'w-4 h-4',
    'md' => 'w-6 h-6', 
    'lg' => 'w-8 h-8',
    'xl' => 'w-12 h-12'
];

$colorClasses = [
    'blue' => 'border-blue-600',
    'green' => 'border-green-600',
    'red' => 'border-red-600',
    'gray' => 'border-gray-600'
];
@endphp

<div class="inline-flex items-center justify-center">
    <div class="animate-spin rounded-full {{ $sizeClasses[$size] }} border-2 border-gray-200 {{ $colorClasses[$color] }} border-t-transparent"></div>
</div>