<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug - Sistema de Notificações</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .section {
            background: white;
            margin: 20px 0;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .section h2 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .section h3 {
            color: #555;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .status-form {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .status-form select, .status-form button {
            padding: 8px;
            margin: 5px;
        }
        .alert {
            padding: 15px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .log-entry {
            font-family: monospace;
            font-size: 12px;
            background: #f8f9fa;
            padding: 5px;
            margin: 2px 0;
            border-left: 3px solid #007bff;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-aprovado {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pendente {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-rejeitado {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Debug - Sistema de Notificações</h1>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <!-- Current User Info -->
        <div class="section">
            <h2>👤 Usuário Atual</h2>
            @if($currentUser)
                <p><strong>ID:</strong> {{ $currentUser->id }}</p>
                <p><strong>Nome:</strong> {{ $currentUser->name }}</p>
                <p><strong>Email:</strong> {{ $currentUser->email }}</p>
                <p><strong>Tipo:</strong> {{ $currentUser->type }}</p>
            @else
                <p style="color: red;">❌ Nenhum usuário logado</p>
            @endif
        </div>

        <!-- Notification Preferences -->
        <div class="section">
            <h2>⚙️ Preferências de Notificação</h2>
            @if($notificationPreferences->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Notif. Orçamento</th>
                            <th>Email</th>
                            <th>Push</th>
                            <th>SMS</th>
                            <th>Orçamento Aprovado</th>
                            <th>Orçamento Rejeitado</th>
                            <th>Pagamento Vencido</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notificationPreferences as $pref)
                            <tr>
                                <td>{{ $pref->id }}</td>
                                <td>{{ $pref->user->name ?? 'N/A' }}</td>
                                <td>{{ $pref->budget_notifications ? '✅' : '❌' }}</td>
                                <td>{{ $pref->email_enabled ? '✅' : '❌' }}</td>
                                <td>{{ $pref->push_enabled ? '✅' : '❌' }}</td>
                                <td>{{ $pref->sms_enabled ? '✅' : '❌' }}</td>
                                <td>{{ $pref->budget_approved ? '✅' : '❌' }}</td>
                                <td>{{ $pref->budget_rejected ? '✅' : '❌' }}</td>
                                <td>{{ $pref->payment_due ? '✅' : '❌' }}</td>
                                <td>
                                    @if(!$pref->budget_notifications)
                                        <a href="{{ route('enable.budget.notifications') }}" 
                                           style="background: #28a745; color: white; padding: 4px 8px; text-decoration: none; border-radius: 4px; font-size: 12px;">
                                            Habilitar
                                        </a>
                                    @else
                                        <span style="color: #28a745; font-size: 12px;">✅ Habilitado</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color: orange;">⚠️ Nenhuma preferência de notificação encontrada</p>
            @endif
        </div>

        <!-- Budgets -->
        <div class="section">
            <h2>💰 Orçamentos</h2>
            @if($orcamentos->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Usuário do Cliente</th>
                            <th>Status</th>
                            <th>Valor</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orcamentos as $orcamento)
                            <tr>
                                <td>{{ $orcamento->id }}</td>
                                <td>{{ $orcamento->cliente->nome ?? 'N/A' }}</td>
                                <td>{{ $orcamento->cliente->user->name ?? 'N/A' }} (ID: {{ $orcamento->cliente->user_id ?? 'N/A' }})</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($orcamento->status) }}">
                                        {{ $orcamento->status }}
                                    </span>
                                </td>
                                <td>R$ {{ number_format($orcamento->valor_total ?? 0, 2, ',', '.') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('debug.change-budget-status') }}" class="status-form" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="orcamento_id" value="{{ $orcamento->id }}">
                                        <select name="new_status" required>
                                            <option value="">Selecione...</option>
                                            <option value="pendente" {{ $orcamento->status == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                            <option value="aprovado" {{ $orcamento->status == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                                            <option value="rejeitado" {{ $orcamento->status == 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                                        </select>
                                        <button type="submit">Alterar Status</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color: orange;">⚠️ Nenhum orçamento encontrado</p>
            @endif
        </div>

        <!-- Recent Notifications -->
        <div class="section">
            <h2>🔔 Notificações Recentes (Últimas 20)</h2>
            @if($recentNotifications->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Tipo</th>
                            <th>Título</th>
                            <th>Mensagem</th>
                            <th>Lida</th>
                            <th>Criada em</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentNotifications as $notification)
                            <tr>
                                <td>{{ $notification->id }}</td>
                                <td>{{ $notification->user->name ?? 'N/A' }} (ID: {{ $notification->user_id }})</td>
                                <td>{{ $notification->type }}</td>
                                <td>{{ $notification->title }}</td>
                                <td>{{ Str::limit($notification->message, 50) }}</td>
                                <td>{{ $notification->read_at ? '✅' : '❌' }}</td>
                                <td>{{ $notification->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color: red;">❌ Nenhuma notificação encontrada na tabela</p>
            @endif
        </div>

        <!-- Log Entries -->
        <div class="section">
            <h2>📋 Últimas Entradas do Log</h2>
            @if(count($logLines) > 0)
                <div style="max-height: 400px; overflow-y: auto; background: #f8f9fa; padding: 10px; border-radius: 4px;">
                    @foreach($logLines as $line)
                        @if(trim($line))
                            <div class="log-entry">{{ $line }}</div>
                        @endif
                    @endforeach
                </div>
            @else
                <p style="color: orange;">⚠️ Nenhuma entrada de log encontrada</p>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="section">
            <h2>🚀 Ações Rápidas</h2>
            <p><a href="{{ route('debug.users-clients') }}" style="color: #007bff;">🔗 Ver Debug Usuários-Clientes</a></p>
            <p><a href="/" style="color: #007bff;">🏠 Voltar ao Início</a></p>
            <p><button onclick="location.reload()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">🔄 Atualizar Página</button></p>
        </div>
    </div>
</body>
</html>