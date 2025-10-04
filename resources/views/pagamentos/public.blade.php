<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo - {{ $pagamento->orcamento->titulo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        
        .recibo-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
        }
        
        .titulo {
            font-size: 48px;
            font-weight: 600;
            color: #333;
            letter-spacing: 2px;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            /* background-color: #ddd; */
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            /* color: #666; */
        }
        
        .info-section {
            margin-bottom: 25px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 15px;
            align-items: center;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
            min-width: 150px;
        }
        
        .info-value {
            color: #333;
            flex: 1;
        }
        
        .valor-extenso {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        
        .assinatura-section {
            margin-top: 60px;
            text-align: center;
        }
        
        .assinatura-linha {
            border-top: 2px solid #333;
            width: 300px;
            margin: 10px auto 10px;
        }
        
        .assinatura-nome {
            font-weight: bold;
            color: #333;
            margin-top: 10px;
        }
        
        .botao-imprimir {
            text-align: center;
            margin-top: 40px;
        }
        
        .btn {
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            background-color: #0056b3;
        }
        
        /* Media Queries para Mobile */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .recibo-container {
                max-width: 100%;
                padding: 20px;
                margin: 0;
                border-radius: 4px;
            }
            
            .header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .titulo {
                font-size: 32px;
                margin-bottom: 10px;
            }
            
            .logo {
                width: 60px;
                height: 60px;
            }
            
            .info-row {
                flex-direction: column;
                align-items: flex-start;
                margin-bottom: 10px;
            }
            
            .info-label {
                min-width: auto;
                margin-bottom: 5px;
            }
            
            .valor-extenso {
                padding: 10px;
                margin: 15px 0;
            }
            
            .assinatura-section {
                margin-top: 40px;
            }
            
            .assinatura-linha {
                width: 250px;
                margin: 10px auto 10px;
            }
        }
        
        @media (max-width: 480px) {
            .recibo-container {
                padding: 15px;
            }
            
            .titulo {
                font-size: 28px;
                letter-spacing: 1px;
            }
            
            .logo {
                width: 50px;
                height: 50px;
            }
            
            .assinatura-linha {
                width: 200px;
            }
            
            .btn {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .recibo-container {
                box-shadow: none;
                padding: 20px;
            }
            
            .botao-imprimir {
                display: none;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="recibo-container">
        <div class="header">
            <h1 class="titulo">RECIBO</h1>
            <div class="logo">
                @if(optional($pagamento->orcamento->cliente->user)->getLogoByType('horizontal'))
                    <img src="{{ asset('storage/' . $pagamento->orcamento->cliente->user->getLogoByType('horizontal')->caminho) }}" alt="Logo" style="max-height: 50px; max-width: 150px;">
                @elseif(optional($pagamento->orcamento->cliente->user)->name)
                    <div style="width: 50px; height: 50px; background-color: #007bff; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 20px;">
                        {{ strtoupper(substr($pagamento->orcamento->cliente->user->name, 0, 1)) }}
                    </div>
                @else
                    Logomarca
                @endif
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Número:</span>
                <span class="info-value">{{ str_pad($pagamento->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Data de Emissão:</span>
                <span class="info-value">
                    @php
                        $dataExtenso = formatarDataExtenso($pagamento->data_pagamento);
                    @endphp
                    {{ $dataExtenso }}
                </span>
            </div>
        </div>
        
        <div class="info-section">
            <p style="line-height: 1.8; font-size: 16px; color: #333;">
                Eu, <strong>{{ $pagamento->orcamento->cliente->user->name }}</strong>, 
                inscrito(a) no CPF/CNPJ sob o nº <strong>{{ optional($pagamento->orcamento->cliente->user)->cpf_cnpj ?: 'Não informado' }}</strong>, 
                declaro para os devidos fins que recebi de <strong>{{ $pagamento->orcamento->cliente->nome }}</strong>, 
                a importância de <strong>{{ $pagamento->valor_formatted }}</strong> 
                <span style="font-style: italic;">({{ $pagamento->valor_extenso }})</span>, 
                referente ao pagamento de <strong>{{ strtoupper($pagamento->forma_pagamento_formatted) }}</strong> 
                do projeto "<strong>{{ $pagamento->orcamento->titulo }}</strong>".
            </p>
        </div>
        
        @if($pagamento->observacoes)
        <div class="valor-extenso no-print">
            <strong>Observações:</strong> {{ $pagamento->observacoes }}
        </div>
        @endif
        
        <div class="assinatura-section">
            @if(optional($pagamento->orcamento->cliente->user)->assinatura_digital)
                <img src="{{ asset('storage/' . $pagamento->orcamento->cliente->user->assinatura_digital) }}" alt="Assinatura Digital" style="max-height: 80px; max-width: 200px; margin-bottom: -5px;">
                
                <div class="assinatura-linha"></div>
                <div class="assinatura-nome">{{ $pagamento->orcamento->cliente->user->name }}</div>
            @else
                <div class="assinatura-linha"></div>
                <div class="assinatura-nome">{{ optional($pagamento->orcamento->cliente->user)->name ?: 'Administrator' }}</div>
            @endif
        </div>
        
        <div class="botao-imprimir">
            <button onclick="window.print()" class="btn">Imprimir / Salvar PDF</button>
        </div>
    </div>
</body>
</html>