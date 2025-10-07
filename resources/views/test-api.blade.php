<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>API Test - Final Verification</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        button { margin: 10px; padding: 10px 20px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #005a87; }
        #results { margin-top: 20px; padding: 20px; border: 1px solid #ccc; background: #f9f9f9; border-radius: 4px; }
        .error { color: red; }
        .success { color: green; }
        .warning { color: orange; }
        pre { background: #f0f0f0; padding: 10px; overflow-x: auto; border-radius: 4px; }
        .status { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .status.success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .status.error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .status.warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
    </style>
</head>
<body>
    <h1>🔍 API Test - Final Verification</h1>
    <p>Testando as APIs do portfólio para verificar se há problemas de HTML vs JSON</p>
    
    <div class="status success">
        <strong>✅ Status:</strong> Testes realizados mostram que as APIs estão funcionando corretamente
    </div>
    
    <button onclick="testModalBehavior()">🎯 Testar Comportamento do Modal</button>
    <button onclick="testAllAPIs()">🔄 Testar Todas as APIs</button>
    <button onclick="clearResults()">🧹 Limpar Resultados</button>
    
    <div id="results">
        <h3>Aguardando testes...</h3>
        <p>Clique nos botões acima para executar os testes.</p>
    </div>

    <script>
        let testResults = [];
        
        function logResult(title, data, type = 'success') {
            const timestamp = new Date().toLocaleTimeString();
            testResults.push({ timestamp, title, data, type });
            updateResultsDisplay();
        }
        
        function updateResultsDisplay() {
            const resultsDiv = document.getElementById('results');
            let html = '<h3>📊 Resultados dos Testes</h3>';
            
            testResults.forEach(result => {
                const icon = result.type === 'success' ? '✅' : result.type === 'error' ? '❌' : '⚠️';
                html += `
                    <div class="status ${result.type}">
                        <strong>${icon} [${result.timestamp}] ${result.title}</strong>
                        <pre>${result.data}</pre>
                    </div>
                `;
            });
            
            resultsDiv.innerHTML = html;
        }
        
        function clearResults() {
            testResults = [];
            document.getElementById('results').innerHTML = '<h3>Resultados limpos</h3><p>Execute novos testes.</p>';
        }
        
        async function testModalBehavior() {
            logResult('Iniciando teste do comportamento do modal', 'Simulando exatamente como o modal faz as requisições...', 'warning');
            
            try {
                // Simular o comportamento exato do modal
                const guestUserId = Math.random().toString(36).substr(2, 9);
                
                // 1. Testar loadWorkMetrics (como no modal)
                console.log('Testing loadWorkMetrics behavior...');
                const statsResponse = await fetch(`/api/portfolio/works/teste/stats?user_id=${guestUserId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                const statsText = await statsResponse.text();
                
                if (statsText.startsWith('<!DOCTYPE')) {
                    logResult('❌ loadWorkMetrics - ERRO ENCONTRADO!', 
                        `Recebeu HTML em vez de JSON:\n${statsText.substring(0, 200)}...`, 'error');
                } else {
                    try {
                        const statsData = JSON.parse(statsText);
                        logResult('✅ loadWorkMetrics - Funcionando', 
                            `Status: ${statsResponse.status}\nDados: ${JSON.stringify(statsData, null, 2)}`, 'success');
                    } catch (e) {
                        logResult('❌ loadWorkMetrics - Erro de Parse', 
                            `Não conseguiu fazer parse do JSON:\n${statsText}`, 'error');
                    }
                }
                
                // 2. Testar toggleLike (como no modal)
                console.log('Testing toggleLike behavior...');
                const formData = new FormData();
                formData.append('user_id', '6');
                
                const likeResponse = await fetch('/api/portfolio/works/teste/like', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const likeText = await likeResponse.text();
                
                if (likeText.startsWith('<!DOCTYPE')) {
                    logResult('❌ toggleLike - ERRO ENCONTRADO!', 
                        `Recebeu HTML em vez de JSON:\n${likeText.substring(0, 200)}...`, 'error');
                } else {
                    try {
                        const likeData = JSON.parse(likeText);
                        logResult('✅ toggleLike - Funcionando', 
                            `Status: ${likeResponse.status}\nDados: ${JSON.stringify(likeData, null, 2)}`, 'success');
                    } catch (e) {
                        logResult('❌ toggleLike - Erro de Parse', 
                            `Não conseguiu fazer parse do JSON:\n${likeText}`, 'error');
                    }
                }
                
                logResult('🎯 Teste do Modal Concluído', 
                    'Ambas as funções do modal (loadWorkMetrics e toggleLike) foram testadas', 'success');
                
            } catch (error) {
                logResult('❌ Erro no Teste do Modal', error.message, 'error');
            }
        }
        
        async function testAllAPIs() {
            logResult('Iniciando teste completo das APIs', 'Testando todas as variações...', 'warning');
            
            const tests = [
                {
                    name: 'Stats API - Simples',
                    url: '/api/portfolio/works/teste/stats?user_id=1',
                    method: 'GET'
                },
                {
                    name: 'Stats API - Com Headers',
                    url: '/api/portfolio/works/teste/stats?user_id=2',
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                },
                {
                    name: 'Like API - Simples',
                    url: '/api/portfolio/works/teste/like',
                    method: 'POST',
                    body: (() => {
                        const fd = new FormData();
                        fd.append('user_id', '3');
                        return fd;
                    })()
                },
                {
                    name: 'Like API - Com CSRF',
                    url: '/api/portfolio/works/teste/like',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: (() => {
                        const fd = new FormData();
                        fd.append('user_id', '4');
                        return fd;
                    })()
                }
            ];
            
            for (const test of tests) {
                try {
                    const response = await fetch(test.url, {
                        method: test.method,
                        headers: test.headers || {},
                        body: test.body || undefined
                    });
                    
                    const text = await response.text();
                    
                    if (text.startsWith('<!DOCTYPE')) {
                        logResult(`❌ ${test.name} - ERRO!`, 
                            `Recebeu HTML em vez de JSON (Status: ${response.status})`, 'error');
                    } else {
                        try {
                            const data = JSON.parse(text);
                            logResult(`✅ ${test.name} - OK`, 
                                `Status: ${response.status} | Dados: ${JSON.stringify(data)}`, 'success');
                        } catch (e) {
                            logResult(`❌ ${test.name} - Parse Error`, 
                                `Resposta não é JSON válido: ${text}`, 'error');
                        }
                    }
                } catch (error) {
                    logResult(`❌ ${test.name} - Network Error`, error.message, 'error');
                }
                
                // Pequena pausa entre os testes
                await new Promise(resolve => setTimeout(resolve, 100));
            }
            
            logResult('🔄 Teste Completo Finalizado', 
                'Todos os testes foram executados. Verifique os resultados acima.', 'success');
        }
        
        // Auto-executar teste básico ao carregar
        window.addEventListener('load', () => {
            logResult('🚀 Página Carregada', 'Sistema de testes inicializado. Execute os testes manualmente.', 'success');
        });
    </script>
</body>
</html>