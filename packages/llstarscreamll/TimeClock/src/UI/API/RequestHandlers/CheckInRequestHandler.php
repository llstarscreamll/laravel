<?php

namespace llstarscreamll\TimeClock\UI\API\RequestHandlers;

use Symfony\Component\HttpFoundation\Response;
use llstarscreamll\TimeClock\Events\CheckedInEvent;
use Illuminate\Http\Exceptions\HttpResponseException;
use llstarscreamll\TimeClock\Actions\LogCheckInAction;
use llstarscreamll\TimeClock\UI\API\Requests\CheckInRequest;
use llstarscreamll\TimeClock\Exceptions\TooLateToCheckException;
use llstarscreamll\TimeClock\Exceptions\TooEarlyToCheckException;
use llstarscreamll\TimeClock\Exceptions\AlreadyCheckedInException;
use llstarscreamll\TimeClock\UI\API\Resources\TimeClockLogResource;
use llstarscreamll\TimeClock\Exceptions\InvalidNoveltyTypeException;
use llstarscreamll\TimeClock\Exceptions\MissingSubCostCenterException;
use llstarscreamll\TimeClock\Exceptions\CanNotDeductWorkShiftException;

/**
 * Class CheckInRequestHandler.
 *
 * @author Johan Alvarez <llstarscreamll@hotmail.com>
 */
class CheckInRequestHandler
{
    /**
     * @param CheckInRequest   $request
     * @param LogCheckInAction $logCheckInAction
     */
    public function __invoke(
        CheckInRequest $request,
        LogCheckInAction $logCheckInAction
    ) {
        $errors = [];

        try {
            $timeClockLog = $logCheckInAction->run(
                $request->user(),
                $request->identification_code,
                $request->work_shift_id,
                $request->novelty_type,
                $request->sub_cost_center_id,
            );
        } catch (AlreadyCheckedInException $exception) {
            array_push($errors, [
                'code' => $exception->getCode(),
                'title' => 'Ya se registra una entrada.',
                'detail' => "Ya se ha registrado entrada en {$exception->checkedInAt}.",
            ]);
        } catch (TooEarlyToCheckException $exception) {
            array_push($errors, [
                'code' => $exception->getCode(),
                'title' => 'Es temprano para registrar la entrada.',
                'detail' => 'Si se llega temprano al turno, se debe registrar una novedad.',
                'meta' => $exception->timeClockData,
            ]);
        } catch (TooLateToCheckException $exception) {
            array_push($errors, [
                'code' => $exception->getCode(),
                'title' => 'Es tarde para registrar la entrada.',
                'detail' => 'Si se llega tarde al turno, se debe registrar una novedad.',
                'meta' => $exception->timeClockData,
            ]);
        } catch (CanNotDeductWorkShiftException $exception) {
            array_push($errors, [
                'code' => $exception->getCode(),
                'title' => 'No fue posible deducir el turno.',
                'detail' => 'No se pudo deducir el turno, se debe elegir uno '
                ."de {$exception->timeClockData['work_shifts']->count()} posibles.",
                'meta' => $exception->timeClockData,
            ]);
        } catch (InvalidNoveltyTypeException $exception) {
            array_push($errors, [
                'code' => $exception->getCode(),
                'title' => 'Tipo de novedad no válido.',
                'detail' => 'El tipo de novedad no es válido.',
                'meta' => $exception->timeClockData,
            ]);
        } catch (MissingSubCostCenterException $exception) {
            array_push($errors, [
                'code' => $exception->getCode(),
                'title' => 'Datos inválidos.',
                'detail' => 'Cuando se registra novedad que suma tiempo, se debe proveer el sub centro de costo.',
                'meta' => $exception->timeClockData,
            ]);
        }

        if ($errors) {
            throw new HttpResponseException(response()->json([
                'message' => 'Error registrando entrada!',
                'errors' => $errors,
            ], Response::HTTP_UNPROCESSABLE_ENTITY));
        }

        event(new CheckedInEvent($timeClockLog->id));

        return new TimeClockLogResource($timeClockLog);
    }
}
