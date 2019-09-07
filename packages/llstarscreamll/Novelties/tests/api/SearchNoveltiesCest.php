<?php

namespace Novelties;

use llstarscreamll\Novelties\Models\Novelty;

/**
 * Class SearchNoveltiesCest.
 *
 * @author Johan Alvarez <llstarscreamll@hotmail.com>
 */
class SearchNoveltiesCest
{
    /**
     * @var string
     */
    private $endpoint = 'api/v1/novelties/';

    /**
     * @param ApiTester $I
     */
    public function _before(ApiTester $I)
    {
        $this->user = $I->amLoggedAsAdminUser();
        $I->haveHttpHeader('Accept', 'application/json');
    }

    /**
     * @test
     * @param ApiTester $I
     */
    public function searchSuccessfully(ApiTester $I)
    {
        $novelties = factory(Novelty::class, 5)->create();

        $I->sendGET($this->endpoint);

        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonMatchesJsonPath('$.data.0.id');
        $I->seeResponseJsonMatchesJsonPath('$.data.1.id');
        $I->seeResponseJsonMatchesJsonPath('$.data.2.id');
        $I->seeResponseJsonMatchesJsonPath('$.data.3.id');
        $I->seeResponseJsonMatchesJsonPath('$.data.4.id');
    }

    /**
     * @test
     * @param ApiTester $I
     */
    public function shouldReturnUnprocesableEntityIfUserDoesntHaveRequiredPermissions(ApiTester $I)
    {
        $this->user->roles()->delete();
        $this->user->permissions()->delete();
        factory(Novelty::class, 5)->create();

        $I->sendGET($this->endpoint);

        $I->seeResponseCodeIs(403);
    }
}