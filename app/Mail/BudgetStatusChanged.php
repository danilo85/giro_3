<?php

namespace App\Mail;

use App\Models\Notification;
use App\Models\Orcamento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BudgetStatusChanged extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $orcamento;
    public $notification;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Orcamento $orcamento, Notification $notification, string $oldStatus, string $newStatus)
    {
        $this->orcamento = $orcamento;
        $this->notification = $notification;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->getSubject();
        
        return new Envelope(
            subject: $subject,
            from: config('mail.from.address', 'noreply@empresa.com'),
            replyTo: config('mail.reply_to.address', 'contato@empresa.com'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notifications.budget-status-changed',
            with: [
                'orcamento' => $this->orcamento,
                'notification' => $this->notification,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'oldStatusLabel' => $this->getStatusLabel($this->oldStatus),
                'newStatusLabel' => $this->getStatusLabel($this->newStatus),
                'statusColor' => $this->getStatusColor($this->newStatus),
                'cliente' => $this->orcamento->cliente,
                'actionUrl' => $this->getActionUrl(),
                'actionText' => $this->getActionText(),
                'statusMessage' => $this->getStatusMessage(),
                'changeDate' => now()->format('d/m/Y H:i'),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Get email subject based on status change
     */
    private function getSubject(): string
    {
        $budgetNumber = $this->orcamento->numero ?? $this->orcamento->id;
        
        switch ($this->newStatus) {
            case 'aprovado':
                return "✅ Orçamento #{$budgetNumber} foi Aprovado";
            case 'rejeitado':
                return "❌ Orçamento #{$budgetNumber} foi Rejeitado";
            case 'pago':
                return "💰 Orçamento #{$budgetNumber} foi Pago";
            case 'cancelado':
                return "🚫 Orçamento #{$budgetNumber} foi Cancelado";
            case 'em_andamento':
                return "🔄 Orçamento #{$budgetNumber} está em Andamento";
            case 'finalizado':
                return "🎉 Orçamento #{$budgetNumber} foi Finalizado";
            default:
                return "📋 Status do Orçamento #{$budgetNumber} foi Alterado";
        }
    }

    /**
     * Get status label in Portuguese
     */
    private function getStatusLabel(string $status): string
    {
        $labels = [
            'pendente' => 'Pendente',
            'aprovado' => 'Aprovado',
            'rejeitado' => 'Rejeitado',
            'em_andamento' => 'Em Andamento',
            'finalizado' => 'Finalizado',
            'pago' => 'Pago',
            'cancelado' => 'Cancelado',
        ];

        return $labels[$status] ?? ucfirst($status);
    }

    /**
     * Get status color for styling
     */
    private function getStatusColor(string $status): string
    {
        $colors = [
            'pendente' => '#f59e0b',
            'aprovado' => '#10b981',
            'rejeitado' => '#ef4444',
            'em_andamento' => '#3b82f6',
            'finalizado' => '#8b5cf6',
            'pago' => '#059669',
            'cancelado' => '#6b7280',
        ];

        return $colors[$status] ?? '#6b7280';
    }

    /**
     * Get action URL based on status
     */
    private function getActionUrl(): string
    {
        $baseUrl = config('app.url');
        
        switch ($this->newStatus) {
            case 'aprovado':
                return "{$baseUrl}/orcamentos/{$this->orcamento->id}";
            case 'pago':
                return "{$baseUrl}/orcamentos/{$this->orcamento->id}/pagamentos";
            default:
                return "{$baseUrl}/orcamentos/{$this->orcamento->id}";
        }
    }

    /**
     * Get action button text based on status
     */
    private function getActionText(): string
    {
        switch ($this->newStatus) {
            case 'aprovado':
                return 'Ver Orçamento Aprovado';
            case 'rejeitado':
                return 'Ver Detalhes';
            case 'pago':
                return 'Ver Comprovantes';
            case 'em_andamento':
                return 'Acompanhar Progresso';
            case 'finalizado':
                return 'Ver Resultado Final';
            default:
                return 'Ver Orçamento';
        }
    }

    /**
     * Get status-specific message
     */
    private function getStatusMessage(): string
    {
        $clienteName = $this->orcamento->cliente->nome ?? 'Cliente';
        
        switch ($this->newStatus) {
            case 'aprovado':
                return "Ótimas notícias! Seu orçamento foi aprovado e agora podemos dar início ao projeto. Em breve entraremos em contato para alinhar os próximos passos.";
            case 'rejeitado':
                return "Infelizmente seu orçamento não foi aprovado desta vez. Entre em contato conosco para discutir possíveis ajustes ou alternativas.";
            case 'pago':
                return "Confirmamos o recebimento do pagamento! Agradecemos pela confiança e o projeto será iniciado conforme acordado.";
            case 'em_andamento':
                return "Seu projeto já está em desenvolvimento! Você pode acompanhar o progresso através do nosso sistema.";
            case 'finalizado':
                return "Parabéns! Seu projeto foi concluído com sucesso. Esperamos que esteja satisfeito com o resultado.";
            case 'cancelado':
                return "Seu orçamento foi cancelado conforme solicitado. Se precisar de algo no futuro, estaremos aqui para ajudar.";
            default:
                return "O status do seu orçamento foi atualizado. Acesse o sistema para mais detalhes.";
        }
    }
}