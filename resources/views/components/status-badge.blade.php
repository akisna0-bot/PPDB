@props(['status', 'size' => 'sm'])

@php
$classes = [
    'DRAFT' => 'bg-gray-100 text-gray-800',
    'SUBMIT' => 'bg-yellow-100 text-yellow-800', 
    'VERIFIED' => 'bg-green-100 text-green-800',
    'REJECTED' => 'bg-red-100 text-red-800',
    'PAID' => 'bg-blue-100 text-blue-800'
];

$labels = [
    'DRAFT' => '📝 Draft',
    'SUBMIT' => '⏳ Menunggu Verifikasi',
    'VERIFIED' => '✅ Diverifikasi', 
    'REJECTED' => '❌ Ditolak',
    'PAID' => '💳 Sudah Bayar'
];

$sizeClasses = [
    'xs' => 'px-2 py-1 text-xs',
    'sm' => 'px-3 py-1 text-sm',
    'md' => 'px-4 py-2 text-base'
];

$class = ($classes[$status] ?? 'bg-gray-100 text-gray-800') . ' ' . ($sizeClasses[$size] ?? $sizeClasses['sm']);
$label = $labels[$status] ?? $status;
@endphp

<span {{ $attributes->merge(['class' => "rounded-full font-medium $class"]) }}>
    {{ $label }}
</span>