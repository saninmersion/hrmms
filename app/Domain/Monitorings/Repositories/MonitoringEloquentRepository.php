<?php

namespace App\Domain\Monitorings\Repositories;

use App\Domain\Monitorings\Models\Monitoring;
use App\Infrastructure\Support\Repository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class MonitoringEloquentRepository
 * @package App\Domain\Monitorings\Repositories
 */
class MonitoringEloquentRepository extends Repository implements MonitoringRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Monitoring::class;
    }

    /**
     * @param int $districtCode
     *
     * @return $this
     */
    public function byDistrict(int $districtCode): self
    {
        /** @var Builder $model */
        $model       = $this->model;
        $this->model = $model->where('district_code', $districtCode);

        return $this;
    }

    /**
     * @param int $enteredBy
     *
     * @return $this
     */
    public function byEnteredBy(int $enteredBy): self
    {
        /** @var Builder $model */
        $model       = $this->model;
        $this->model = $model->where('entered_by_id', $enteredBy);

        return $this;
    }

    /**
     * @param int $monitoredBy
     *
     * @return $this
     */
    public function byMonitoredBy(int $monitoredBy): self
    {
        /** @var Builder $model */
        $model       = $this->model;
        $this->model = $model->where('monitored_by_id', $monitoredBy);

        return $this;
    }

    public function countByFormAndDistrict(): array
    {
        $builder = $this->getBuilder();

        $builder = $builder->selectRaw("district_code, monitoring_form, count(monitoring_form)");
        $builder = $builder->groupByRaw("1, 2");
        $builder = $builder->orderByRaw("1");
        $stats   = [];
        $stats   = $builder->get()->reduce(
            function ($stats, $item) {
                $stats[$item['district_code']][$item['monitoring_form']] = $item['count'];

                return $stats;
            }
        );

        return $stats ?? [];
    }

    /**
     * @return array
     */
    public function countByFormAndMunicipality(): array
    {
        $builder = $this->getBuilder();

        $builder = $builder->selectRaw("municipality_code, monitoring_form, count(monitoring_form)");
        $builder = $builder->groupByRaw("1, 2");
        $builder = $builder->orderByRaw("1");
        $stats   = [];
        $stats   = $builder->get()->reduce(
            function ($stats, $item) {
                $stats[$item['municipality_code']][$item['monitoring_form']] = $item['count'];

                return $stats;
            }
        );

        return $stats ?? [];
    }

    public function countByFormAndMunicipalityForDistrict(int $districtCode): array
    {
        $builder = $this->getBuilder();

        $builder = $builder->selectRaw("monitoring_form, municipality_code, count(1) ");
        $builder = $builder->groupByRaw("2,1");
        $builder = $builder->where('district_code', '=', $districtCode);
        $stats   = [];
        $stats   = $builder->get()->reduce(
            function ($stats, $item) {
                $stats[$item['municipality_code']][$item['monitoring_form']] = $item['count'];

                return $stats;
            }
        );

        return $stats ?? [];
    }

    /**
     * @param int $districtCode
     *
     * @return array
     */
    public function countByFormForDistrict(int $districtCode): array
    {
        $builder = $this->getBuilder();

        $builder = $builder->selectRaw("monitoring_form, count(1)");
        $builder = $builder->groupByRaw("1");
        $builder = $builder->where('district_code', '=', $districtCode);

        return $builder->get()->keyBy('monitoring_form')->toArray();
    }

    /**
     * @param string $formType
     *
     * @return $this
     */
    public function byFormType(string $formType): self
    {
        /** @var Builder $model */
        $model       = $this->model;
        $this->model = $model->where('monitoring_form', $formType);

        return $this;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function byMonitoringType(string $type): self
    {
        /** @var Builder $model */
        $model       = $this->model;
        $this->model = $model->where('monitoring_form', $type);

        return $this;
    }
}
