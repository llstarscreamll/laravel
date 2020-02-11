<?php

namespace Kirby\TimeClock\UI\API\Controllers;

use Illuminate\Http\Request;
use Kirby\Core\Http\Controller;
use Kirby\TimeClock\Events\CheckedOutEvent;
use Prettus\Repository\Criteria\RequestCriteria;
use Kirby\TimeClock\UI\API\Resources\TimeClockLogResource;
use Kirby\TimeClock\Contracts\TimeClockLogRepositoryInterface;
use Kirby\TimeClock\UI\API\Requests\CreateTimeClockLogRequest;
use Kirby\TimeClock\UI\API\Requests\SearchTimeClockLogsRequest;

/**
 * Class TimeClockLogsController.
 *
 * @author Johan Alvarez <llstarscreamll@hotmail.com>
 */
class TimeClockLogsController extends Controller
{
    /**
     * @var TimeClockLogRepositoryInterface
     */
    private $timeClockLogRepository;

    /**
     * @param TimeClockLogRepositoryInterface $timeClockLogRepository
     */
    public function __construct(TimeClockLogRepositoryInterface $timeClockLogRepository)
    {
        $this->timeClockLogRepository = $timeClockLogRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SearchTimeClockLogsRequest $request)
    {
        $timeClockLogs = $this->timeClockLogRepository
            ->pushCriteria(app(RequestCriteria::class))
            ->with([
                'employee.user', 'workShift', 'novelties.noveltyType', 'subCostCenter',
                'approvals:users.id,users.first_name,users.last_name',
            ])
            ->orderBy('id', 'DESC')
            ->simplePaginate();

        return TimeClockLogResource::collection($timeClockLogs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Kirby\TimeClock\UI\API\Requests\CreateTimeClockLogRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTimeClockLogRequest $request)
    {
        $timeClockLog = $this->timeClockLogRepository->create($request->validated());

        if ($timeClockLog->checked_out_at) {
            event(new CheckedOutEvent($timeClockLog->id));
        }

        return new TimeClockLogResource($timeClockLog);
    }

    /**
     * Display the specified resource.
     *
     * @param  int                         $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request    $request
     * @param  int                         $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int                         $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
