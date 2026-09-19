<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quotation List') }}
        </h2>
    </x-slot>

    <div class="mt-4 shadow-lg">
        <div class="bg-gray-400 rounded-t-lg">
            <div x-data="{
                open: false,
                jobType: '',
                polMode: 'select',
                podMode: 'select',
                init() {
                    this.jobType = String($wire.get('job_type') ?? '');
                },
                openModal() {
                    this.open = true;
                    this.$nextTick(() => {
                        window.reinitClientSelect2();
                        if (this.jobType) window.PortSelect2?.initAll(this.jobType);
                    });
                },
                setJobType(val) {
                    this.jobType = String(val ?? '');
                    if (this.jobType) {
                        this.$nextTick(() => window.PortSelect2?.initAll(this.jobType));
                    }
                }
            }" @keydown.escape.window="open = false" @close-transaction-modal.window="open = false">

                {{-- Header Bar --}}
                <div class="flex items-center justify-between p-3">
                    <div class="flex-1"></div>
                    <p class="font-bold text-center">TRANSACTION</p>
                    <div class="flex-1 flex justify-end gap-2">
                        <button @click="openModal()" class="py-2 px-3 bg-blue-600 text-white rounded-lg text-sm">
                            Add Cost
                        </button>
                    </div>
                </div>

                {{-- Overlay --}}
                <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-300 delay-150"
                    x-transition:leave="transition ease-in duration-200"
                    class="fixed inset-0 bg-gray-500 bg-opacity-50 z-40">
                </div>

                {{-- Modal --}}
                <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="scale-90 opacity-0" x-transition:enter-end="scale-100 opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-90 opacity-0"
                    class="fixed inset-0 flex items-center justify-center z-50 px-4">

                    <div class="bg-white rounded-2xl shadow-md w-full max-w-7xl">

                        {{-- Modal Header --}}
                        <div class="relative flex items-center p-4 border-b">
                            <h2 class="absolute left-1/2 -translate-x-1/2 text-lg font-semibold text-gray-800">
                                Quotation
                            </h2>
                            <button @click="open = false"
                                class="ml-auto text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {{-- Form Body --}}
                        <div class="p-4 w-full">

                            {{-- Row 1: Quotation Number, Job Type, Inco Terms --}}
                            <div class="grid grid-cols-3 gap-4 mb-4">

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Quotation Number
                                    </label>
                                    <input type="text" wire:model="quotation_number"
                                        class="w-full border rounded-md border-gray-300 p-2 h-10">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Job Type
                                    </label>
                                    <select wire:model.live="job_type" @change="setJobType($event.target.value)"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                        <option value="">Select Job Type</option>
                                        <option value="ocean_fcl_export">Ocean FCL Export</option>
                                        <option value="ocean_fcl_import">Ocean FCL Import</option>
                                        <option value="ocean_lcl_export">Ocean LCL Export</option>
                                        <option value="ocean_lcl_import">Ocean LCL Import</option>
                                        <option value="air_export">Air Export</option>
                                        <option value="air_import">Air Import</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Inco Terms
                                    </label>
                                    <select wire:model="inco_terms"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                        <option value="">Select Terms</option>
                                        <option value="FOB">FOB</option>
                                        <option value="CFR">CFR</option>
                                        <option value="CIF">CIF</option>
                                        <option value="CPT">CPT</option>
                                        <option value="CIP">CIP</option>
                                        <option value="FAS">FAS</option>
                                    </select>
                                </div>

                            </div>

                            {{-- Row 2: Customer, POL, POD --}}
                            <div class="grid grid-cols-3 gap-4 mb-4">

                                {{-- Customer Name --}}
                                <div class="flex flex-col justify-end" wire:ignore>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        Customer Name
                                    </label>
                                    <select id="select-client" wire:model="sclient"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                        <option value="">-- Pilih Client --</option>
                                    </select>
                                </div>

                                {{-- Port Of Loading --}}
                                <div class="flex flex-col">
                                    <h2 class="text-sm font-semibold text-gray-700 mb-1">
                                        Port Of Loading / POL
                                    </h2>

                                    <div class="flex items-center gap-3 h-5 mb-2">
                                        <label class="text-sm"
                                            :class="!jobType ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer'">
                                            <input type="radio" value="select" x-model="polMode" :disabled="!jobType"
                                                @change="$nextTick(() => window.PortSelect2?.initSingle('.port-select-pol', 'port_of_loading', jobType))"
                                                class="mr-1">
                                            Select from List
                                        </label>
                                        <label class="text-sm"
                                            :class="!jobType ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer'">
                                            <input type="radio" value="input" x-model="polMode" :disabled="!jobType"
                                                class="mr-1">
                                            Enter Manually
                                        </label>
                                    </div>

                                    {{-- Select Mode --}}
                                    <div x-show="polMode === 'select'" class="relative">
                                        <div x-show="!jobType"
                                            class="absolute inset-0 z-10 bg-white bg-opacity-70 rounded-md cursor-not-allowed">
                                        </div>
                                        <div wire:ignore>
                                            <select wire:model="port_of_loading"
                                                class="port-select-pol block w-full rounded-md border border-gray-300 p-2 h-10 shadow-sm">
                                                <option value="">Select a port...</option>
                                            </select>
                                        </div>
                                        <p x-show="!jobType" class="text-xs text-amber-600 mt-1">
                                            Pilih Job Type terlebih dahulu
                                        </p>
                                    </div>

                                    {{-- Manual Mode --}}
                                    <div x-show="polMode === 'input'" class="relative">
                                        <div x-show="!jobType"
                                            class="absolute inset-0 z-10 bg-white bg-opacity-70 rounded-md cursor-not-allowed">
                                        </div>
                                        <input wire:model.live="port_of_loading" type="text" :disabled="!jobType"
                                            class="block w-full rounded-md border border-gray-300 p-2 h-10 shadow-sm focus:ring focus:ring-blue-200"
                                            :class="!jobType ? 'bg-gray-100 cursor-not-allowed' : ''"
                                            placeholder="Enter port name">
                                        <p x-show="!jobType" class="text-xs text-amber-600 mt-1">
                                            Pilih Job Type terlebih dahulu
                                        </p>
                                    </div>
                                </div>

                                {{-- Port Of Discharge --}}
                                <div class="flex flex-col">
                                    <h2 class="text-sm font-semibold text-gray-700 mb-1">
                                        Port Of Discharge / POD
                                    </h2>

                                    <div class="flex items-center gap-3 h-5 mb-2">
                                        <label class="text-sm"
                                            :class="!jobType ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer'">
                                            <input type="radio" value="select" x-model="podMode" :disabled="!jobType"
                                                @change="$nextTick(() => window.PortSelect2?.initSingle('.port-select-pod', 'port_of_discharge', jobType))"
                                                class="mr-1">
                                            Select from List
                                        </label>
                                        <label class="text-sm"
                                            :class="!jobType ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer'">
                                            <input type="radio" value="input" x-model="podMode" :disabled="!jobType"
                                                class="mr-1">
                                            Enter Manually
                                        </label>
                                    </div>

                                    {{-- Select Mode --}}
                                    <div x-show="podMode === 'select'" class="relative">
                                        <div x-show="!jobType"
                                            class="absolute inset-0 z-10 bg-white bg-opacity-70 rounded-md cursor-not-allowed">
                                        </div>
                                        <div wire:ignore>
                                            <select wire:model="port_of_discharge"
                                                class="port-select-pod block w-full rounded-md border border-gray-300 p-2 h-10 shadow-sm">
                                                <option value="">Select a port...</option>
                                            </select>
                                        </div>
                                        <p x-show="!jobType" class="text-xs text-amber-600 mt-1">
                                            Pilih Job Type terlebih dahulu
                                        </p>
                                    </div>

                                    {{-- Manual Mode --}}
                                    <div x-show="podMode === 'input'" class="relative">
                                        <div x-show="!jobType"
                                            class="absolute inset-0 z-10 bg-white bg-opacity-70 rounded-md cursor-not-allowed">
                                        </div>
                                        <input wire:model.live="port_of_discharge" type="text" :disabled="!jobType"
                                            class="block w-full rounded-md border border-gray-300 p-2 h-10 shadow-sm focus:ring focus:ring-blue-200"
                                            :class="!jobType ? 'bg-gray-100 cursor-not-allowed' : ''"
                                            placeholder="Enter port name">
                                        <p x-show="!jobType" class="text-xs text-amber-600 mt-1">
                                            Pilih Job Type terlebih dahulu
                                        </p>
                                    </div>
                                </div>

                            </div>

                            {{-- Valid Until --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Valid Until</label>
                                <input type="date" wire:model="valid_until"
                                    class="border rounded-md border-gray-300 p-2 h-10">
                            </div>

                        </div>
                        {{-- end form body --}}

                    </div>
                </div>
                {{-- end modal --}}

            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="table-hover min-w-full divide-y divide-gray-200 dark:divide-neutral-700 text-center">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-xs font-bold text-gray-700 uppercase">No</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-700 uppercase"></th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-700 uppercase">Description</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-700 uppercase">Unit</th>
                        <th class="px-6 py-3 bg-orange-500 text-xs font-bold text-gray-700 uppercase">Client</th>
                        <th class="px-6 py-3 bg-orange-500 text-xs font-bold text-gray-700 uppercase">Sale</th>
                        <th class="px-6 py-3 bg-orange-500 text-xs whitespace-nowrap font-bold text-gray-700 uppercase">
                            Amount (IDR)</th>
                        <th class="px-6 py-3 bg-orange-500 text-xs font-bold text-gray-700 uppercase">Dr/Cr</th>
                        <th class="px-6 py-3 bg-blue-500 text-xs font-bold text-gray-700 uppercase">Vendor</th>
                        <th class="px-6 py-3 bg-blue-500 text-xs font-bold text-gray-700 uppercase">Cost</th>
                        <th class="px-6 py-3 bg-blue-500 text-xs whitespace-nowrap font-bold text-gray-700 uppercase">
                            Amount (IDR)</th>
                        <th class="px-6 py-3 bg-blue-500 text-xs font-bold text-gray-700 uppercase">Dr/Cr</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-700 uppercase">Freight</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-700 uppercase">Gross Profit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                            <div class="flex items-center space-x-3">
                                <div x-data>
                                    <button
                                        class="px-3 py-2 bg-red-600 text-white rounded-full hover:scale-105 hover:bg-red-700 transition-transform"
                                        @click="Swal.fire({
                                            title: 'Are you sure?',
                                            text: 'You won\'t be able to revert this!',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d8',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'Yes, delete it!',
                                            cancelButtonText: 'No, Keep it',
                                        }).then((result) => {
                                            if (result.isConfirmed) $wire.confirmDelete();
                                        })">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                                <button type="button" wire:click="editTransaction()"
                                    class="px-3 py-2 bg-blue-500 rounded-full text-white hover:bg-blue-600 transition transform hover:scale-105">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr wire:loading.remove>
                        <td colspan="14" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <img src="{{ asset('images/nodata.svg') }}" alt="No data"
                                    class="w-64 h-48 mb-4 opacity-75 dark:opacity-50">
                            </div>
                        </td>
                    </tr>
                    <tr wire:loading class="animate-pulse">
                        <td colspan="14" class="py-12 text-center text-gray-500 dark:text-neutral-400">
                            Retrieving data…
                        </td>
                    </tr>
                </tbody>
            </table>

            <x-confirm-delete :message="'Are you sure you want to delete this transaction?'"
                :key="'confirm-delete-job-transaction-' . now()->timestamp" />
        </div>
    </div>
