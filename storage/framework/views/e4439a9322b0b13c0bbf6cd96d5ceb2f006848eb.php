<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['steps' => [], 'currentStep' => 1]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['steps' => [], 'currentStep' => 1]); ?>
<?php foreach (array_filter((['steps' => [], 'currentStep' => 1]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-200">
    <div class="flex items-center justify-between">
        <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center <?php echo e($index < count($steps) - 1 ? 'flex-1' : ''); ?>">
                <!-- Step Circle -->
                <div class="flex items-center justify-center w-8 h-8 rounded-full <?php echo e($index + 1 <= $currentStep ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600'); ?> font-semibold text-sm">
                    <?php echo e($index + 1); ?>

                </div>
                
                <!-- Step Label -->
                <span class="ml-2 text-sm font-medium <?php echo e($index + 1 <= $currentStep ? 'text-blue-600' : 'text-gray-500'); ?>">
                    <?php echo e($step); ?>

                </span>
                
                <!-- Progress Line -->
                <?php if($index < count($steps) - 1): ?>
                    <div class="flex-1 h-0.5 mx-4 <?php echo e($index + 1 < $currentStep ? 'bg-blue-600' : 'bg-gray-300'); ?>"></div>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div><?php /**PATH C:\xampp\htdocs\ppdb\resources\views/components/form-progress.blade.php ENDPATH**/ ?>