<?php

namespace Tests\Unit;

use App\Models\ActivityLog;
use App\Models\Opportunity;
use App\Models\SalesTarget;
use App\Models\User;
use App\Services\KpiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KpiServiceComprehensiveTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================================
    // incrementActivityCount Tests - Meeting Type
    // =========================================================================

    public function test_increment_activity_count_meeting_increments_meetings()
    {
        $user = User::factory()->create();
        $activityLog = ActivityLog::factory()->create([
            'sales_id' => $user->id,
            'type' => 'meeting',
            'activity_date' => now(),
        ]);

        KpiService::incrementActivityCount($activityLog);

        $target = SalesTarget::where('user_id', $user->id)->first();
        expect($target)->not->toBeNull();
        expect($target->actual_meetings)->toBeGreaterThanOrEqual(1);
    }

    public function test_increment_activity_count_increments_by_type()
    {
        $user = User::factory()->create();
        $initialLog = ActivityLog::factory()->create([
            'sales_id' => $user->id,
            'type' => 'meeting',
            'activity_date' => now(),
        ]);
        KpiService::incrementActivityCount($initialLog);

        $beforeMeetings = SalesTarget::where('user_id', $user->id)->first()->actual_meetings;

        $newLog = ActivityLog::factory()->create([
            'sales_id' => $user->id,
            'type' => 'meeting',
            'activity_date' => now(),
        ]);
        KpiService::incrementActivityCount($newLog);

        $afterMeetings = SalesTarget::where('user_id', $user->id)->first()->actual_meetings;
        expect($afterMeetings)->toBeGreaterThan($beforeMeetings);
    }

    // =========================================================================
    // incrementActivityCount Tests - Call Type
    // =========================================================================

    public function test_increment_activity_count_call_type()
    {
        $user = User::factory()->create();
        $activityLog = ActivityLog::factory()->create([
            'sales_id' => $user->id,
            'type' => 'call',
            'activity_date' => now(),
        ]);

        KpiService::incrementActivityCount($activityLog);

        $target = SalesTarget::where('user_id', $user->id)->first();
        expect($target)->not->toBeNull();
        expect($target->actual_calls)->toBeGreaterThanOrEqual(1);
    }

    // =========================================================================
    // incrementActivityCount Tests - Visit Type
    // =========================================================================

    public function test_increment_activity_count_visit_type()
    {
        $user = User::factory()->create();
        $activityLog = ActivityLog::factory()->create([
            'sales_id' => $user->id,
            'type' => 'visit',
            'activity_date' => now(),
        ]);

        KpiService::incrementActivityCount($activityLog);

        $target = SalesTarget::where('user_id', $user->id)->first();
        expect($target->actual_visits)->toBeGreaterThanOrEqual(1);
    }

    // =========================================================================
    // incrementActivityCount Tests - Generic Call Types (follow_up, email, demo)
    // =========================================================================

    public function test_increment_activity_count_follow_up_as_call()
    {
        $user = User::factory()->create();
        $activityLog = ActivityLog::factory()->create([
            'sales_id' => $user->id,
            'type' => 'follow_up',
            'activity_date' => now(),
        ]);

        KpiService::incrementActivityCount($activityLog);

        $target = SalesTarget::where('user_id', $user->id)->first();
        expect($target->actual_calls)->toBeGreaterThanOrEqual(1);
    }

    public function test_increment_activity_count_email_as_call()
    {
        $user = User::factory()->create();
        $activityLog = ActivityLog::factory()->create([
            'sales_id' => $user->id,
            'type' => 'email',
            'activity_date' => now(),
        ]);

        KpiService::incrementActivityCount($activityLog);

        $target = SalesTarget::where('user_id', $user->id)->first();
        expect($target->actual_calls)->toBeGreaterThanOrEqual(1);
    }

    public function test_increment_activity_count_demo_as_call()
    {
        $user = User::factory()->create();
        $activityLog = ActivityLog::factory()->create([
            'sales_id' => $user->id,
            'type' => 'demo',
            'activity_date' => now(),
        ]);

        KpiService::incrementActivityCount($activityLog);

        $target = SalesTarget::where('user_id', $user->id)->first();
        expect($target->actual_calls)->toBeGreaterThanOrEqual(1);
    }

    // =========================================================================
    // incrementActivityCount Tests - Mixed Activities
    // =========================================================================

    public function test_increment_activity_count_tracks_different_types()
    {
        $user = User::factory()->create();

        ActivityLog::factory()->create(['sales_id' => $user->id, 'type' => 'meeting', 'activity_date' => now()]);
        ActivityLog::factory()->create(['sales_id' => $user->id, 'type' => 'call', 'activity_date' => now()]);
        ActivityLog::factory()->create(['sales_id' => $user->id, 'type' => 'visit', 'activity_date' => now()]);

        $logs = ActivityLog::where('sales_id', $user->id)->get();
        foreach ($logs as $log) {
            KpiService::incrementActivityCount($log);
        }

        $target = SalesTarget::where('user_id', $user->id)->first();
        expect($target->actual_meetings)->toBeGreaterThanOrEqual(1);
        expect($target->actual_calls)->toBeGreaterThanOrEqual(1);
        expect($target->actual_visits)->toBeGreaterThanOrEqual(1);
    }

    // =========================================================================
    // incrementActivityCount Tests - Period Handling
    // =========================================================================

    public function test_increment_activity_count_creates_target_for_correct_period()
    {
        $user = User::factory()->create();
        $activityDate = now();
        $activityLog = ActivityLog::factory()->create([
            'sales_id' => $user->id,
            'type' => 'meeting',
            'activity_date' => $activityDate,
        ]);

        KpiService::incrementActivityCount($activityLog);

        $target = SalesTarget::where('user_id', $user->id)
            ->where('period_year', $activityDate->format('Y'))
            ->where('period_month', $activityDate->format('n'))
            ->first();

        expect($target)->not->toBeNull();
    }

    public function test_increment_activity_count_separates_different_months()
    {
        $user = User::factory()->create();

        $log1 = ActivityLog::factory()->create([
            'sales_id' => $user->id,
            'type' => 'meeting',
            'activity_date' => now(),
        ]);
        KpiService::incrementActivityCount($log1);

        $log2 = ActivityLog::factory()->create([
            'sales_id' => $user->id,
            'type' => 'meeting',
            'activity_date' => now()->addMonth(),
        ]);
        KpiService::incrementActivityCount($log2);

        expect(SalesTarget::where('user_id', $user->id)->count())->toBeGreaterThanOrEqual(2);
    }

    // =========================================================================
    // incrementActivityCount Tests - Unknown Activity Type
    // =========================================================================

    public function test_increment_activity_count_ignores_unknown_type()
    {
        $user = User::factory()->create();
        $activityLog = ActivityLog::factory()->create([
            'sales_id' => $user->id,
            'type' => 'unknown_type',
            'activity_date' => now(),
        ]);

        KpiService::incrementActivityCount($activityLog);

        $target = SalesTarget::where('user_id', $user->id)->first();
        // Unknown types should not increment any counter
        expect($target->actual_meetings + $target->actual_calls + $target->actual_visits)->toBe(0);
    }

    // =========================================================================
    // incrementOpportunityCount Tests
    // =========================================================================

    public function test_increment_opportunity_count_increments()
    {
        $user = User::factory()->create();

        KpiService::incrementOpportunityCount($user->id);

        $target = SalesTarget::where('user_id', $user->id)
            ->where('period_year', now()->format('Y'))
            ->where('period_month', now()->format('n'))
            ->first();

        expect($target)->not->toBeNull();
        expect($target->actual_opportunities)->toBeGreaterThanOrEqual(1);
    }

    public function test_increment_opportunity_count_accumulates()
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 3; $i++) {
            KpiService::incrementOpportunityCount($user->id);
        }

        $target = SalesTarget::where('user_id', $user->id)
            ->where('period_year', now()->format('Y'))
            ->where('period_month', now()->format('n'))
            ->first();

        expect($target->actual_opportunities)->toBeGreaterThanOrEqual(3);
    }

    public function test_increment_opportunity_count_for_different_users()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        KpiService::incrementOpportunityCount($user1->id);
        KpiService::incrementOpportunityCount($user2->id);
        KpiService::incrementOpportunityCount($user1->id);

        $target1 = SalesTarget::where('user_id', $user1->id)->first();
        $target2 = SalesTarget::where('user_id', $user2->id)->first();

        expect($target1->actual_opportunities)->toBeGreaterThan($target2->actual_opportunities);
    }

    // =========================================================================
    // recordWon Tests
    // =========================================================================

    public function test_record_won_increments_won_count()
    {
        $user = User::factory()->create();
        $opp = Opportunity::factory()->create([
            'sales_id' => $user->id,
            'estimated_value' => 10_000_000,
        ]);

        KpiService::recordWon($opp);

        $target = SalesTarget::where('user_id', $user->id)->first();
        expect($target->actual_won)->toBeGreaterThanOrEqual(1);
    }

    public function test_record_won_increments_revenue()
    {
        $user = User::factory()->create();
        $opp = Opportunity::factory()->create([
            'sales_id' => $user->id,
            'estimated_value' => 10_000_000,
        ]);

        KpiService::recordWon($opp);

        $target = SalesTarget::where('user_id', $user->id)->first();
        expect((float)$target->actual_revenue)->toBeGreaterThanOrEqual(10_000_000.0);
    }

    public function test_record_won_uses_final_value_when_available()
    {
        $user = User::factory()->create();
        $opp = Opportunity::factory()->create([
            'sales_id' => $user->id,
            'estimated_value' => 10_000_000,
            'final_value' => 8_000_000,
        ]);

        KpiService::recordWon($opp);

        $target = SalesTarget::where('user_id', $user->id)->first();
        expect((float)$target->actual_revenue)->toBeLessThanOrEqual(8_000_000.0);
    }

    public function test_record_won_creates_correct_period()
    {
        $user = User::factory()->create();
        $opp = Opportunity::factory()->create(['sales_id' => $user->id]);

        KpiService::recordWon($opp);

        $target = SalesTarget::where('user_id', $user->id)
            ->where('period_year', now()->format('Y'))
            ->where('period_month', now()->format('n'))
            ->first();

        expect($target)->not->toBeNull();
    }

    // =========================================================================
    // Integration Tests
    // =========================================================================

    public function test_complete_kpi_tracking_workflow()
    {
        $user = User::factory()->create();

        // Record activities
        for ($i = 0; $i < 3; $i++) {
            $activityLog = ActivityLog::factory()->create([
                'sales_id' => $user->id,
                'type' => 'meeting',
                'activity_date' => now(),
            ]);
            KpiService::incrementActivityCount($activityLog);
        }

        // Record opportunities
        KpiService::incrementOpportunityCount($user->id);
        KpiService::incrementOpportunityCount($user->id);

        // Record wins
        $opp1 = Opportunity::factory()->create(['sales_id' => $user->id, 'estimated_value' => 10_000_000]);
        $opp2 = Opportunity::factory()->create(['sales_id' => $user->id, 'estimated_value' => 5_000_000]);
        KpiService::recordWon($opp1);
        KpiService::recordWon($opp2);

        $target = SalesTarget::where('user_id', $user->id)->first();

        expect($target->actual_meetings)->toBeGreaterThanOrEqual(3);
        expect($target->actual_opportunities)->toBeGreaterThanOrEqual(2);
        expect($target->actual_won)->toBeGreaterThanOrEqual(2);
        expect((float)$target->actual_revenue)->toBeGreaterThanOrEqual(15_000_000.0);
    }

    public function test_multiple_users_have_separate_kpis()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        KpiService::incrementOpportunityCount($user1->id);
        KpiService::incrementOpportunityCount($user1->id);

        KpiService::incrementOpportunityCount($user2->id);

        $target1 = SalesTarget::where('user_id', $user1->id)->first();
        $target2 = SalesTarget::where('user_id', $user2->id)->first();

        expect($target1->actual_opportunities)->toBeGreaterThan($target2->actual_opportunities);
    }
}
