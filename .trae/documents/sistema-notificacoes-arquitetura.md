# Sistema de Notificações - Arquitetura Técnica

## 1. Design da Arquitetura

```mermaid
graph TD
    A[Laravel Application] --> B[Observer Pattern]
    A --> C[Scheduler Commands]
    A --> D[Queue System]
    
    B --> E[Orcamento Observer]
    C --> F[CheckPaymentDueDates Command]
    
    E --> G[Mail Queue]
    F --> G
    G --> H[Email Service]
    
    A --> I[Notification Model]
    I --> J[MySQL Database]
    
    H --> K[SMTP Server]
    
    subgraph "Application Layer"
        A
        B
        C
        D
    end
    
    subgraph "Business Logic"
        E
        F
    end
    
    subgraph "Communication Layer"
        G
        H
        K
    end
    
    subgraph "Data Layer"
        I
        J
    end
```

## 2. Descrição das Tecnologias

- **Frontend**: Blade Templates + Alpine.js + Tailwind CSS
- **Backend**: Laravel 10 + MySQL + Redis (Queue)
- **Email**: Laravel Mail + SMTP
- **Agendamento**: Laravel Scheduler + Cron
- **Observadores**: Laravel Observers
- **Filas**: Laravel Queues com Redis

## 3. Definições de Rotas

| Rota | Propósito |
|------|-----------|
| /notifications | Dashboard principal de notificações |
| /notifications/settings | Configurações de preferências de notificação |
| /notifications/templates | Gerenciamento de templates de email |
| /notifications/logs | Logs e histórico de notificações enviadas |
| /api/notifications/mark-read | API para marcar notificações como lidas |
| /api/notifications/preferences | API para atualizar preferências |

## 4. Definições de API

### 4.1 APIs Principais

**Marcar notificação como lida**
```
POST /api/notifications/{id}/mark-read
```

Request:
| Nome do Parâmetro | Tipo | Obrigatório | Descrição |
|-------------------|------|-------------|-----------|
| id | integer | true | ID da notificação |

Response:
| Nome do Parâmetro | Tipo | Descrição |
|-------------------|------|-----------|
| success | boolean | Status da operação |
| message | string | Mensagem de confirmação |

**Atualizar preferências de notificação**
```
PUT /api/notifications/preferences
```

Request:
| Nome do Parâmetro | Tipo | Obrigatório | Descrição |
|-------------------|------|-------------|-----------|
| email_enabled | boolean | true | Habilitar notificações por email |
| budget_notifications | boolean | true | Notificações de orçamento |
| payment_notifications | boolean | true | Notificações de pagamento |
| notification_days | array | true | Dias antes do vencimento [7,3,1] |

Response:
```json
{
  "success": true,
  "message": "Preferências atualizadas com sucesso",
  "data": {
    "email_enabled": true,
    "budget_notifications": true,
    "payment_notifications": true,
    "notification_days": [7, 3, 1]
  }
}
```

## 5. Arquitetura do Servidor

```mermaid
graph TD
    A[HTTP Request] --> B[Route Layer]
    B --> C[Controller Layer]
    C --> D[Service Layer]
    D --> E[Repository Layer]
    E --> F[(Database)]
    
    G[Observer Layer] --> D
    H[Command Layer] --> D
    I[Queue Layer] --> J[Mail Service]
    
    subgraph Server
        B
        C
        D
        E
        G
        H
        I
        J
    end
```

## 6. Modelo de Dados

### 6.1 Definição do Modelo de Dados

```mermaid
erDiagram
    NOTIFICATIONS ||--o{ NOTIFICATION_LOGS : has
    NOTIFICATIONS }o--|| USERS : belongs_to
    NOTIFICATIONS }o--|| ORCAMENTOS : relates_to
    NOTIFICATIONS }o--|| PAGAMENTOS : relates_to
    
    NOTIFICATION_PREFERENCES }o--|| USERS : belongs_to
    
    NOTIFICATIONS {
        uuid id PK
        bigint user_id FK
        string type
        string title
        text message
        json data
        timestamp read_at
        timestamp created_at
        timestamp updated_at
    }
    
    NOTIFICATION_LOGS {
        uuid id PK
        uuid notification_id FK
        string channel
        string status
        text error_message
        timestamp sent_at
        timestamp delivered_at
        timestamp created_at
    }
    
    NOTIFICATION_PREFERENCES {
        uuid id PK
        bigint user_id FK
        boolean email_enabled
        boolean budget_notifications
        boolean payment_notifications
        json notification_days
        timestamp created_at
        timestamp updated_at
    }
    
    USERS {
        bigint id PK
        string name
        string email
        timestamp created_at
        timestamp updated_at
    }
    
    ORCAMENTOS {
        bigint id PK
        bigint cliente_id FK
        string titulo
        decimal valor_total
        string status
        date data_validade
        timestamp created_at
        timestamp updated_at
    }
    
    PAGAMENTOS {
        bigint id PK
        bigint orcamento_id FK
        decimal valor
        date data_pagamento
        string status
        timestamp created_at
        timestamp updated_at
    }
```

### 6.2 Linguagem de Definição de Dados

**Tabela de Notificações (notifications)**
```sql
-- Criar tabela de notificações
CREATE TABLE notifications (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id BIGINT UNSIGNED NOT NULL,
    type VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    data JSON NULL,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Criar índices
CREATE INDEX idx_notifications_user_id ON notifications(user_id);
CREATE INDEX idx_notifications_type ON notifications(type);
CREATE INDEX idx_notifications_read_at ON notifications(read_at);
CREATE INDEX idx_notifications_created_at ON notifications(created_at DESC);
```

**Tabela de Logs de Notificações (notification_logs)**
```sql
-- Criar tabela de logs
CREATE TABLE notification_logs (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    notification_id CHAR(36) NOT NULL,
    channel VARCHAR(50) NOT NULL DEFAULT 'email',
    status ENUM('pending', 'sent', 'delivered', 'failed') NOT NULL DEFAULT 'pending',
    error_message TEXT NULL,
    sent_at TIMESTAMP NULL,
    delivered_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (notification_id) REFERENCES notifications(id) ON DELETE CASCADE
);

-- Criar índices
CREATE INDEX idx_notification_logs_notification_id ON notification_logs(notification_id);
CREATE INDEX idx_notification_logs_status ON notification_logs(status);
CREATE INDEX idx_notification_logs_created_at ON notification_logs(created_at DESC);
```

**Tabela de Preferências de Notificação (notification_preferences)**
```sql
-- Criar tabela de preferências
CREATE TABLE notification_preferences (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    email_enabled BOOLEAN DEFAULT TRUE,
    budget_notifications BOOLEAN DEFAULT TRUE,
    payment_notifications BOOLEAN DEFAULT TRUE,
    notification_days JSON DEFAULT '[7, 3, 1]',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Criar índice
CREATE INDEX idx_notification_preferences_user_id ON notification_preferences(user_id);
```

**Dados Iniciais**
```sql
-- Inserir preferências padrão para usuários existentes
INSERT INTO notification_preferences (user_id, email_enabled, budget_notifications, payment_notifications, notification_days)
SELECT id, TRUE, TRUE, TRUE, '[7, 3, 1]'
FROM users
WHERE id NOT IN (SELECT user_id FROM notification_preferences);
```