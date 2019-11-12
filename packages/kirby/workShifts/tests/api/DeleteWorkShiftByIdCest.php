<?php

namespace WorkShifts;

/**
 * Class DeleteWorkShiftByIdCest.
 *
 * @author Johan Alvarez <llstarscreamll@hotmail.com>
 */
class DeleteWorkShiftByIdCest
{
    /**
     * @var string
     */
    private $endpoint = 'api/v1/work-shifts/:id';

    /**
     * @var array
     */
    private $workShift;

    /**
     * @param ApiTester $I
     */
    public function _before(ApiTester $I)
    {
        $this->workShift = [
            'name' => 'work shift A',
            'grace_minutes_after_start_times' => 15,
            'grace_minutes_before_start_times' => 15,
            'grace_minutes_after_end_times' => 15,
            'grace_minutes_before_end_times' => 15,
            'meal_time_in_minutes' => 90,
            'min_minutes_required_to_discount_meal_time' => 60 * 6,
            'time_slots' => json_encode([['start' => '07:00', 'end' => '12:30'], ['start' => '02:00', 'end' => '06:00']]),
            'deleted_at' => null,
        ];

        $this->workShift['id'] = $I->haveRecord('work_shifts', $this->workShift);

        $I->amLoggedAsAdminUser();
        $I->haveHttpHeader('Accept', 'application/json');
    }

    /**
     * @test
     * @param ApiTester $I
     */
    public function whenIdExistsExpectNoContentAndResourceToBeDeleted(ApiTester $I)
    {
        $I->sendDELETE(str_replace(':id', $this->workShift['id'], $this->endpoint));

        $I->seeResponseCodeIs(204);
        $I->dontSeeRecord('work_shifts', $this->workShift);
    }

    /**
     * @test
     * @param ApiTester $I
     */
    public function whenIdDoesNotExistsExpectNotFound(ApiTester $I)
    {
        $I->sendDELETE(str_replace(':id', 123, $this->endpoint));

        $I->seeResponseCodeIs(404);
    }
}