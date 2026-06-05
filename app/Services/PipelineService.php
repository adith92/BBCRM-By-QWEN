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
        'prospecting'   => ['qualification', 'lost'],
        'qualification' => ['proposal', 'lost'],
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
        $opportunity->loadMissing('product.category');

        return optional(optional($opportunity->product)->category)->type === 'long_term';
    }

    /**
     * Create a Subscription record linked to the won opportunity.
     */
    protected function createSubscription(Opportunity $opportunity): Subscription
    {
        $amount = $this->dealAmount($opportunity);

        $subscription = Subscription::create([
            'opportunity_id' => $opportunity->id,
            'client_id'      => $opportunity->client_id,
            'product_id'     => $opportunity->product_id,
            'start_date'     => now(),
            'end_date'       => now()->addYear(),
            'monthly_rate'   => $amount,
            'billing_cycle'  => 'monthly',
            'status'         => 'active',
            'next_billing_date' => now()->addMonth()->toDateString(),
            'notes'          => "Generated from opportunity {$opportunity->opp_number}",
        ]);

        $opportunity->update(['subscription_id' => $subscription->id]);

        return $subscription;
    }

    /**
     * Create an Invoice record linked to the won opportunity.
     */
    protected function createInvoice(Opportunity $opportunity): Invoice
    {
        return Invoice::create([
            'invoice_number' => $this->nextInvoiceNumber(),
            'booking_id'     => $opportunity->booking_id,
            'client_id'      => $opportunity->client_id,
            'amount'         => $this->dealAmount($opportunity),
            'status'         => 'draft',
            'due_date'       => now()->addDays(30)->toDateString(),
            'notes'          => "Generated from opportunity {$opportunity->opp_number}",
        ]);
    }

    protected function dealAmount(Opportunity $opportunity): float
    {
        return (float) ($opportunity->final_value ?? $opportunity->estimated_value ?? 0);
    }

    protected function nextInvoiceNumber(): string
    {
        $prefix = 'INV-' . now()->format('Ym') . '-';
        $lastInvoice = Invoice::where('invoice_number', 'like', $prefix . '%')
            ->orderByDesc('invoice_number')
            ->first();

        $sequence = $lastInvoice ? ((int) substr($lastInvoice->invoice_number, -4)) + 1 : 1;

        return $prefix . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }
}
