<?php

namespace App\Application\Admin\Controllers;

use App\Domain\Applicants\Criteria\ShortlistedApplicantListCriteria;
use App\Domain\Applicants\Presenters\ApplicantShortListExportPresenter;
use App\Domain\Applicants\Repositories\ApplicationListRepository;
use App\Infrastructure\Support\Controller\Controller;
use Illuminate\Http\Request;
use Prettus\Repository\Exceptions\RepositoryException;
use Rap2hpoutre\FastExcel\FastExcel;

/**
 * Class ShortListingExportController
 * @package App\Application\Admin\Controllers
 */
class ShortListingExportController extends Controller
{
    /**
     * @var ApplicationListRepository
     */
    protected ApplicationListRepository $applicationListRepository;

    /**
     * ShortListingController constructor.
     *
     * @param ApplicationListRepository $applicationListRepository
     */
    public function __construct(ApplicationListRepository $applicationListRepository)
    {
        $this->applicationListRepository = $applicationListRepository;
    }

    /**
     * @param Request $request
     *
     * @return string|\Symfony\Component\HttpFoundation\StreamedResponse|void
     * @throws RepositoryException
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\InvalidArgumentException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
    public function exportScoredList(Request $request)
    {
        $district     = $request->get('district');
        $municipality = $request->get('municipality');
        $role         = $request->get('role');

        if ( !$district || !$municipality || !$role ) {
            return;
        }

        $this->applicationListRepository->pushCriteria(new ShortlistedApplicantListCriteria($request->only('district', 'municipality', 'role')));
        $this->applicationListRepository->setPresenter(ApplicantShortListExportPresenter::class);
        $applicants = $this->applicationListRepository->getShortlisted($municipality, $role)->all();

        return (new FastExcel($applicants))->download(sprintf('shortlist_%s_%s_%s_%s.xlsx', $district, $municipality, $role, now()));
    }

    /**
     * @param Request $request
     *
     * @return string|\Symfony\Component\HttpFoundation\StreamedResponse|void
     * @throws RepositoryException
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\InvalidArgumentException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
    public function exportShortlist(Request $request)
    {
        $district     = $request->get('district');
        $municipality = $request->get('municipality');
        $role         = $request->get('role');

        if ( !$district || !$municipality || !$role ) {
            return;
        }

        $this->applicationListRepository->pushCriteria(new ShortlistedApplicantListCriteria($request->only('district', 'municipality', 'role')));
        $this->applicationListRepository->setPresenter(ApplicantShortListExportPresenter::class);
        $applicants = $this->applicationListRepository->getOnlyShortlisted($municipality, $role)->all();

        return (new FastExcel($applicants))->download(sprintf('shortlist_%s_%s_%s_%s.xlsx', $district, $municipality, $role, now()));
    }
}
