<?php

namespace App\Services;

use App\Models\Opportunity;
use App\Models\Subscription;
use App\Models\Invoice;

class PipelineService
{
    /**
     * Valid stage transitions map.
     * Keys are current stages; values are arrays of allowed next stages.
     */
    protected array $transitions = [
        'prospecting'   => ['proposal', 'lost'],
        'proposal'      => ['negotiation', 'lost'],
        'negotiation'   => ['won', 'lost'],
        'won'           => [],
        'lost'          => [],
        'closed'        => [],
    ];

    /**
     * Return the valid next stages from the given current stage.
     *
     * @param  string  $currentStage
     * @return string[]
     */
    public function getNextStages(string $currentStage): array
    {
        return $this->transitions[strtolower($currentStage)] ?? [];
    }

    /**
     * Determine whether a transition from one stage to another is allowed.
     *
     * @param  string  $from
     * @param  string  $to
     * @return bool
     */
    public function canTransition(string $from, string $to): bool
    {
        $nextStages = $this->getNextStages($from);
        return in_array(strtolower($to), $nextStages, true);
    }

    /**
     * Trigger post-won actions for an opportunity.
     *
     * Creates a Subscription (for recurring deals) or an Invoice (for
     * one-time deals) linked to the opportunity.
     *
     * @param  Opportunity  $opportunity
     * @return Subscription|Invoice
     */
    public function triggerWonActions(Opportunity $opportunity): Subscription|Invoice
    {
        if ($this->isRecurring($opportunity)) {
            return $this->createSubscription($opportunity);
        }

        return $this->createInvoice($opportunity);
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Decide whether the opportunity results in a recurring subscription.
     * Extend this logic to suit your product catalogue.
     */
    protected function isRecurring(Opportunity $opportunity): bool
    {
        return isset($opportunity->type) && $opportunity->type === 'recurring';
    }

    /**
     * Create a Subscription record linked to the won opportunity.
     */
    protected function createSubscription(Opportunity $opportunity): Subscription
    {
        return Subscription::create([
            'opportunity_id' => $opportunity->id,
            'client_id'      => $opportunity->client_id,
            'monthly_rate'   => $opportunity->final_value ?? $opportunity->estimated_value ?? 0,
            'start_date'     => now(),
            'status'         => 'active',
        ]);
    }

    /**
     * Create an Invoice record linked to the won opportunity.
     */
    protected function createInvoice(Opportunity $opportunity): Invoice
    {
        $yearMonth = now()->format('Ym');
        $prefix = 'INV-' . $yearMonth . '-';
        $lastInvoice = Invoice::where('invoice_number', 'like', $prefix . '%')
            ->orderByDesc('invoice_number')
            ->first();
        $seq = $lastInvoice ? ((int) substr($lastInvoice->invoice_number, -4)) + 1 : 1;
        $invoiceNumber = $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);

        return Invoice::create([
            'invoice_number' => $invoiceNumber,
            'booking_id'     => $opportunity->booking_id,
            'client_id'      => $opportunity->client_id,
            'amount'         => $opportunity->final_value ?? $opportunity->estimated_value ?? 0,
            'due_date'       => now()->addDays(30),
            'status'         => 'draft',
        ]);
    }
}
