<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembrete de Pagamento</title>
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
            background: linear-gradient(135deg, {{ $urgencyColor }}, {{ $urgencyColor }}dd);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .urgency-badge {
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
        .alert-box {
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid {{ $urgencyColor }};
        }
        .alert-overdue {
            background-color: #fef2f2;
            border-color: #dc2626;
        }
        .alert-due-today {
            background-color: #fff7ed;
            border-color: #ea580c;
        }
        .alert-urgent {
            background-color: #fffbeb;
            border-color: #f59e0b;
        }
        .alert-reminder {
            background-color: #eff6ff;
            border-color: #3b82f6;
        }
        .countdown {
            text-align: center;
            margin: 20px 0;
        }
        .countdown-number {
            font-size: 48px;
            font-weight: bold;
            color: {{ $urgencyColor }};
            display: block;
        }
        .countdown-label {
            font-size: 16px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .payment-details {
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
        .amount-highlight {
            font-size: 24px;
            font-weight: bold;
            color: {{ $urgencyColor }};
        }
        .payment-methods {
            margin: 30px 0;
        }
        .payment-methods h3 {
            color: #1f2937;
            margin-bottom: 15px;
        }
        .payment-method {
            display: flex;
            align-items: center;
            padding: 12px;
            margin-bottom: 8px;
            background-color: #f9fafb;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
        .payment-method-icon {
            font-size: 20px;
            margin-right: 12px;
            width: 30px;
            text-align: center;
        }
        .payment-method-info {
            flex: 1;
        }
        .payment-method-name {
            font-weight: 600;
            color: #374151;
        }
        .payment-method-desc {
            font-size: 12px;
            color: #6b7280;
        }
        .action-button {
            display: inline-block;
            background-color: {{ $urgencyColor }};
            color: white;
            padding: 16px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .action-button:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        .instructions {
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .instructions .icon {
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
            color: {{ $urgencyColor }};
            text-decoration: none;
        }
        .contact-info {
            background-color: #f3f4f6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        @media (max-width: 600px) {
            .detail-row {
                flex-direction: column;
                gap: 5px;
            }
            .detail-value {
                text-align: left;
            }
            .countdown-number {
                font-size: 36px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>
                @if($isOverdue)
                    🚨 Pagamento em Atraso
                @elseif($isDueToday)
                    ⏰ Pagamento Vence Hoje
                @elseif($daysUntilDue === 1)
                    ⚠️ Pagamento Vence Amanhã
                @else
                    📅 Lembrete de Pagamento
                @endif
            </h1>
            <div class="urgency-badge">
                @if($isOverdue)
                    URGENTE - EM ATRASO
                @elseif($isDueToday)
                    VENCE HOJE
                @elseif($daysUntilDue === 1)
                    VENCE AMANHÃ
                @else
                    {{ $daysUntilDue }} DIAS RESTANTES
                @endif
            </div>
        </div>

        <div class="content">
            @if(!$isOverdue)
            <div class="countdown">
                <span class="countdown-number">{{ $daysUntilDue }}</span>
                <span class="countdown-label">
                    @if($daysUntilDue === 0)
                        Vence Hoje
                    @elseif($daysUntilDue === 1)
                        Dia Restante
                    @else
                        Dias Restantes
                    @endif
                </span>
            </div>
            @endif

            <div class="alert-box alert-{{ $urgencyLevel }}">
                <div class="instructions">
                    <div class="icon">
                        @if($isOverdue)
                            🚨
                        @elseif($isDueToday)
                            ⏰
                        @elseif($daysUntilDue === 1)
                            ⚠️
                        @else
                            💡
                        @endif
                    </div>
                    <p>{{ app(App\Mail\PaymentDueAlert::class, [$orcamento, $notification, $daysUntilDue])->getPaymentInstructions() }}</p>
                </div>
            </div>

            <div class="payment-details">
                <div class="detail-row">
                    <span class="detail-label">Número do Orçamento:</span>
                    <span class="detail-value">#{{ $orcamento->numero ?? $orcamento->id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Cliente:</span>
                    <span class="detail-value">{{ $cliente->nome }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Data de Vencimento:</span>
                    <span class="detail-value">{{ $dataVencimentoFormatted }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Valor Total:</span>
                    <span class="detail-value">{{ $orcamento->valor_total_formatted }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Valor Pago:</span>
                    <span class="detail-value">{{ $orcamento->valor_pago_formatted }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Saldo Restante:</span>
                    <span class="detail-value amount-highlight">{{ $saldoRestanteFormatted }}</span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ $actionUrl }}" class="action-button">{{ $actionText }}</a>
            </div>

            <div class="payment-methods">
                <h3>Formas de Pagamento Disponíveis:</h3>
                @foreach($paymentMethods as $method)
                <div class="payment-method">
                    <div class="payment-method-icon">{{ $method['icon'] }}</div>
                    <div class="payment-method-info">
                        <div class="payment-method-name">{{ $method['name'] }}</div>
                        <div class="payment-method-desc">{{ $method['description'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="contact-info">
                <h4>Precisa de Ajuda?</h4>
                <p>Entre em contato conosco:</p>
                <p>📧 Email: contato@empresa.com</p>
                <p>📱 WhatsApp: (11) 99999-9999</p>
                <p>📞 Telefone: (11) 3333-3333</p>
            </div>
        </div>

        <div class="footer">
            <p>Esta é uma notificação automática do sistema de pagamentos.</p>
            <p>Se você não deseja mais receber estas notificações, <a href="#">clique aqui</a> para alterar suas preferências.</p>
            <p>© {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>