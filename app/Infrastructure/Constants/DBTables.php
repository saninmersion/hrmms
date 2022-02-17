<?php

namespace App\Infrastructure\Constants;

/**
 * Class DBTables
 *
 * @package App\Infrastructure\Constants
 */
final class DBTables
{
    public const SYS_MIGRATIONS    = 'sys_migrations';
    public const SYS_FAILED_JOBS   = 'sys_failed_jobs';
    public const SYS_SESSIONS      = 'sys_sessions';
    public const SYS_AUDITS        = 'sys_audits';
    public const SYS_NOTIFICATIONS = 'notifications';
    public const SYS_MEDIAS        = 'sys_medias';
    public const SYS_MEDIAABLE     = 'sys_mediaable';

    public const AUTH_USERS           = 'auth_users';
    public const AUTH_PASSWORD_RESETS = 'auth_password_resets';
    public const AUTH_APPLICANTS      = 'auth_applicants';

    public const PROVINCES      = 'tbl_provinces';
    public const DISTRICTS      = 'tbl_districts';
    public const MUNICIPALITIES = 'tbl_municipalities';
    public const CENSUS_OFFICES = 'tbl_census_offices';

    public const APPLICATIONS              = 'tbl_applications';
    public const APPLICANT_EXPORTS         = 'tbl_applicant_exports';
    public const APPLICANT_VERIFICATIONS   = 'tbl_applicant_verifications';
    public const APPLICATION_DOWNLOAD_LOGS = 'tbl_application_download_logs';

    public const LAST_UPDATED_TABLE  = 'tbl_last_updated_table';
    public const VERIFIER_ASSIGNMENT = 'tbl_verifier_assignments';

    public const MONITORINGS         = 'tbl_monitorings';
    public const SHORTLISTED         = 'tbl_shortlisted';
    public const DAILY_REPORTS       = 'tbl_daily_reports';
    public const HOUSE_DAILY_REPORTS = 'tbl_house_daily_reports';
    public const ENUMERATORS         = 'tbl_enumerators';

    public const VIEW_APPLICATIONS = 'view_applications';
}
