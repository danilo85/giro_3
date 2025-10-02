<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug: Usuários e Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Debug: Relacionamento Usuários e Clientes</h1>
        
        <!-- Informações do usuário logado -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
            <h2 class="text-lg font-semibold text-blue-800 mb-2">Usuário Logado Atualmente</h2>
            @auth
                <p><strong>ID:</strong> {{ auth()->id() }}</p>
                <p><strong>Nome:</strong> {{ auth()->user()->name }}</p>
                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                <p><strong>Tipo:</strong> {{ auth()->user()->tipo ?? 'N/A' }}</p>
            @else
                <p class="text-red-600">Nenhum usuário logado</p>
            @endauth
        </div>

        <!-- Tabela de Usuários -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Todos os Usuários</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tem Cliente?</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente ID</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr class="{{ auth()->id() == $user->id ? 'bg-yellow-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $user->id }}
                                @if(auth()->id() == $user->id)
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Logado
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->tipo ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($user->clientes_count > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Sim ({{ $user->clientes_count }})
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Não
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($user->clientes_list->count() > 0)
                                    @foreach($user->clientes_list as $cliente)
                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1 mb-1">
                                            {{ $cliente->id }} - {{ $cliente->nome }}
                                        </span>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabela de Clientes -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Todos os Clientes</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome do Usuário</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email do Usuário</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($clientes as $cliente)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $cliente->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cliente->nome }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cliente->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $cliente->user_id ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $cliente->user ? $cliente->user->name : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $cliente->user ? $cliente->user->email : 'N/A' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Análise de Notificações -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Análise de Notificações</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-2">Como funciona atualmente:</h3>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Notificações são criadas para o <strong>cliente.user_id</strong></li>
                            <li>• Quando um orçamento é aprovado, a notificação vai para o usuário associado ao cliente</li>
                            <li>• O usuário logado pode ser diferente do usuário que recebe a notificação</li>
                        </ul>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-green-800 mb-2">Para receber notificações:</h3>
                        <ul class="text-sm text-green-700 space-y-1">
                            <li>• Você precisa estar logado como o usuário associado ao cliente</li>
                            <li>• Ou o cliente precisa ter o seu user_id</li>
                            <li>• Verifique as preferências de notificação do usuário correto</li>
                        </ul>
                    </div>
                </div>
                
                @auth
                <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                    <h3 class="font-semibold text-yellow-800 mb-2">Situação Atual:</h3>
                    <p class="text-sm text-yellow-700">
                        Você está logado como usuário ID {{ auth()->id() }}. 
                        @if($clientes->where('user_id', auth()->id())->count() > 0)
                            <strong class="text-green-600">Você tem {{ $clientes->where('user_id', auth()->id())->count() }} cliente(s) associado(s)</strong> e deve receber notificações para orçamentos desses clientes.
                        @else
                            <strong class="text-red-600">Você não tem clientes associados</strong>, então não receberá notificações de aprovação de orçamentos.
                        @endif
                    </p>
                </div>
                @endauth
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Voltar ao Dashboard
            </a>
        </div>
    </div>
</body>
</html>