<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status do Orçamento Alterado</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, {{ $statusColor }}, {{ $statusColor }}dd);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header .status-badge {
            background-color: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 10px;
            font-size: 14px;
            font-weight: 500;
        }
        .content {
            padding: 40px 30px;
        }
        .status-change {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid {{ $statusColor }};
        }
        .status-change h3 {
            margin: 0 0 10px 0;
            color: #1f2937;
            font-size: 18px;
        }
        .status-transition {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 15px 0;
            font-size: 16px;
        }
        .old-status {
            background-color: #e5e7eb;
            color: #6b7280;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 500;
        }
        .arrow {
            margin: 0 15px;
            color: #9ca3af;
            font-size: 20px;
        }
        .new-status {
            background-color: {{ $statusColor }};
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 500;
        }
        .orcamento-details {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .detail-label {
            font-weight: 600;
            color: #374151;
        }
        .detail-value {
            color: #6b7280;
            text-align: right;
        }
        .action-button {
            display: inline-block;
            background-color: {{ $statusColor }};
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        .action-button:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        .message-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .message-box .icon {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
        }
        .footer a {
            color: {{ $statusColor }};
            text-decoration: none;
        }
        @media (max-width: 600px) {
            .status-transition {
                flex-direction: column;
                gap: 10px;
            }
            .arrow {
                transform: rotate(90deg);
            }
            .detail-row {
                flex-direction: column;
                gap: 5px;
            }
            .detail-value {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ $notification->title }}</h1>
            <div class="status-badge">{{ $statusLabel }}</div>
        </div>

        <div class="content">
            <div class="status-change">
                <h3>Mudança de Status</h3>
                <div class="status-transition">
                    <span class="old-status">{{ $oldStatusLabel }}</span>
                    <span class="arrow">→</span>
                    <span class="new-status">{{ $statusLabel }}</span>
                </div>
            </div>

            <div class="orcamento-details">
                <div class="detail-row">
                    <span class="detail-label">Número do Orçamento:</span>
                    <span class="detail-value">#{{ $orcamento->numero ?? $orcamento->id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Cliente:</span>
                    <span class="detail-value">{{ $cliente->nome }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Valor Total:</span>
                    <span class="detail-value">{{ $orcamento->valor_total_formatted }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Data da Alteração:</span>
                    <span class="detail-value">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="message-box">
                @if($newStatus === App\Models\Orcamento::STATUS_APROVADO)
                    <div class="icon">🎉</div>
                    <p><strong>Parabéns!</strong> Seu orçamento foi aprovado e o projeto já pode ser iniciado. Nossa equipe entrará em contato em breve para alinhar os próximos passos.</p>
                @elseif($newStatus === App\Models\Orcamento::STATUS_REJEITADO)
                    <div class="icon">😔</div>
                    <p>Infelizmente seu orçamento foi rejeitado. Entre em contato conosco para entender os motivos e discutir possíveis ajustes.</p>
                @elseif($newStatus === App\Models\Orcamento::STATUS_QUITADO)
                    <div class="icon">💰</div>
                    <p><strong>Obrigado!</strong> Seu orçamento foi quitado com sucesso. Agradecemos pela confiança em nossos serviços!</p>
                @else
                    <div class="icon">📋</div>
                    <p>{{ $notification->message }}</p>
                @endif
            </div>

            <div style="text-align: center;">
                <a href="{{ $actionUrl }}" class="action-button">{{ $actionText }}</a>
            </div>
        </div>

        <div class="footer">
            <p>Esta é uma notificação automática do sistema de orçamentos.</p>
            <p>Se você não deseja mais receber estas notificações, <a href="#">clique aqui</a> para alterar suas preferências.</p>
            <p>© {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>