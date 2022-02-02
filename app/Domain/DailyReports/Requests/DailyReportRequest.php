<?php


namespace App\Domain\DailyReports\Requests;


use App\Domain\DailyReports\Repositories\DailyReportRepository;
use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\DBTables;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Requests\WebFormRequest;
use Illuminate\Validation\Rule;

/**
 * class DailyReportRequest
 */
class DailyReportRequest extends WebFormRequest
{
    /**
     * @var DailyReportRepository
     */
    protected DailyReportRepository $dailyReportRepository;

    /**
     * @var array $data
     */
    private array $data;

    /**
     * DailyReportRequest constructor.
     *
     * @param DailyReportRepository $dailyReportRepository
     */
    public function __construct(DailyReportRepository $dailyReportRepository)
    {
        parent::__construct();

        $this->dailyReportRepository = $dailyReportRepository;
    }

    public function rules(): array
    {
        /** @var User $user */
        $user             = AuthHelper::currentUser();
        $reportId         = (int) $this->route('report_id');
        $dailyReportTable = DBTables::DAILY_REPORTS;
        $rules            = [
            'report_date'    => [
                'required',
                'date',
                Rule::unique($dailyReportTable)->where(
                    function ($query) use ($user) {
                        return $query->where('enumerator_id', $this->input('enumerator_id'))->where('report_date', $this->input('report_date'))->where('created_by', $user->id);
                    }
                )->ignore($reportId),
            ],
            'total_surveyed' => ['required', 'integer', 'min:1'],
            'enumerator_id'  => ['required', Rule::exists(DBTables::ENUMERATORS, 'id')],
        ];

        return $rules;
    }

    /**
     * @return string[]
     */
    public function attributes()
    {
        return [
            'enumerator_id'  => 'गणक',
            'total_surveyed' => 'कुल सर्वेक्षण',
            'report_date'    => 'रिपोर्ट मिति',
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
            'report_date'      => $this->input('report_date'),
            'total_surveyed'   => (int) $this->input('total_surveyed'),
            'enumerator_id'    => (int) $this->input('enumerator_id'),
            'created_by'       => (int) $user->id,
            'district_code'    => (int) $user->district_code,
            'census_office_id' => (int) $user->census_office_id,
        ];

        return $this;
    }

    /**
     * @return $this
     */
    public function setFormForUpdate(): self
    {
        $this->data = [
            'report_date'    => $this->input('report_date'),
            'total_surveyed' => $this->input('total_surveyed'),
            'enumerator_id'  => $this->input('enumerator_id'),
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
