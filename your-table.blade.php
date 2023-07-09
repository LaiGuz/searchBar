<div class="w-100">
    <style type="text/css">
        /* CHECKBOX TOGGLE SWITCH */
        /* @apply rules for documentation, these do not work as inline style */
        .toggle-checkbox:checked {
            @apply: right-0 border-green-400;
            right: 0;
            border-color: #68D391;
        }

        .toggle-checkbox:checked+.toggle-label {
            @apply: bg-green-400;
            background-color: #68D391;
        }

        .toggle-checkbox {
            border-color: #ff0000;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 16 16'%3e%3cpath stroke='white' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 8h8'/%3e%3c/svg%3e");

            background-color: #ff0000;
        }

        .search-box .clear {
            clear: both;
            margin-top: 20px;
        }

        .search-box ul {
            list-style: none;
            padding: 0px;
            width: 250px;
            position: absolute;
            margin: 0;
            background: white;
        }

        .search-box ul li {
            background: lavender;
            padding: 4px;
            margin-bottom: 1px;
        }

        .search-box ul li:nth-child(even) {
            background: cadetblue;
            color: white;
        }

        .search-box ul li:hover {
            cursor: pointer;
        }
    </style>
    {{-- @livewire('admin.filters.institution.institution-table') --}}
    <div class="bg-white shadow-md dark:bg-gray-800 pt-3 sm:rounded-lg">
        @if ($userVal == false)
            <div
                class="flex flex-col items-center justify-between px-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
                <div
                    class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3">
                    <div class="group flex ">
                        <button wire:click="showModalCreate()"
                            class="flex items-center justify-center w-1/2 px-5
                    py-2 text-sm tracking-wide text-white transition-colors
                    duration-200 bg-blue-500 rounded-lg sm:w-auto gap-x-2
                    hover:bg-blue-600 dark:hover:bg-blue-500 dark:bg-blue-600">
                            <svg class="h-4 w-4 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            <span>Novo </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- <@livewire('component', ['user' => $user], key($user->id))>
