<?php

namespace Tests\Unit;

use App\Services\PipelineService;
use Tests\TestCase;

class PipelineServiceComprehensiveTest extends TestCase
{
    protected PipelineService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PipelineService();
    }

    // =========================================================================
    // getNextStages Tests
    // =========================================================================

    public function test_get_next_stages_from_prospecting()
    {
        $stages = $this->service->getNextStages('prospecting');

        expect($stages)->toBe(['qualification', 'lost']);
    }

    public function test_get_next_stages_from_qualification()
    {
        $stages = $this->service->getNextStages('qualification');

        expect($stages)->toBe(['proposal', 'lost']);
    }

    public function test_get_next_stages_from_proposal()
    {
        $stages = $this->service->getNextStages('proposal');

        expect($stages)->toBe(['negotiation', 'lost']);
    }

    public function test_get_next_stages_from_negotiation()
    {
        $stages = $this->service->getNextStages('negotiation');

        expect($stages)->toBe(['won', 'lost']);
    }

    public function test_get_next_stages_from_won_returns_empty()
    {
        $stages = $this->service->getNextStages('won');

        expect($stages)->toEqual([]);
    }

    public function test_get_next_stages_from_lost_returns_empty()
    {
        $stages = $this->service->getNextStages('lost');

        expect($stages)->toEqual([]);
    }

    public function test_get_next_stages_from_closed_returns_empty()
    {
        $stages = $this->service->getNextStages('closed');

        expect($stages)->toEqual([]);
    }

    public function test_get_next_stages_from_invalid_stage_returns_empty()
    {
        $stages = $this->service->getNextStages('invalid_stage');

        expect($stages)->toEqual([]);
    }

    public function test_get_next_stages_is_case_insensitive()
    {
        $stages1 = $this->service->getNextStages('PROSPECTING');
        $stages2 = $this->service->getNextStages('Prospecting');
        $stages3 = $this->service->getNextStages('prospecting');

        expect($stages1)->toBe($stages2);
        expect($stages2)->toBe($stages3);
    }

    // =========================================================================
    // canTransition Tests
    // =========================================================================

    public function test_can_transition_prospecting_to_qualification()
    {
        $result = $this->service->canTransition('prospecting', 'qualification');

        expect($result)->toBeTrue();
    }

    public function test_can_transition_prospecting_to_lost()
    {
        $result = $this->service->canTransition('prospecting', 'lost');

        expect($result)->toBeTrue();
    }

    public function test_cannot_transition_prospecting_to_proposal()
    {
        $result = $this->service->canTransition('prospecting', 'proposal');

        expect($result)->toBeFalse();
    }

    public function test_cannot_transition_prospecting_to_negotiation()
    {
        $result = $this->service->canTransition('prospecting', 'negotiation');

        expect($result)->toBeFalse();
    }

    public function test_can_transition_qualification_to_proposal()
    {
        $result = $this->service->canTransition('qualification', 'proposal');

        expect($result)->toBeTrue();
    }

    public function test_cannot_transition_qualification_to_prospecting()
    {
        $result = $this->service->canTransition('qualification', 'prospecting');

        expect($result)->toBeFalse();
    }

    public function test_can_transition_proposal_to_negotiation()
    {
        $result = $this->service->canTransition('proposal', 'negotiation');

        expect($result)->toBeTrue();
    }

    public function test_can_transition_negotiation_to_won()
    {
        $result = $this->service->canTransition('negotiation', 'won');

        expect($result)->toBeTrue();
    }

    public function test_cannot_transition_from_won()
    {
        expect($this->service->canTransition('won', 'lost'))->toBeFalse();
        expect($this->service->canTransition('won', 'negotiation'))->toBeFalse();
        expect($this->service->canTransition('won', 'prospecting'))->toBeFalse();
    }

    public function test_cannot_transition_from_lost()
    {
        expect($this->service->canTransition('lost', 'won'))->toBeFalse();
        expect($this->service->canTransition('lost', 'negotiation'))->toBeFalse();
    }

    public function test_transition_is_case_insensitive()
    {
        $result1 = $this->service->canTransition('PROSPECTING', 'QUALIFICATION');
        $result2 = $this->service->canTransition('Prospecting', 'Qualification');
        $result3 = $this->service->canTransition('prospecting', 'qualification');

        expect($result1)->toBeTrue();
        expect($result2)->toBeTrue();
        expect($result3)->toBeTrue();
    }

    public function test_can_transition_from_qualification_to_lost()
    {
        $result = $this->service->canTransition('qualification', 'lost');

        expect($result)->toBeTrue();
    }

    public function test_can_transition_from_proposal_to_lost()
    {
        $result = $this->service->canTransition('proposal', 'lost');

        expect($result)->toBeTrue();
    }

    public function test_can_transition_from_negotiation_to_lost()
    {
        $result = $this->service->canTransition('negotiation', 'lost');

        expect($result)->toBeTrue();
    }

    // =========================================================================
    // Complex Transition Chains
    // =========================================================================

    public function test_forward_progression_prospecting_to_won()
    {
        expect($this->service->canTransition('prospecting', 'qualification'))->toBeTrue();
        expect($this->service->canTransition('qualification', 'proposal'))->toBeTrue();
        expect($this->service->canTransition('proposal', 'negotiation'))->toBeTrue();
        expect($this->service->canTransition('negotiation', 'won'))->toBeTrue();
    }

    public function test_lost_is_accessible_from_multiple_stages()
    {
        expect($this->service->canTransition('prospecting', 'lost'))->toBeTrue();
        expect($this->service->canTransition('qualification', 'lost'))->toBeTrue();
        expect($this->service->canTransition('proposal', 'lost'))->toBeTrue();
        expect($this->service->canTransition('negotiation', 'lost'))->toBeTrue();
    }

    public function test_cannot_skip_stages_forward()
    {
        expect($this->service->canTransition('prospecting', 'proposal'))->toBeFalse();
        expect($this->service->canTransition('prospecting', 'negotiation'))->toBeFalse();
        expect($this->service->canTransition('prospecting', 'won'))->toBeFalse();
        expect($this->service->canTransition('qualification', 'negotiation'))->toBeFalse();
        expect($this->service->canTransition('qualification', 'won'))->toBeFalse();
        expect($this->service->canTransition('proposal', 'won'))->toBeFalse();
    }

    public function test_cannot_go_backward()
    {
        expect($this->service->canTransition('qualification', 'prospecting'))->toBeFalse();
        expect($this->service->canTransition('proposal', 'qualification'))->toBeFalse();
        expect($this->service->canTransition('negotiation', 'proposal'))->toBeFalse();
    }

    public function test_final_states_have_no_transitions()
    {
        // Won has no valid next states
        $wonStages = $this->service->getNextStages('won');
        expect($wonStages)->toEqual([]);

        // Lost has no valid next states
        $lostStages = $this->service->getNextStages('lost');
        expect($lostStages)->toEqual([]);
    }

    // =========================================================================
    // Edge Cases
    // =========================================================================

    public function test_empty_string_stage_returns_empty()
    {
        $stages = $this->service->getNextStages('');
        expect($stages)->toEqual([]);
    }

    public function test_whitespace_stage_returns_empty()
    {
        $stages = $this->service->getNextStages('   ');
        expect($stages)->toEqual([]);
    }

    public function test_special_characters_in_stage_returns_empty()
    {
        $stages = $this->service->getNextStages('pro@specting!');
        expect($stages)->toEqual([]);
    }

    public function test_null_stage_handled_gracefully()
    {
        // Should not throw error
        $stages = $this->service->getNextStages('null_value');
        expect(is_array($stages))->toBeTrue();
    }

    // =========================================================================
    // Transition Matrix Validation
    // =========================================================================

    public function test_all_valid_transitions_defined()
    {
        // Get all reachable states
        $reachableFromProspecting = ['qualification', 'lost'];
        $reachableFromQualification = ['proposal', 'lost'];
        $reachableFromProposal = ['negotiation', 'lost'];
        $reachableFromNegotiation = ['won', 'lost'];

        expect($this->service->getNextStages('prospecting'))->toBe($reachableFromProspecting);
        expect($this->service->getNextStages('qualification'))->toBe($reachableFromQualification);
        expect($this->service->getNextStages('proposal'))->toBe($reachableFromProposal);
        expect($this->service->getNextStages('negotiation'))->toBe($reachableFromNegotiation);
    }

    public function test_early_exit_paths()
    {
        // Can exit from any stage via 'lost'
        expect($this->service->canTransition('prospecting', 'lost'))->toBeTrue();
        expect($this->service->canTransition('qualification', 'lost'))->toBeTrue();
        expect($this->service->canTransition('proposal', 'lost'))->toBeTrue();
        expect($this->service->canTransition('negotiation', 'lost'))->toBeTrue();
    }

    public function test_only_one_winning_state()
    {
        // Only negotiation can transition to won
        expect($this->service->canTransition('prospecting', 'won'))->toBeFalse();
        expect($this->service->canTransition('qualification', 'won'))->toBeFalse();
        expect($this->service->canTransition('proposal', 'won'))->toBeFalse();
        expect($this->service->canTransition('negotiation', 'won'))->toBeTrue();
    }

}
