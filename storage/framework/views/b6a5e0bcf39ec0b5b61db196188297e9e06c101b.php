<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-slate-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">SMK BAKTI NUSANTARA 666</h1>
                    <p class="text-slate-200 text-sm">Formulir Pendaftaran Peserta Didik Baru</p>
                </div>
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.back-button','data' => ['url' => ''.e(route('dashboard')).'','text' => 'Kembali ke Dashboard','class' => 'bg-white/20 hover:bg-white/30 text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('back-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => ''.e(route('dashboard')).'','text' => 'Kembali ke Dashboard','class' => 'bg-white/20 hover:bg-white/30 text-white']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Progress Indicator -->
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form-progress','data' => ['steps' => ['Isi Formulir', 'Upload Dokumen', 'Pembayaran', 'Verifikasi'],'currentStep' => 1]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('form-progress'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['steps' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Isi Formulir', 'Upload Dokumen', 'Pembayaran', 'Verifikasi']),'currentStep' => 1]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
        
        <?php if($errors->any()): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <span class="text-xl mr-3">⚠️</span>
                    <div>
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mt-2 list-disc list-inside">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('pendaftaran.store')); ?>" class="space-y-8">
            <?php echo csrf_field(); ?>
            
            <!-- 1. Data Pribadi -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center mb-6">
                    <span class="text-2xl mr-3">👤</span>
                    <h3 class="text-xl font-bold text-slate-800">Data Pribadi</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">NIK <span class="text-red-500">*</span></label>
                        <input type="text" name="nik" value="<?php echo e(old('nik')); ?>" maxlength="16" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="<?php echo e(old('nama_lengkap')); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" name="tempat_lahir" value="<?php echo e(old('tempat_lahir')); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir')); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" <?php echo e(old('jenis_kelamin') == 'L' ? 'selected' : ''); ?>>Laki-laki</option>
                            <option value="P" <?php echo e(old('jenis_kelamin') == 'P' ? 'selected' : ''); ?>>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Agama <span class="text-red-500">*</span></label>
                        <select name="agama" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500" required>
                            <option value="">Pilih Agama</option>
                            <?php $__currentLoopData = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agama): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($agama); ?>" <?php echo e(old('agama') == $agama ? 'selected' : ''); ?>><?php echo e($agama); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">No. HP <span class="text-red-500">*</span></label>
                        <input type="tel" name="no_hp" value="<?php echo e(old('no_hp')); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500" required>
                    </div>
                </div>
            </div>

            <!-- 2. Alamat -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center mb-6">
                    <span class="text-2xl mr-3">🏠</span>
                    <h3 class="text-xl font-bold text-slate-800">Alamat Lengkap</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="alamat_lengkap" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500" required><?php echo e(old('alamat_lengkap')); ?></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Provinsi</label>
                            <input type="text" name="provinsi" id="provinsi" value="<?php echo e(old('provinsi')); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Kabupaten/Kota</label>
                            <input type="text" name="kabupaten" id="kabupaten" value="<?php echo e(old('kabupaten')); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Kecamatan</label>
                            <input type="text" name="kecamatan" id="kecamatan" value="<?php echo e(old('kecamatan')); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Kode Pos</label>
                            <input type="text" name="kode_pos" value="<?php echo e(old('kode_pos')); ?>" maxlength="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500">
                        </div>
                    </div>
                    
                    <!-- Geolocation Section -->
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium text-blue-900">📍 Koordinat Lokasi (Opsional)</h4>
                            <button type="button" onclick="getLocation()" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                📍 Dapatkan Lokasi
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-blue-700 mb-1">Latitude</label>
                                <input type="text" name="latitude" id="latitude" value="<?php echo e(old('latitude')); ?>" class="w-full px-3 py-2 border border-blue-300 rounded focus:ring-2 focus:ring-blue-500" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-blue-700 mb-1">Longitude</label>
                                <input type="text" name="longitude" id="longitude" value="<?php echo e(old('longitude')); ?>" class="w-full px-3 py-2 border border-blue-300 rounded focus:ring-2 focus:ring-blue-500" readonly>
                            </div>
                        </div>
                        <p class="text-xs text-blue-600 mt-2">💡 Koordinat membantu kami memetakan sebaran pendaftar dan memberikan informasi yang lebih akurat.</p>
                    </div>
                </div>
            </div>

            <!-- 3. Data Sekolah Asal -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center mb-6">
                    <span class="text-2xl mr-3">🏫</span>
                    <h3 class="text-xl font-bold text-slate-800">Data Sekolah Asal</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nama Sekolah Asal <span class="text-red-500">*</span></label>
                        <input type="text" name="asal_sekolah" value="<?php echo e(old('asal_sekolah')); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">NPSN Sekolah</label>
                        <input type="text" name="npsn_asal" value="<?php echo e(old('npsn_asal')); ?>" maxlength="8" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Alamat Sekolah Asal</label>
                        <textarea name="alamat_sekolah_asal" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500"><?php echo e(old('alamat_sekolah_asal')); ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Tahun Lulus <span class="text-red-500">*</span></label>
                        <input type="number" name="tahun_lulus" value="<?php echo e(old('tahun_lulus', date('Y'))); ?>" min="2020" max="<?php echo e(date('Y') + 1); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500" required>
                    </div>
                </div>
            </div>

            <!-- 4. Data Orang Tua -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center mb-6">
                    <span class="text-2xl mr-3">👨‍👩‍👧‍👦</span>
                    <h3 class="text-xl font-bold text-slate-800">Data Orang Tua/Wali</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Data Ayah -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-slate-700 border-b pb-2">Data Ayah</h4>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nama Ayah <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_ayah" value="<?php echo e(old('nama_ayah')); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah" value="<?php echo e(old('pekerjaan_ayah')); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Penghasilan Ayah</label>
                            <select name="penghasilan_ayah" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500">
                                <option value="">Pilih Range Penghasilan</option>
                                <option value="< 1 juta" <?php echo e(old('penghasilan_ayah') == '< 1 juta' ? 'selected' : ''); ?>>< Rp 1.000.000</option>
                                <option value="1-3 juta" <?php echo e(old('penghasilan_ayah') == '1-3 juta' ? 'selected' : ''); ?>>Rp 1.000.000 - 3.000.000</option>
                                <option value="3-5 juta" <?php echo e(old('penghasilan_ayah') == '3-5 juta' ? 'selected' : ''); ?>>Rp 3.000.000 - 5.000.000</option>
                                <option value="> 5 juta" <?php echo e(old('penghasilan_ayah') == '> 5 juta' ? 'selected' : ''); ?>>> Rp 5.000.000</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Data Ibu -->
                    <div class="space-y-4">
                        <h4 class="font-semibold text-slate-700 border-b pb-2">Data Ibu</h4>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nama Ibu <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_ibu" value="<?php echo e(old('nama_ibu')); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu" value="<?php echo e(old('pekerjaan_ibu')); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Penghasilan Ibu</label>
                            <select name="penghasilan_ibu" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500">
                                <option value="">Pilih Range Penghasilan</option>
                                <option value="< 1 juta" <?php echo e(old('penghasilan_ibu') == '< 1 juta' ? 'selected' : ''); ?>>< Rp 1.000.000</option>
                                <option value="1-3 juta" <?php echo e(old('penghasilan_ibu') == '1-3 juta' ? 'selected' : ''); ?>>Rp 1.000.000 - 3.000.000</option>
                                <option value="3-5 juta" <?php echo e(old('penghasilan_ibu') == '3-5 juta' ? 'selected' : ''); ?>>Rp 3.000.000 - 5.000.000</option>
                                <option value="> 5 juta" <?php echo e(old('penghasilan_ibu') == '> 5 juta' ? 'selected' : ''); ?>>> Rp 5.000.000</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Data Wali & Kontak -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nama Wali (jika ada)</label>
                            <input type="text" name="nama_wali" value="<?php echo e(old('nama_wali')); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Hubungan dengan Wali</label>
                            <input type="text" name="hubungan_wali" value="<?php echo e(old('hubungan_wali')); ?>" placeholder="Contoh: Paman, Bibi, Kakek" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">No. HP Orang Tua <span class="text-red-500">*</span></label>
                            <input type="tel" name="no_hp_ortu" value="<?php echo e(old('no_hp_ortu')); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. Pilihan Jurusan & Gelombang -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center mb-6">
                    <span class="text-2xl mr-3">🎓</span>
                    <h3 class="text-xl font-bold text-slate-800">Pilihan Jurusan & Gelombang</h3>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Jurusan -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-4">Jurusan yang Dipilih <span class="text-red-500">*</span></label>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $majors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $major): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                    <input type="radio" name="major_id" value="<?php echo e($major->id); ?>" class="sr-only" <?php echo e(old('major_id') == $major->id ? 'checked' : ''); ?> required>
                                    <div class="flex-1">
                                        <div class="font-semibold text-slate-800"><?php echo e($major->name); ?></div>
                                        <div class="text-sm text-slate-600">Kuota: <?php echo e($major->kuota); ?> siswa</div>
                                    </div>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    
                    <!-- Gelombang -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-4">Gelombang Pendaftaran <span class="text-red-500">*</span></label>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $waves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-emerald-300 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                                    <input type="radio" name="wave_id" value="<?php echo e($wave->id); ?>" class="sr-only" <?php echo e(old('wave_id') == $wave->id ? 'checked' : ''); ?> required>
                                    <div class="flex-1">
                                        <div class="font-semibold text-slate-800"><?php echo e($wave->nama); ?></div>
                                        <div class="text-sm text-slate-600"><?php echo e(\Carbon\Carbon::parse($wave->tgl_mulai)->format('d M Y')); ?> - <?php echo e(\Carbon\Carbon::parse($wave->tgl_selesai)->format('d M Y')); ?></div>
                                        <div class="text-sm font-semibold text-emerald-600">Rp <?php echo e(number_format($wave->biaya_daftar, 0, ',', '.')); ?></div>
                                    </div>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-between items-center pt-6">
                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.back-button','data' => ['url' => ''.e(route('dashboard')).'','text' => 'Batal','class' => 'bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('back-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => ''.e(route('dashboard')).'','text' => 'Batal','class' => 'bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                <button type="submit" id="submit-btn" class="bg-slate-600 hover:bg-slate-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-200 flex items-center disabled:opacity-50 disabled:cursor-not-allowed">
                    <span class="mr-2" id="submit-text">Daftar Sekarang</span>
                    <span>🚀</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Geolocation functions
function getLocation() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '🔄 Mencari...';
    button.disabled = true;
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lng.toFixed(6);
                
                // Get address from coordinates
                reverseGeocode(lat, lng);
                
                button.innerHTML = '✅ Berhasil';
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 2000);
            },
            function(error) {
                let errorMsg = 'Gagal mendapatkan lokasi';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMsg = 'Akses lokasi ditolak';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMsg = 'Lokasi tidak tersedia';
                        break;
                    case error.TIMEOUT:
                        errorMsg = 'Timeout mendapatkan lokasi';
                        break;
                }
                
                alert(errorMsg + '. Silakan isi koordinat manual atau coba lagi.');
                button.innerHTML = originalText;
                button.disabled = false;
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 60000
            }
        );
    } else {
        alert('Browser tidak mendukung geolocation');
        button.innerHTML = originalText;
        button.disabled = false;
    }
}

