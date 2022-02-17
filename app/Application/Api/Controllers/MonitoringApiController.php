<?php

namespace App\Application\Api\Controllers;

use _HumbugBoxa9bfddcdef37\Nette\Neon\Exception;
use App\Application\Api\Requests\EnumeratorMonitoringFormApiRequest;
use App\Domain\Monitorings\Models\Monitoring;
use App\Domain\Monitorings\Repositories\MonitoringRepository;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\Guard;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Controller\ApiController;
use Illuminate\Http\JsonResponse;

/**
 * Class MonitoringController
 *
 * @package App\Application\Api\Controllers
 */
class MonitoringApiController extends ApiController
{
    /**
     * @var MonitoringRepository
     */
    protected MonitoringRepository $monitoringRepository;

    /**
     * MonitoringApiController constructor.
     *
     * @param MonitoringRepository $monitoringRepository
     */
    public function __construct(MonitoringRepository $monitoringRepository)
    {
        $this->monitoringRepository = $monitoringRepository;
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        /** @var User $user */
        $user = AuthHelper::currentUser(Guard::API);

        try {
            $monitorings = $this->monitoringRepository->byFormType(ApplicationConstants::MONITORING_FORM_ENUMERATOR)->byMonitoredBy(optional($user)->id)->all();

            return $this->sendResponse(
                [
                    'monitorings' => $monitorings,
                ],
                'List Of Enumerator monitorings'
            );
        } catch (\Exception $exception) {
            logger()->error($exception);

            return $this->sendError("Error fetching Monitoring list");
        }
    }

    /**
     * @return JsonResponse
     */
    public function storeEnumeratorMonitoring(EnumeratorMonitoringFormApiRequest $request)
    {
        try {
            $monitoring = $request->setEnumeratorForm()->store();

            return $this->sendResponse(
                [
                    'monitoring' => $monitoring,
                ],
                'Enumerator Monitoring form created'
            );
        } catch (\Exception $exception) {
            logger()->error($exception);

            return $this->sendError("Error creating Enumerator monitoring form");
        }
    }

    /**
     * @param EnumeratorMonitoringFormApiRequest $request
     * @param int                                $monitoringId
     *
     * @return JsonResponse
     */
    public function updateEnumeratorMonitoring(EnumeratorMonitoringFormApiRequest $request, $monitoringId)
    {
        try {
            $monitoring = $request->setEnumeratorFormForUpdate()->update((int) $monitoringId);

            return $this->sendResponse(
                [
                    'monitoring' => $monitoring,
                ],
                'Enumerator Monitoring form updated'
            );
        } catch (\Exception $exception) {
            logger()->error($exception);

            return $this->sendError("Error updating Enumerator monitoring form");
        }
    }

    /**
     * @param int $monitoringFormId
     *
     * @return JsonResponse
     */
    public function destroy($monitoringFormId)
    {
        try {
            /** @var User $user */
            $user = AuthHelper::currentUser(Guard::API);

            /** @var Monitoring|null $monitoring */
            $monitoring = $this->monitoringRepository->find($monitoringFormId);

            if ( !$monitoring || $monitoring->monitored_by_id !== $user->id ) {
                throw new \Exception();
            }

            $this->monitoringRepository->delete($monitoringFormId);

            return $this->sendResponse(
                [],
                'Enumerator Monitoring Deleted'
            );
        } catch (\Exception $exception) {
            logger()->error($exception);

            return $this->sendError("Error deleting Enumerator monitoring");
        }
    }
}
