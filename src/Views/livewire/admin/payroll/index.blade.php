<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('hr::hr.payrolls') }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('hr::hr.manage_payroll') }}</p>
        </div>
        <button wire:click="openModal()"
            class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-blue-500/20">
            <i data-lucide="play" class="w-4 h-4"></i>
            {{ __('hr::hr.generate_payroll') }}
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
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.month_year') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.basic_salary') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.allowances') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.deductions') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.net_salary') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.status') }}</th>
                    <th class="text-start px-4 py-4 font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-[10px]">{{ __('hr::hr.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                @forelse($payrolls as $pr)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $pr->employee->fullName }}</td>
                    <td class="px-4 py-4 text-gray-500 font-mono">{{ sprintf('%02d', $pr->month) }}/{{ $pr->year }}</td>
                    <td class="px-4 py-4 text-gray-500 font-mono">{{ number_format($pr->basic_salary, 2) }}</td>
                    <td class="px-4 py-4 text-gray-500 font-mono text-green-600">+{{ number_format($pr->allowances, 2) }}</td>
                    <td class="px-4 py-4 text-gray-500 font-mono text-red-600">-{{ number_format($pr->deductions, 2) }}</td>
                    <td class="px-4 py-4 font-bold text-gray-900 dark:text-white font-mono">{{ number_format($pr->net_salary, 2) }}</td>
                    <td class="px-4 py-4">
                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $pr->status === 'paid' ? 'bg-green-50 text-green-700 dark:bg-green-900/20' : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20' }}">
                            {{ __('hr::hr.' . $pr->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        @if($pr->status === 'draft')
                        <button wire:click="pay({{ $pr->id }})" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-all shadow-sm shadow-blue-500/10">
                            {{ __('hr::hr.pay') }}
                        </button>
                        @else
                        <span class="text-gray-400 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center text-gray-400">
                        <i data-lucide="wallet" class="w-12 h-12 mx-auto mb-4 text-gray-200 dark:text-gray-700"></i>
                        <p>{{ __('hr::hr.no_payroll_yet') }}</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100 dark:border-gray-800">
            {{ $payrolls->links() }}
        </div>
    </div>

    {{-- Modal --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-data x-on:click.self="$wire.closeModal()">
        <div class="bg-white dark:bg-gray-900 w-full max-w-sm rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-800">
            <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-lg font-black text-gray-900 dark:text-white">{{ __('hr::hr.generate_payroll') }}</h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="px-8 py-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1.5">{{ __('hr::hr.month') }} *</label>
                    <select wire:model="month" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none">
                        @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}">{{ sprintf('%02d', $m) }}</option>
                        @endfor
                    </select>
                    @error('month') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1.5">{{ __('hr::hr.year') }} *</label>
                    <select wire:model="year" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm outline-none">
                        @for($y = (int)now()->format('Y') - 2; $y <= (int)now()->format('Y') + 2; $y++)
                        <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                    @error('year') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="px-8 py-5 border-t border-gray-100 dark:border-gray-800 flex items-center justify-end gap-3">
                <button wire:click="closeModal" class="px-5 py-2.5 text-sm font-bold text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-all">{{ __('hr::hr.cancel') }}</button>
                <button wire:click="generate" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-blue-500/20">{{ __('hr::hr.generate') }}</button>
            </div>
        </div>
    </div>
    @endif
</div>