// Reverse geocoding to get address from coordinates
function reverseGeocode(lat, lng) {
    // Using Nominatim (OpenStreetMap) for reverse geocoding
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            if (data && data.address) {
                const address = data.address;
                
                // Fill address fields if empty
                if (!document.getElementById('provinsi').value && address.state) {
                    document.getElementById('provinsi').value = address.state;
                }
                
                if (!document.getElementById('kabupaten').value) {
                    const city = address.city || address.county || address.municipality || address.town;
                    if (city) {
                        document.getElementById('kabupaten').value = city;
                    }
                }
                
                if (!document.getElementById('kecamatan').value) {
                    const district = address.suburb || address.district || address.neighbourhood;
                    if (district) {
                        document.getElementById('kecamatan').value = district;
                    }
                }
            }
        })
        .catch(error => {
            console.log('Reverse geocoding failed:', error);
            // Don't show error to user, coordinates are still saved
        });
}

// Auto-fill address suggestion
function setupAddressAutocomplete() {
    const alamatInput = document.querySelector('textarea[name="alamat_lengkap"]');
    
    alamatInput.addEventListener('blur', function() {
        const address = this.value.trim();
        if (address && address.length > 10) {
            // Simple geocoding to get coordinates from address
            geocodeAddress(address);
        }
    });
}

