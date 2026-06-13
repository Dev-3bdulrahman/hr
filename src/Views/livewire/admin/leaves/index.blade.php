<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('hr::hr.leave_requests') }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('hr::hr.manage_leaves') }}</p>
        </div>
        <button wire:click="openModal()"
            class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-blue-500/20">
            <i data-lucide="plus" class="w-4 h-4"></i>
            {{ __('hr::hr.request_leave') }}
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
                    <th class="text-start px-6 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.employee') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.leave_type') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.start_date') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.end_date') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.status') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                @forelse($leaves as $lv)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $lv->employee->fullName }}</td>
                    <td class="px-4 py-4 text-gray-500">{{ $lv->leaveType->name }}</td>
                    <td class="px-4 py-4 text-gray-500">{{ $lv->start_date->toDateString() }}</td>
                    <td class="px-4 py-4 text-gray-500">{{ $lv->end_date->toDateString() }}</td>
                    <td class="px-4 py-4">
                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $lv->status === 'approved' ? 'bg-green-50 text-green-700 dark:bg-green-900/20' : ($lv->status === 'pending' ? 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20' : 'bg-red-50 text-red-700 dark:bg-red-900/20') }}">
                            {{ __('hr::hr.' . $lv->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        @if($lv->status === 'pending')
                        <div class="flex items-center gap-2">
                            <button wire:click="approve({{ $lv->id }})" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg transition-all shadow-sm shadow-green-500/10">
                                {{ __('hr::hr.approve') }}
                            </button>
                            <button wire:click="reject({{ $lv->id }})" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-all shadow-sm shadow-red-500/10">
                                {{ __('hr::hr.reject') }}
                            </button>
                        </div>
                        @else
                        <span class="text-gray-400 text-xs">{{ $lv->approver?->name ?? '—' }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                        <i data-lucide="plane" class="w-12 h-12 mx-auto mb-4 text-gray-200 dark:text-gray-700"></i>
                        <p>{{ __('hr::hr.no_leaves_yet') }}</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100 dark:border-gray-800">
            {{ $leaves->links() }}
        </div>
    </div>

    {{-- Modal --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-data x-on:click.self="$wire.closeModal()">
        <div class="bg-white dark:bg-gray-900 w-full max-w-lg rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-800">
            <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-lg font-black text-gray-900 dark:text-white">{{ __('hr::hr.request_leave') }}</h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="px-8 py-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1.5">{{ __('hr::hr.employee') }} *</label>
                    <select wire:model="employee_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none">
                        <option value="">{{ __('hr::hr.select_employee') }}</option>
                        @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->fullName }}</option>
                        @endforeach
                    </select>
                    @error('employee_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1.5">{{ __('hr::hr.leave_type') }} *</label>
                    <select wire:model="leave_type_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none">
                        <option value="">{{ __('hr::hr.select_leave_type') }}</option>
                        @foreach($leaveTypes as $lt)
                        <option value="{{ $lt->id }}">{{ $lt->name }}</option>
                        @endforeach
                    </select>
                    @error('leave_type_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">{{ __('hr::hr.start_date') }} *</label>
                        <input wire:model="start_date" type="date" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">{{ __('hr::hr.end_date') }} *</label>
                        <input wire:model="end_date" type="date" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:ring-2 focus:ring-blue-500" />
                        @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1.5">{{ __('hr::hr.reason') }}</label>
                    <textarea wire:model="reason" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    @error('reason') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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
