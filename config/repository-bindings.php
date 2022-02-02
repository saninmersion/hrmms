<?php

use App\Domain\AdminDivisions\Repositories\DistrictEloquentRepository;
use App\Domain\AdminDivisions\Repositories\DistrictRepository;
use App\Domain\AdminDivisions\Repositories\MunicipalityRepository;
use App\Domain\AdminDivisions\Repositories\MunicipalityRepositoryEloquent;
use App\Domain\AdminDivisions\Repositories\ProvinceRepository;
use App\Domain\AdminDivisions\Repositories\ProvinceRepositoryEloquent;
use App\Domain\Applicants\Repositories\ApplicantEloquentRepository;
use App\Domain\Applicants\Repositories\ApplicantExportEloquentRepository;
use App\Domain\Applicants\Repositories\ApplicantExportRepository;
use App\Domain\Applicants\Repositories\ApplicantRepository;
use App\Domain\Applicants\Repositories\ApplicantVerificationEloquentRepository;
use App\Domain\Applicants\Repositories\ApplicantVerificationRepository;
use App\Domain\Applicants\Repositories\ApplicationListEloquentRepository;
use App\Domain\Applicants\Repositories\ApplicationListRepository;
use App\Domain\Applicants\Repositories\DownloadLogEloquentRepository;
use App\Domain\Applicants\Repositories\DownloadLogRepository;
use App\Domain\Applicants\Repositories\ShortListedApplicantEloquentRepository;
use App\Domain\Applicants\Repositories\ShortListedApplicantRepository;
use App\Domain\Applicants\Repositories\VerifierAssignmentEloquentRepository;
use App\Domain\Applicants\Repositories\VerifierAssignmentRepository;
use App\Domain\Applications\Repositories\ApplicationEloquentRepository;
use App\Domain\Applications\Repositories\ApplicationRepository;
use App\Domain\CensusOffices\Repositories\CensusOfficeEloquentRepository;
use App\Domain\CensusOffices\Repositories\CensusOfficeRepository;
use App\Domain\DailyReports\Repositories\DailyReportEloquentRepository;
use App\Domain\DailyReports\Repositories\DailyReportRepository;
use App\Domain\Enumerators\Repositories\EnumeratorEloquentRepository;
use App\Domain\Enumerators\Repositories\EnumeratorRepository;
use App\Domain\HouseDailyReports\Repositories\HouseDailyReportEloquentRepository;
use App\Domain\HouseDailyReports\Repositories\HouseDailyReportRepository;
use App\Domain\LastUpdated\Repositories\LastUpdatedEloquentRepository;
use App\Domain\LastUpdated\Repositories\LastUpdatedRepository;
use App\Domain\Medias\Repositories\MediaEloquentRepository;
use App\Domain\Medias\Repositories\MediaRepository;
use App\Domain\Monitorings\Repositories\MonitoringEloquentRepository;
use App\Domain\Monitorings\Repositories\MonitoringRepository;
use App\Domain\Users\Repositories\UserEloquentRepository;
use App\Domain\Users\Repositories\UserRepository;

return [
    MediaRepository::class                 => MediaEloquentRepository::class,
    UserRepository::class                  => UserEloquentRepository::class,
    ProvinceRepository::class              => ProvinceRepositoryEloquent::class,
    DistrictRepository::class              => DistrictEloquentRepository::class,
    MunicipalityRepository::class          => MunicipalityRepositoryEloquent::class,
    ApplicantRepository::class             => ApplicantEloquentRepository::class,
    ApplicationRepository::class           => ApplicationEloquentRepository::class,
    ApplicantExportRepository::class       => ApplicantExportEloquentRepository::class,
    DownloadLogRepository::class           => DownloadLogEloquentRepository::class,
    ApplicationListRepository::class       => ApplicationListEloquentRepository::class,
    LastUpdatedRepository::class           => LastUpdatedEloquentRepository::class,
    ApplicantVerificationRepository::class => ApplicantVerificationEloquentRepository::class,
    VerifierAssignmentRepository::class    => VerifierAssignmentEloquentRepository::class,
    MonitoringRepository::class            => MonitoringEloquentRepository::class,
    ShortListedApplicantRepository::class  => ShortListedApplicantEloquentRepository::class,
    CensusOfficeRepository::class          => CensusOfficeEloquentRepository::class,
    DailyReportRepository::class           => DailyReportEloquentRepository::class,
    HouseDailyReportRepository::class      => HouseDailyReportEloquentRepository::class,
    EnumeratorRepository::class            => EnumeratorEloquentRepository::class,
];
