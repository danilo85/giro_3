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
use Carbon\Carbon;

class PaymentDueAlert extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $orcamento;
    public $notification;
    public $daysUntilDue;

    /**
     * Create a new message instance.
     */
    public function __construct(Orcamento $orcamento, Notification $notification, int $daysUntilDue)
    {
        $this->orcamento = $orcamento;
        $this->notification = $notification;
        $this->daysUntilDue = $daysUntilDue;
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
        $dataVencimento = $this->getDataVencimento();
        $isOverdue = $this->daysUntilDue < 0;
        $isDueToday = $this->daysUntilDue === 0;
        
        return new Content(
            view: 'emails.notifications.payment-due-alert',
            with: [
                'orcamento' => $this->orcamento,
                'notification' => $this->notification,
                'daysUntilDue' => abs($this->daysUntilDue),
                'isOverdue' => $isOverdue,
                'isDueToday' => $isDueToday,
                'urgencyLevel' => $this->getUrgencyLevel(),
                'urgencyColor' => $this->getUrgencyColor(),
                'cliente' => $this->orcamento->cliente,
                'dataVencimentoFormatted' => $dataVencimento ? $dataVencimento->format('d/m/Y') : 'Não definida',
                'saldoRestanteFormatted' => $this->getSaldoRestanteFormatted(),
                'actionUrl' => $this->getActionUrl(),
                'actionText' => $this->getActionText(),
                'paymentMethods' => $this->getPaymentMethods(),
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
     * Get email subject based on urgency
     */
    private function getSubject(): string
    {
        $budgetNumber = $this->orcamento->numero ?? $this->orcamento->id;
        
        if ($this->daysUntilDue < 0) {
            $daysOverdue = abs($this->daysUntilDue);
            return "🚨 URGENTE: Pagamento em atraso há {$daysOverdue} dia(s) - Orçamento #{$budgetNumber}";
        } elseif ($this->daysUntilDue === 0) {
            return "⏰ HOJE: Vencimento do pagamento - Orçamento #{$budgetNumber}";
        } elseif ($this->daysUntilDue === 1) {
            return "⚠️ AMANHÃ: Vencimento do pagamento - Orçamento #{$budgetNumber}";
        } else {
            return "📅 Lembrete: Pagamento vence em {$this->daysUntilDue} dias - Orçamento #{$budgetNumber}";
        }
    }

    /**
     * Get urgency level for styling
     */
    private function getUrgencyLevel(): string
    {
        if ($this->daysUntilDue < 0) {
            return 'overdue';
        } elseif ($this->daysUntilDue === 0) {
            return 'due-today';
        } elseif ($this->daysUntilDue <= 3) {
            return 'urgent';
        } else {
            return 'reminder';
        }
    }

    /**
     * Get urgency color for styling
     */
    private function getUrgencyColor(): string
    {
        if ($this->daysUntilDue < 0) {
            return '#dc2626'; // Red for overdue
        } elseif ($this->daysUntilDue === 0) {
            return '#ea580c'; // Orange for due today
        } elseif ($this->daysUntilDue <= 3) {
            return '#f59e0b'; // Amber for urgent
        } else {
            return '#3b82f6'; // Blue for reminder
        }
    }

    /**
     * Get payment due date
     */
    private function getDataVencimento(): ?Carbon
    {
        // Try to get from data_vencimento field or calculate from created_at + payment terms
        if ($this->orcamento->data_vencimento) {
            return Carbon::parse($this->orcamento->data_vencimento);
        }
        
        // If no specific due date, assume 30 days from creation
        return $this->orcamento->created_at ? 
            Carbon::parse($this->orcamento->created_at)->addDays(30) : 
            null;
    }

    /**
     * Get formatted remaining balance
     */
    private function getSaldoRestanteFormatted(): string
    {
        $saldoRestante = $this->orcamento->valor_total - $this->orcamento->valor_pago;
        return 'R$ ' . number_format($saldoRestante, 2, ',', '.');
    }

    /**
     * Get action URL
     */
    private function getActionUrl(): string
    {
        $baseUrl = config('app.url');
        return "{$baseUrl}/orcamentos/{$this->orcamento->id}/pagamento";
    }

    /**
     * Get action button text
     */
    private function getActionText(): string
    {
        if ($this->daysUntilDue < 0) {
            return 'Regularizar Pagamento';
        } elseif ($this->daysUntilDue === 0) {
            return 'Pagar Agora';
        } else {
            return 'Efetuar Pagamento';
        }
    }

    /**
     * Get available payment methods
     */
    private function getPaymentMethods(): array
    {
        return [
            [
                'icon' => '💳',
                'name' => 'Cartão de Crédito',
                'description' => 'Pagamento instantâneo via cartão'
            ],
            [
                'icon' => '🏦',
                'name' => 'Transferência Bancária',
                'description' => 'PIX ou TED para nossa conta'
            ],
            [
                'icon' => '📄',
                'name' => 'Boleto Bancário',
                'description' => 'Pagamento via boleto'
            ],
            [
                'icon' => '💰',
                'name' => 'Dinheiro',
                'description' => 'Pagamento presencial'
            ]
        ];
    }

    /**
     * Get payment instructions based on urgency
     */
    public function getPaymentInstructions(): string
    {
        if ($this->daysUntilDue < 0) {
            $daysOverdue = abs($this->daysUntilDue);
            return "Seu pagamento está em atraso há {$daysOverdue} dia(s). Para evitar juros e multas, regularize sua situação o quanto antes. Entre em contato conosco se precisar renegociar as condições.";
        } elseif ($this->daysUntilDue === 0) {
            return "Seu pagamento vence hoje! Para evitar atrasos e possíveis encargos, efetue o pagamento até o final do dia. Caso já tenha pago, desconsidere este aviso.";
        } elseif ($this->daysUntilDue === 1) {
            return "Seu pagamento vence amanhã. Organize-se para efetuar o pagamento em dia e evitar qualquer inconveniente.";
        } elseif ($this->daysUntilDue <= 3) {
            return "Seu pagamento vence em {$this->daysUntilDue} dias. Este é um lembrete para que você possa se organizar e efetuar o pagamento pontualmente.";
        } else {
            return "Este é um lembrete amigável de que seu pagamento vence em {$this->daysUntilDue} dias. Você pode efetuar o pagamento antecipadamente se desejar.";
        }
    }
}