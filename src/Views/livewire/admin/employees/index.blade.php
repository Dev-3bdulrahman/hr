<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('hr::hr.employees') }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('hr::hr.manage_employees') }}</p>
        </div>
        <button wire:click="openModal()"
            class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-blue-500/20">
            <i data-lucide="plus" class="w-4 h-4"></i>
            {{ __('hr::hr.add_employee') }}
        </button>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-4 mb-6">
        <input wire:model.live.debounce.300ms="search" type="text"
            placeholder="{{ __('hr::hr.search_employees') }}"
            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:ring-2 focus:ring-blue-500" />
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                <tr>
                    <th class="text-start px-6 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.name') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.email') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.department') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.designation') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.salary') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.status') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                @forelse($employees as $emp)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $emp->fullName }}</td>
                    <td class="px-4 py-4 text-gray-500">{{ $emp->email ?? '—' }}</td>
                    <td class="px-4 py-4 text-gray-500">{{ $emp->department?->name ?? '—' }}</td>
                    <td class="px-4 py-4 text-gray-500">{{ $emp->designation?->name ?? '—' }}</td>
                    <td class="px-4 py-4 text-gray-500 font-mono">{{ $emp->activeContract ? number_format($emp->activeContract->salary, 2) : '—' }}</td>
                    <td class="px-4 py-4">
                        <button wire:click="toggleStatus({{ $emp->id }})"
                            class="px-2.5 py-1 text-xs font-bold rounded-full transition-all duration-200 hover:opacity-80 active:scale-95 {{ $emp->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-800' }}">
                            {{ __('hr::hr.' . $emp->status) }}
                        </button>
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-2">
                            <button wire:click="openModal({{ $emp->id }})" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </button>
                            <button 
                                wire:click="$dispatch('swal:confirm', {
                                    title: '{{ __('hr::hr.are_you_sure') }}',
                                    text: '{{ __('hr::hr.delete_confirm_text') }}',
                                    onConfirm: 'delete',
                                    params: { id: {{ $emp->id }} }
                                })"
                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                        <i data-lucide="users" class="w-12 h-12 mx-auto mb-4 text-gray-200 dark:text-gray-700"></i>
                        <p>{{ __('hr::hr.no_employees_yet') }}</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-data x-on:click.self="$wire.closeModal()">
        <div class="bg-white dark:bg-gray-900 w-full max-w-lg rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-800">
            <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-lg font-black text-gray-900 dark:text-white">{{ $employee_id ? __('hr::hr.edit_employee') : __('hr::hr.add_employee') }}</h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="px-8 py-6 space-y-4 max-h-[70vh] overflow-y-auto">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">{{ __('hr::hr.first_name') }} *</label>
                        <input wire:model="first_name" type="text" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:ring-2 focus:ring-blue-500 @error('first_name') border-red-400 @enderror" />
                        @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">{{ __('hr::hr.last_name') }} *</label>
                        <input wire:model="last_name" type="text" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:ring-2 focus:ring-blue-500 @error('last_name') border-red-400 @enderror" />
                        @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">{{ __('hr::hr.email') }}</label>
                        <input wire:model="email" type="email" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror" />
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">{{ __('hr::hr.phone') }}</label>
                        <input wire:model="phone" type="text" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-400 @enderror" />
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">{{ __('hr::hr.department') }}</label>
                        <select wire:model="department_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none">
                            <option value="">{{ __('hr::hr.select_department') }}</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">{{ __('hr::hr.designation') }}</label>
                        <select wire:model="designation_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none">
                            <option value="">{{ __('hr::hr.select_designation') }}</option>
                            @foreach($designations as $desg)
                            <option value="{{ $desg->id }}">{{ $desg->name }}</option>
                            @endforeach
                        </select>
                        @error('designation_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">{{ __('hr::hr.hire_date') }} *</label>
                        <input wire:model="hire_date" type="date" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:ring-2 focus:ring-blue-500 @error('hire_date') border-red-400 @enderror" />
                        @error('hire_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">{{ __('hr::hr.salary') }}</label>
                        <input wire:model="salary" type="number" step="0.01" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:ring-2 focus:ring-blue-500 @error('salary') border-red-400 @enderror" />
                        @error('salary') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1.5">{{ __('hr::hr.status') }}</label>
                    <select wire:model="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none">
                        <option value="active">{{ __('hr::hr.active') }}</option>
                        <option value="inactive">{{ __('hr::hr.inactive') }}</option>
                    </select>
                </div>
            </div>
            <div class="px-8 py-5 border-t border-gray-100 dark:border-gray-800 flex items-center justify-end gap-3">
                <button wire:click="closeModal" class="px-5 py-2.5 text-sm font-bold text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-all">{{ __('hr::hr.cancel') }}</button>
                <button wire:click="save" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-blue-500/20">{{ __('hr::hr.save') }}</button>
            </div>
        </div>
    </div>
    @endif
</div>