// Geocoding to get coordinates from address
function geocodeAddress(address) {
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address + ', Indonesia')}&limit=1`)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const result = data[0];
                
                // Only fill if coordinates are empty
                if (!document.getElementById('latitude').value && !document.getElementById('longitude').value) {
                    document.getElementById('latitude').value = parseFloat(result.lat).toFixed(6);
                    document.getElementById('longitude').value = parseFloat(result.lon).toFixed(6);
                }
            }
        })
        .catch(error => {
            console.log('Geocoding failed:', error);
        });
}

// Form validation and submission
function validateForm() {
    const requiredFields = document.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('border-red-500');
            isValid = false;
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    // Validate NIK (16 digits)
    const nik = document.querySelector('input[name="nik"]');
    if (nik.value && nik.value.length !== 16) {
        nik.classList.add('border-red-500');
        alert('NIK harus 16 digit');
        isValid = false;
    }
    
    // Validate phone numbers
    const phones = document.querySelectorAll('input[type="tel"]');
    phones.forEach(phone => {
        if (phone.value && (phone.value.length < 10 || phone.value.length > 15)) {
            phone.classList.add('border-red-500');
            alert('Nomor HP tidak valid');
            isValid = false;
        }
    });
    
    return isValid;
}

// Handle form submission
document.addEventListener('DOMContentLoaded', function() {
    setupAddressAutocomplete();
    
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!validateForm()) {
            return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitText.textContent = 'Menyimpan...';
        
        // Submit form
        setTimeout(() => {
            form.submit();
        }, 500);
    });
    
    // Real-time validation
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ppdb\resources\views/pendaftaran/create.blade.php ENDPATH**/ ?>