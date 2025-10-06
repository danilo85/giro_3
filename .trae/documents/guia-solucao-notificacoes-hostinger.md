# Guia de Solução: Sistema de Notificações na Hostinger

## 🔍 Problema Identificado
As notificações funcionam perfeitamente no ambiente local, mas não funcionam na Hostinger. Este guia fornece soluções específicas para resolver os problemas mais comuns.

## 📋 Diagnóstico dos Problemas

### 1. **Cron Jobs não Configurados**
- **Sintoma**: Notificações automáticas não são enviadas
- **Causa**: O comando `payments:check-due-dates` não está sendo executado automaticamente

### 2. **Configuração de Queue Inadequada**
- **Sintoma**: Emails ficam "presos" na fila ou falham silenciosamente
- **Causa**: `PaymentDueAlert` implementa `ShouldQueue` mas queue pode estar mal configurada

### 3. **Configurações de Email Incorretas**
- **Sintoma**: Emails não são enviados
- **Causa**: Configurações SMTP diferentes entre local e produção

### 4. **Cache Desatualizado**
- **Sintoma**: Comandos ou rotas não funcionam
- **Causa**: Cache de configurações e rotas desatualizado

## 🛠️ Soluções Passo a Passo

### **ETAPA 1: Configurar Cron Jobs na Hostinger**

#### 1.1 Acesse o painel da Hostinger
1. Faça login no painel da Hostinger
2. Vá para **Hosting** → **Gerenciar**
3. Procure por **Cron Jobs** ou **Tarefas Agendadas**

#### 1.2 Adicione o Cron Job do Laravel
```bash
# Comando para adicionar no cron job da Hostinger:
* * * * * cd /home/u123456789/domains/seudominio.com/public_html && php artisan schedule:run >> /dev/null 2>&1
```

**Importante**: Substitua o caminho pelo caminho real do seu projeto na Hostinger.

#### 1.3 Verificar se o comando específico funciona
```bash
# Teste manual do comando de notificações:
php artisan notifications:check-payment-due-dates --dry-run
```

### **ETAPA 2: Configurar Queue System**

#### 2.1 Verificar configuração atual
```bash
# Verificar configuração de queue:
php artisan config:show queue
```

#### 2.2 Configurar Queue para Database (Recomendado para Hostinger)
No arquivo `.env` da produção:
```env
QUEUE_CONNECTION=database
```

#### 2.3 Criar tabelas de queue (se necessário)
```bash
php artisan queue:table
php artisan migrate
```

#### 2.4 Processar queue manualmente (teste)
```bash
# Processar jobs na queue:
php artisan queue:work --once
```

### **ETAPA 3: Verificar e Corrigir Configurações de Email**

#### 3.1 Verificar configurações SMTP no .env
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@seudominio.com
MAIL_PASSWORD=sua-senha
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu-email@seudominio.com
MAIL_FROM_NAME="Nome da Empresa"
```

#### 3.2 Testar envio de email
```bash
# Comando para testar email:
php artisan tinker
```

Dentro do tinker:
```php
Mail::raw('Teste de email', function ($message) {
    $message->to('seu-email@teste.com')
            ->subject('Teste Hostinger');
});
```

### **ETAPA 4: Limpar Cache e Configurações**

#### 4.1 Comandos de limpeza essenciais
```bash
# Limpar todos os caches:
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recriar caches otimizados:
php artisan config:cache
php artisan route:cache
```

#### 4.2 Verificar permissões
```bash
# Ajustar permissões:
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

### **ETAPA 5: Configurar Worker de Queue (Importante)**

#### 5.1 Criar script de worker
Crie um arquivo `queue-worker.sh`:
```bash
#!/bin/bash
cd /home/u123456789/domains/seudominio.com/public_html
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```

#### 5.2 Adicionar worker como cron job
```bash
# Adicionar no cron da Hostinger para manter worker ativo:
*/5 * * * * /home/u123456789/domains/seudominio.com/queue-worker.sh
```

### **ETAPA 6: Testes e Verificações**

#### 6.1 Testar comando de notificações manualmente
```bash
# Teste com dry-run:
php artisan notifications:check-payment-due-dates --dry-run

# Teste real:
php artisan notifications:check-payment-due-dates
```

#### 6.2 Verificar logs
```bash
# Verificar logs de erro:
tail -f storage/logs/laravel.log

# Verificar logs específicos de email:
grep -i "mail\|email" storage/logs/laravel.log
```

#### 6.3 Testar criação manual de notificação
```bash
php artisan tinker
```

```php
// Criar notificação de teste:
$user = App\Models\User::first();
$notification = App\Models\Notification::create([
    'user_id' => $user->id,
    'type' => 'test',
    'title' => 'Teste de Notificação',
    'message' => 'Esta é uma notificação de teste',
    'data' => []
]);
```

## 🔧 Configurações Específicas da Hostinger

### **Configuração de PHP**
Verifique se o PHP está configurado corretamente:
```bash
# Verificar versão do PHP:
php -v

# Verificar extensões necessárias:
php -m | grep -E "(pdo|mysql|curl|openssl|mbstring)"
```

### **Configuração de Timezone**
No arquivo `.env`:
```env
APP_TIMEZONE=America/Sao_Paulo
```

### **Configuração de URL**
```env
APP_URL=https://seudominio.com
```

## 🚨 Problemas Comuns e Soluções

### **Problema 1: "Class not found" errors**
```bash
# Solução:
composer dump-autoload
php artisan clear-compiled
```

### **Problema 2: Emails não chegam**
1. Verificar se o domínio tem SPF/DKIM configurado
2. Testar com email externo (Gmail, etc.)
3. Verificar pasta de spam

### **Problema 3: Queue jobs ficam "stuck"**
```bash
# Limpar jobs failed:
php artisan queue:flush

# Reiniciar queue:
php artisan queue:restart
```

### **Problema 4: Cron jobs não executam**
1. Verificar se o caminho está correto
2. Testar comando manualmente via SSH
3. Verificar logs do cron no painel da Hostinger

## 📝 Checklist de Verificação

- [ ] Cron job configurado no painel da Hostinger
- [ ] Queue configurada para `database`
- [ ] Tabelas de queue criadas (`jobs`, `failed_jobs`)
- [ ] Configurações de email corretas no `.env`
- [ ] Cache limpo e reconfigurado
- [ ] Permissões de arquivos corretas
- [ ] Worker de queue ativo
- [ ] Comando de notificações testado manualmente
- [ ] Logs verificados para erros
- [ ] Timezone configurado corretamente

## 🔍 Comandos de Debug Úteis

```bash
# Verificar se o schedule está funcionando:
php artisan schedule:list

# Testar schedule manualmente:
php artisan schedule:run

# Verificar configurações:
php artisan about

# Verificar status da queue:
php artisan queue:monitor

# Verificar jobs failed:
php artisan queue:failed
```

## 📞 Suporte Adicional

Se após seguir todos os passos o problema persistir:

1. **Verificar logs detalhados**:
   ```bash
   tail -f storage/logs/laravel.log | grep -i notification
   ```

2. **Contatar suporte da Hostinger** com informações específicas sobre:
   - Configuração de cron jobs
   - Limitações de email
   - Configurações de PHP

3. **Testar em ambiente de staging** antes de aplicar em produção

---

**Nota**: Este guia foi criado especificamente para resolver problemas de notificações na Hostinger. Mantenha sempre backups antes de fazer alterações em produção.