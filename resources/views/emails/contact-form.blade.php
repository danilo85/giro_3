<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Mensagem de Contato</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .field {
            margin-bottom: 20px;
        }
        .field-label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }
        .field-value {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #007bff;
        }
        .message-content {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #007bff;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .contact-info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Nova Mensagem de Contato</h1>
            <p>Recebida em {{ $contactData['sent_at'] }}</p>
        </div>

        <div class="contact-info">
            <div class="field">
                <span class="field-label">Nome:</span>
                <div class="field-value">{{ $contactData['name'] }}</div>
            </div>

            <div class="field">
                <span class="field-label">Email:</span>
                <div class="field-value">
                    <a href="mailto:{{ $contactData['email'] }}">{{ $contactData['email'] }}</a>
                </div>
            </div>

            <div class="field">
                <span class="field-label">Assunto:</span>
                <div class="field-value">{{ $contactData['subject'] }}</div>
            </div>
        </div>

        <div class="field">
            <span class="field-label">Mensagem:</span>
            <div class="message-content">{{ $contactData['message'] }}</div>
        </div>

        <div class="footer">
            <p>Esta mensagem foi enviada através do formulário de contato do seu site.</p>
            <p>Para responder, clique no email do remetente ou use o botão "Responder" do seu cliente de email.</p>
        </div>
    </div>
</body>
</html>