<div>
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm mb-6">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('hr::hr.departments') }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('hr::hr.manage_departments') }}</p>
        </div>
        <div class="w-full md:w-auto flex justify-end">
            <button wire:click="openModal()"
                class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-blue-500/20">
                <i data-lucide="plus" class="w-4 h-4"></i>
                {{ __('hr::hr.add_department') }}
            </button>
        </div>
    </div>

    {{-- Search --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-4 mb-6">
        <div class="relative flex items-center">
            <input wire:model.live.debounce.300ms="search" type="text"
                placeholder="{{ __('hr::hr.search_departments') }}"
                class="w-full ps-10 pe-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500" />
            <i data-lucide="search" class="absolute start-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                <tr>
                    <th class="text-start px-6 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.department_name') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.department_code') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.parent_department') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.department_manager') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.employees_count') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.status') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                @forelse($departments as $dept)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $dept->name }}</td>
                    <td class="px-4 py-4 text-gray-500 font-mono text-xs">{{ $dept->code ?? '—' }}</td>
                    <td class="px-4 py-4 text-gray-500">{{ $dept->parent?->name ?? '—' }}</td>
                    <td class="px-4 py-4 text-gray-500">{{ $dept->manager?->fullName ?? '—' }}</td>
                    <td class="px-4 py-4">
                        <span class="bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 text-xs font-bold px-2.5 py-1 rounded-full">
                            {{ $dept->employees_count }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $dept->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-800' }}">
                            {{ __('hr::hr.' . $dept->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-1">
                            <button wire:click="openModal({{ $dept->id }})" title="{{ __('hr::hr.edit_department') }}"
                                class="p-1.5 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </button>
                            <button wire:click="$dispatch('swal:confirm', {
                                    title: '{{ __('hr::hr.are_you_sure') }}',
                                    text: '{{ __('hr::hr.delete_department_confirm') }}',
                                    onConfirm: 'delete',
                                    params: { id: {{ $dept->id }} }
                                })"
                                class="p-1.5 text-gray-500 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                        <i data-lucide="building-2" class="w-12 h-12 mx-auto mb-4 text-gray-200 dark:text-gray-700"></i>
                        <p>{{ __('hr::hr.no_departments_yet') }}</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($departments->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
            {{ $departments->links() }}
        </div>
        @endif
    </div>

    {{-- Modal --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-data x-on:click.self="$wire.closeModal()">
        <div class="bg-white dark:bg-gray-900 w-full max-w-lg rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-800">
            <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-lg font-black text-gray-900 dark:text-white">
                    {{ $department_id ? __('hr::hr.edit_department') : __('hr::hr.add_department') }}
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="px-8 py-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5">{{ __('hr::hr.department_name') }} *</label>
                        <input wire:model="name" type="text"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror" />
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5">{{ __('hr::hr.department_code') }}</label>
                        <input wire:model="code" type="text"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5">{{ __('hr::hr.parent_department') }}</label>
                    <select wire:model="parent_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— {{ __('hr::hr.parent_department') }}</option>
                        @foreach($allDepartments as $d)
                            @if($d->id !== $department_id)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5">{{ __('hr::hr.department_manager') }}</label>
                    <select wire:model="manager_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— {{ __('hr::hr.department_manager') }}</option>
                        @foreach($managers as $mgr)
                        <option value="{{ $mgr->id }}">{{ $mgr->fullName }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5">{{ __('hr::hr.status') }}</label>
                    <select wire:model="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-white outline-none">
                        <option value="active">{{ __('hr::hr.active') }}</option>
                        <option value="inactive">{{ __('hr::hr.inactive') }}</option>
                    </select>
                </div>
            </div>
            <div class="px-8 py-5 border-t border-gray-100 dark:border-gray-800 flex items-center justify-end gap-3">
                <button wire:click="closeModal" class="px-5 py-2.5 text-sm font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-all">
                    {{ __('hr::hr.cancel') }}
                </button>
                <button wire:click="save" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-blue-500/20">
                    {{ __('hr::hr.save') }}
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