<livewire:voucher-table />
        --}}

        <livewire:search-bar model="App\Models\Admin\Voucher"
            relationTables="users,users.id,vouchers.user_id,plans,plans.id,vouchers.plan_id"
            columnsInclude="vouchers.id,name,email,email_verified_at,title,limit_access,vouchers.status"
            columnsNames="Id,Nome,Email,E-mail Verificado,Plano,Limite de Acesso,Status" showButtons="Opções"
            searchable="name,email" searchableDates="email_verified_at,limit_access" sort="vouchers.id|asc" />

    </div>
    {{-- MODAL DELETE --}}
    <x-confirmation-modal wire:model="showJetModal">
        <x-slot name="title">Excluir registro</x-slot>
        <x-slot name="content">
            <h2 class="h2">Deseja realmente excluir o registro?</h2>
            <p>Não será possível reverter esta ação!</p>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('showJetModal')" class="mx-2">
                Cancelar
            </x-secondary-button>
            <x-danger-button wire:click.prevent="delete({{ $registerId }})" wire.loading.attr='disable'>
                Apagar registro
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
    {{-- MODAL READ --}}
    <x-dialog-modal wire:model="showModalView">
        <x-slot name="title">Detalhes</x-slot>
        <x-slot name="content">
            <dl class="max-w text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700">
                @if ($detail)
                    @foreach ($detail as $item => $value)
                        @if ($value)
                            @if ($item == 'Voucher')
                                <div class="flex flex-col pb-1">
                                    <dt class="text-gray-500 md:text-lg dark:text-gray-400">{{ $item }}:</dt>
                                    <dd class="text-lg font-semibold flex ">
                                        <input type="text" readonly
                                            style="
                                border-color: transparent;
                                border-color: inherit;
                                width:60%;
                                "
                                            id="code" value="{{ $value }}">
                                        <button class="flex rounded-full bg-gray-200 p-2 ml-5"
                                            onclick="copyToClipboard('code')">Copiar
                                            <svg fill="#000000" class="h-8 w-8" viewBox="0 0 32 32" data-name="Layer 1"
                                                id="Layer_1" xmlns="http://www.w3.org/2000/svg">
                                                <title />
                                                <path
                                                    d="M24.89,6.61H22.31V4.47A2.47,2.47,0,0,0,19.84,2H6.78A2.47,2.47,0,0,0,4.31,4.47V22.92a2.47,2.47,0,0,0,2.47,2.47H9.69V27.2a2.8,2.8,0,0,0,2.8,2.8h12.4a2.8,2.8,0,0,0,2.8-2.8V9.41A2.8,2.8,0,0,0,24.89,6.61ZM6.78,23.52a.61.61,0,0,1-.61-.6V4.47a.61.61,0,0,1,.61-.6H19.84a.61.61,0,0,1,.61.6V6.61h-8a2.8,2.8,0,0,0-2.8,2.8V23.52Zm19,3.68a.94.94,0,0,1-.94.93H12.49a.94.94,0,0,1-.94-.93V9.41a.94.94,0,0,1,.94-.93h12.4a.94.94,0,0,1,.94.93Z" />
                                                <path
                                                    d="M23.49,13.53h-9.6a.94.94,0,1,0,0,1.87h9.6a.94.94,0,1,0,0-1.87Z" />
                                                <path
                                                    d="M23.49,17.37h-9.6a.94.94,0,1,0,0,1.87h9.6a.94.94,0,1,0,0-1.87Z" />
                                                <path
                                                    d="M23.49,21.22h-9.6a.93.93,0,1,0,0,1.86h9.6a.93.93,0,1,0,0-1.86Z" />
                                            </svg>
                                        </button>
                                    </dd>
                                </div>
                            @else
                                <div class="flex flex-col pb-1">
                                    <dt class="text-gray-500 md:text-lg dark:text-gray-400">{{ $item }}:</dt>
                                    <dd class="text-lg font-semibold">
                                        {{ $value }}
                                    </dd>
                                </div>
                            @endif
                        @endif
                    @endforeach
                @endif
            </dl>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('showModalView')" class="mx-2">
                Fechar
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>
    {{-- MODAL CREATE --}}
    <x-dialog-modal wire:model="showModalCreate">
        <x-slot name="title">Criar novo</x-slot>
        <x-slot name="content">

            <form action="#" wire:submit.prevent="store" wire.loading.attr='disable'>
                <div class="grid gap-4 mb-1 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                    <div class="sm:col-span-2">
                        <label for="user_id"
                            class="block text-sm font-medium text-gray-900 dark:text-white">Cliente</label>
                        {{-- <x-app-autocomplete-user :records={{ $records }} showdiv={{ $showdiv }} ></x-app-autocomplete-user> --}}
                        <div>

                            <div class="search-box">
                                <input type='text'
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    wire:model="userSearch" wire:keyup="searchResult">

                                <!-- Search result list -->
                                @if ($showdiv)
                                    <ul class="rounded-sm shadow-md">
                                        @if (!empty($records))
                                            @foreach ($records as $record)
                                                <li wire:click="fetchEmployeeDetail({{ $record->id }})">
                                                    {{ $record->name }}</li>
                                            @endforeach
                                        @endif
                                    </ul>
                                @endif

                                <div class="clear"></div>
                                <div>
                                    @if (!empty($empDetails))
                                        <div>
                                            Name : {{ $empDetails->name }} <br>
                                            Email : {{ $empDetails->email }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                        @error('user_id')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="plan_id"
                            class="block text-sm font-medium text-gray-900 dark:text-white">Plano</label>
                        <select wire:model="plan_id" required="" name="plan_id" id="plan_id" placeholder="Plano"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="">Selecione uma opção</option>
                            @foreach ($plans as $item)
                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                        @error('plan_id')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-span-2 sm:w-full">
                        <label for="unity" class="block text-sm font-medium text-gray-900 dark:text-white">
                            Aplicativos
                        </label>
                        <div id="tabs" class="flex justify-between">
                            <label class="w-full justify-center inline-block text-center pt-2 pb-1">
                                <input type="checkbox" value="questions" wire:model="applications"
                                    name="applications[]" class="checkbox checkbox-primary" />
                                <span class="tab tab-explore block text-xs">Questões</span>
                            </label>
                            <label class="w-full justify-center inline-block text-center pt-2 pb-1">
                                <input type="checkbox" value="treinament" wire:model="applications"
                                    name="applications[]" class="checkbox checkbox-primary" />
                                <span class="tab tab-explore block text-xs">Treinamento</span>
                            </label>
                            <label class="w-full justify-center inline-block text-center pt-2 pb-1">
                                <input type="checkbox" value="essay" wire:model="applications"
                                    name="applications[]" class="checkbox checkbox-primary" />
                                <span class="tab tab-explore block text-xs">Redação</span>
                            </label>
                            <label class="w-full justify-center inline-block text-center pt-2 pb-1">
                                <input type="checkbox" value="mentoring" wire:model="applications"
                                    name="applications[]" class="checkbox checkbox-primary" />
                                <span class="tab tab-explore block text-xs">Mentoria</span>
                            </label>
                        </div>

                        @error('applications')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex items-end space-x-4">
                    <button type="submit"
                        class="text-white
                        bg-blue-700 hover:bg-blue-800
                        focus:ring-4 focus:outline-none focus:ring-blue-300
                        font-medium rounded-lg text-sm px-5 py-2.5
                        text-center dark:bg-blue-600 dark:hover:bg-blue-700
                        dark:focus:ring-blue-800">
                        Salvar
                    </button>
                </div>
            </form>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('showModalCreate')" class="mx-2">
                Fechar
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>
    {{-- MODAL UPDATE --}}
    <x-dialog-modal wire:model="showModalEdit">
        <x-slot name="title">Editar</x-slot>
        <x-slot name="content">
            <form action="#" wire:submit.prevent="update" wire.loading.attr='disable'>
                <div class="grid gap-4 mb-1 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                    <div class="w-full ">
                        <label for="user_id"
                            class="block text-sm font-medium text-gray-900 dark:text-white">Cliente</label>
                        {{-- <x-app-autocomplete-user :records={{ $records }} showdiv={{ $showdiv }} ></x-app-autocomplete-user> --}}
                        <div>
                            <div class="search-box">
                                <div>
                                    @if (!empty($user))
                                        <div>
                                            Name : {{ $user->name }} <br>
                                            Email : {{ $user->email }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="w-full ">
                        <label for="plan_id"
                            class="block text-sm font-medium text-gray-900 dark:text-white">Ativo</label>
                        <div
                            class="relative inline-block w-10 mr-2 align-middle
                                    select-none transition duration-200 ease-in">
                            <input wire:model="status" wire:click="$toggle('hasStatus')" type="checkbox"
                                name="status"
                                class="toggle-checkbox absolute block w-6 h-6
                                    rounded-full bg-white border-4 appearance-none cursor-pointer" />
                            <label for="toggle"
                                class="toggle-label block overflow-hidden h-6 w-10
                                    rounded-full bg-gray-300 cursor-pointer"></label>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="plan_id"
                            class="block text-sm font-medium text-gray-900 dark:text-white">Plano</label>
                        <select wire:model="plan_id" required="" name="plan_id" id="plan_id"
                            placeholder="Plano"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="">Selecione uma opção</option>
                            @foreach ($plans as $item)
                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                        @error('plan_id')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex items-end space-x-4">
                    <button type="submit"
                        class="text-white
                        bg-blue-700 hover:bg-blue-800
                        focus:ring-4 focus:outline-none focus:ring-blue-300
                        font-medium rounded-lg text-sm px-5 py-2.5
                        text-center dark:bg-blue-600 dark:hover:bg-blue-700
                        dark:focus:ring-blue-800">
                        Salvar
                    </button>
                </div>
            </form>
        </x-slot>
        <x-slot name="footer">
            <x-primary-button wire:click="$toggle('showModalEdit')" class="mx-2">
                Fechar
                </x-secondary-button>
        </x-slot>
    </x-dialog-modal>
    {{-- MODAL UPDATE --}}
    <x-dialog-modal wire:model="showSendModal">
        <x-slot name="title">Enviar voucher</x-slot>
        <x-slot name="content">
            <form action="#" wire:submit.prevent="send" wire.loading.attr='disable'>
                <div class="grid gap-4 mb-1 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                    <div class="sm:col-span-2">
                        <label for="send_email" class="block text-sm font-medium text-gray-900 dark:text-white">Email
                            do aluno</label>
                        <input type="email" wire:model="send_email" name="send_email" id="send_email"
                            placeholder="Email do aluno" required=""
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @error('send_email')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex items-end space-x-4">
                    <button type="submit"
                        class="text-white
                            bg-blue-700 hover:bg-blue-800
                            focus:ring-4 focus:outline-none focus:ring-blue-300
                            font-medium rounded-lg text-sm px-5 py-2.5
                            text-center dark:bg-blue-600 dark:hover:bg-blue-700
                            dark:focus:ring-blue-800">

                        Enviar
                        @if ($send_email)
                            novamente
                        @endif
                    </button>
                </div>
            </form>
        </x-slot>
        <x-slot name="footer">
            <x-primary-button wire:click="$toggle('showSendModal')" class="mx-2">
                Fechar
                </x-secondary-button>
        </x-slot>
    </x-dialog-modal>
    <script>
        function copyToClipboard(voucher) {
            var texto = document.getElementById(voucher);
            texto.select();
            document.execCommand("copy");
        }
    </script>
</div>
