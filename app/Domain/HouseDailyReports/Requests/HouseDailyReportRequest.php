<?php


namespace App\Domain\HouseDailyReports\Requests;


use App\Domain\HouseDailyReports\Repositories\HouseDailyReportRepository;
use App\Domain\Users\Models\User;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Requests\WebFormRequest;

/**
 * class HouseDailyReportRequest
 */
class HouseDailyReportRequest extends WebFormRequest
{
    /**
     * @var HouseDailyReportRepository
     */
    protected HouseDailyReportRepository $dailyReportRepository;

    /**
     * @var array $data
     */
    private array $data;

    /**
     * HouseDailyReportRequest constructor.
     *
     * @param HouseDailyReportRepository $dailyReportRepository
     */
    public function __construct(HouseDailyReportRepository $dailyReportRepository)
    {
        parent::__construct();

        $this->dailyReportRepository = $dailyReportRepository;
    }

    public function rules(): array
    {
        return [
            'report_date'                  => ['required', 'date'],
            'total_surveyed'               => ['required', 'integer', 'min:1'],
            'number_of_houses_in_census'   => ['required', 'integer', 'min:1'],
            'number_of_families_in_census' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * @return string[]
     */
    public function attributes()
    {
        return [
            'report_date'                  => 'रिपोर्ट मिति',
            'total_surveyed'               => 'घर संख्या',
            'number_of_families_in_census' => 'जनगणना घर संख्या',
            'number_of_houses_in_census'   => 'जनगणना परिवार संख्या',
        ];
    }

    /**
     * @return $this
     */
    public function setForm(): self
    {
        /** @var User $user */
        $user       = AuthHelper::currentUser();
        $this->data = [
            'report_date'                  => $this->input('report_date'),
            'total_surveyed'               => (int) $this->input('total_surveyed'),
            'number_of_houses_in_census'   => (int) $this->input('number_of_houses_in_census'),
            'number_of_families_in_census' => (int) $this->input('number_of_families_in_census'),
            'created_by'                   => (int) $user->id,
            'district_code'                => (int) $user->district_code,
            'census_office_id'             => (int) $user->census_office_id,
        ];

        return $this;
    }

    /**
     * @return $this
     */
    public function setFormForUpdate(): self
    {
        $this->data = [
            'report_date'                  => $this->input('report_date'),
            'total_surveyed'               => $this->input('total_surveyed'),
            'number_of_houses_in_census'   => $this->input('number_of_houses_in_census'),
            'number_of_families_in_census' => $this->input('number_of_families_in_census'),
        ];

        return $this;
    }

    /**
     * @return mixed
     */
    public function store()
    {
        return $this->dailyReportRepository->create($this->data);
    }

    /**
     * @return mixed
     */
    public function update(int $reportId)
    {
        return $this->dailyReportRepository->update($this->data, $reportId);
    }
}