</div>

@push('scripts')
@script()
<script>
    // =============================================
    // CLIENT SELECT2
    // =============================================
    window.reinitClientSelect2 = () => {
        const $el = $('#select-client');
        if (!$el.length) return;

        if ($el.hasClass('select2-hidden-accessible')) {
            $el.select2('destroy');
        }

        $el.select2({
            placeholder: 'Search client...',
            allowClear: true,
            theme: 'tailwindcss-3',
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: '/data/clients-ajax',
                dataType: 'json',
                delay: 300,
                data: params => ({
                    q: params.term || '',
                    page: params.page || 1
                }),
                processResults: data => ({
                    results: data.results,
                    pagination: { more: data.pagination?.more ?? false }
                }),
                cache: true
            }
        });

        // Restore nilai existing dari $wire
        const currentVal = $wire.get('sclient');
        if (currentVal) {
            if ($el.find(`option[value="${currentVal}"]`).length === 0) {
                const option = new Option(currentVal, currentVal, true, true);
                $el.append(option);
            }
            $el.val(currentVal).trigger('change.select2');
        }

        $el.off('change.lw').on('change.lw', function () {
            $wire.set('sclient', $(this).val());
        });
    };

    // =============================================
    // PORT SELECT2
    // =============================================
    window.PortSelect2 = {
        initSingle(selector, model, type_job) {
            const jobStr = String(type_job ?? '');
            if (!jobStr) return;

            const isAir = ['air', 'logistics', 'domestics_transport', 'trucking']
                .some(prefix => jobStr.startsWith(prefix));

            const endpoint = isAir ? '/data/airports-ajax' : '/data/ports.json';
            const $el = $(selector);
            if (!$el.length) return;

            if ($el.hasClass('select2-hidden-accessible')) {
                $el.select2('destroy');
            }

            if (isAir) {
                $el.select2({
                    placeholder: 'Select airport...',
                    allowClear: true,
                    theme: 'tailwindcss-3',
                    width: '100%',
                    minimumInputLength: 3,
                    ajax: {
                        url: endpoint,
                        dataType: 'json',
                        delay: 300,
                        data: params => ({ q: params.term || '', page: params.page || 1 }),
                        processResults: data => ({
                            results: data.results,
                            pagination: { more: data.pagination?.more ?? false }
                        }),
                        cache: true
                    }
                });
            } else {
                fetch(endpoint)
                    .then(res => res.json())
                    .then(data => {
                        $el.select2({
                            placeholder: 'Select port...',
                            allowClear: true,
                            theme: 'tailwindcss-3',
                            width: '100%',
                            minimumInputLength: 2,
                            ajax: {
                                transport(params, success) {
                                    const term = params.data.term?.toLowerCase() || '';
                                    const results = data
                                        .filter(p =>
                                            p.name?.toLowerCase().includes(term) ||
                                            p.code?.toLowerCase().includes(term) ||
                                            p.country?.toLowerCase().includes(term)
                                        )
                                        .slice(0, 20)
                                        .map(p => ({
                                            id: `${p.name}, ${p.country}`,
                                            text: `${p.name} (${p.code}) - ${p.country}`
                                        }));
                                    success({ results });
                                },
                                delay: 250
                            }
                        });
                    });
            }

            $el.off('change.lw').on('change.lw', function () {
                $wire.set(model, $(this).val());
            });
        },

        initAll(type_job) {
            const jobStr = String(type_job ?? '');
            if (!jobStr) return;
            this.initSingle('.port-select-pol', 'port_of_loading', jobStr);
            this.initSingle('.port-select-pod', 'port_of_discharge', jobStr);
        }
    };

    // =============================================
    // LIVEWIRE INIT — pola sama dengan file kedua
    // =============================================
    document.addEventListener('livewire:init', () => {
        window.reinitClientSelect2();

        const jobType = String($wire.get('job_type') ?? '');
        if (jobType) window.PortSelect2.initAll(jobType);

        Livewire.hook('message.processed', () => {
            window.reinitClientSelect2();

            const jobType = String($wire.get('job_type') ?? '');
            if (jobType) window.PortSelect2.initAll(jobType);
        });
    });
</script>
@endscript
@endpush