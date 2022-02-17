<?php

namespace App\Application\Admin\InertiaJs;

use App\Domain\Users\Models\User;
use App\Infrastructure\Constants\AuthRoles;
use App\Infrastructure\Support\AuthHelper;
use App\Infrastructure\Support\Contracts\InertiaDataSharable;

/**
 * Class Policy
 *
 * @package App\Application\Admin\InertiaJs
 */
class Policy implements InertiaDataSharable
{
    /**
     * @return array|\Closure
     */
    public function data()
    {
        return function () {
            if ( !AuthHelper::currentUser() ) {
                return null;
            }

            /** @var User $user */
            $user = AuthHelper::currentUser();

            /** @var string $role */
            $role = $user->role ?? '';

            return [
                'verification'           => in_array($role, [AuthRoles::VERIFIERS]),
                'offline_application'    => config('config.offline-application-available') && in_array($role, [AuthRoles::SUPER_ADMIN]),
                'offline_index'          => config('config.offline-application-available') && in_array($role, [AuthRoles::SUPER_ADMIN]),
                'hr_module'              => in_array($role, [AuthRoles::ADMIN, AuthRoles::STAFFS, AuthRoles::DISTRICT_STAFFS, AuthRoles::SUPER_ADMIN, AuthRoles::VERIFIERS, AuthRoles::OPERATOR]),
                'dashboard'              => in_array($role, [AuthRoles::ADMIN, AuthRoles::STAFFS, AuthRoles::DISTRICT_STAFFS, AuthRoles::SUPER_ADMIN]),
                'user'                   => in_array($role, [AuthRoles::ADMIN, AuthRoles::SUPER_ADMIN]),
                'district_dashboard'     => in_array($role, [AuthRoles::ADMIN, AuthRoles::STAFFS, AuthRoles::SUPER_ADMIN]),
                'applicants'             => in_array($role, [AuthRoles::ADMIN, AuthRoles::STAFFS, AuthRoles::SUPER_ADMIN]),
                'shortlisting'           => config('config.shortlist-available') && in_array($role, [AuthRoles::SUPER_ADMIN]),
                'district_shortlisting'  => config('config.shortlist-available') && in_array($role, [AuthRoles::SUPER_ADMIN, AuthRoles::DISTRICT_STAFFS]),
                'export'                 => in_array($role, [AuthRoles::ADMIN, AuthRoles::SUPER_ADMIN]),
                'monitoring'             => in_array($role, [AuthRoles::SUPERVISOR, AuthRoles::STAFFS, AuthRoles::MONITORING, AuthRoles::OPERATOR, AuthRoles::DISTRICT_STAFFS, AuthRoles::ADMIN, AuthRoles::SUPER_ADMIN]),
                'activate_monitor'       => in_array($role, [AuthRoles::STAFFS, AuthRoles::ADMIN, AuthRoles::SUPER_ADMIN]),
                'monitoring_export'      => in_array($role, [AuthRoles::ADMIN, AuthRoles::SUPER_ADMIN, AuthRoles::DISTRICT_STAFFS]),
                'monitoring_dashboard'   => in_array($role, [AuthRoles::STAFFS, AuthRoles::ADMIN, AuthRoles::SUPER_ADMIN, AuthRoles::DISTRICT_STAFFS]),
                'monitoring_general'     => in_array($role, [AuthRoles::MONITORING, AuthRoles::OPERATOR, AuthRoles::DISTRICT_STAFFS, AuthRoles::STAFFS, AuthRoles::ADMIN, AuthRoles::SUPER_ADMIN, AuthRoles::SUPERVISOR]),
                'monitoring_supervisor'  => in_array($role, [AuthRoles::MONITORING, AuthRoles::OPERATOR, AuthRoles::DISTRICT_STAFFS, AuthRoles::STAFFS, AuthRoles::ADMIN, AuthRoles::SUPER_ADMIN]),
                'monitoring_enumerator'  => in_array($role, [AuthRoles::SUPERVISOR, AuthRoles::MONITORING, AuthRoles::OPERATOR, AuthRoles::DISTRICT_STAFFS, AuthRoles::STAFFS, AuthRoles::ADMIN, AuthRoles::SUPER_ADMIN]),
                'monitoring_list'        => in_array($role, [AuthRoles::SUPERVISOR, AuthRoles::MONITORING, AuthRoles::OPERATOR, AuthRoles::DISTRICT_STAFFS, AuthRoles::STAFFS, AuthRoles::ADMIN, AuthRoles::SUPER_ADMIN]),
                'daily_report'           => in_array($role, [AuthRoles::SUPERVISOR, AuthRoles::SUPER_ADMIN, AuthRoles::ADMIN]),
                'daily_report_create'    => in_array($role, [AuthRoles::SUPERVISOR]),
                'house_report_export'    => in_array($role, [AuthRoles::ADMIN, AuthRoles::SUPER_ADMIN]),
                'daily_report_dashboard' => in_array($role, [AuthRoles::SUPER_ADMIN, AuthRoles::ADMIN, AuthRoles::DISTRICT_STAFFS]),
                'enumerator'             => in_array($role, [AuthRoles::SUPERVISOR]),
            ];
        };
    }

    /**
     * @return string
     */
    public function key()
    : string
    {
        return 'can';
    }
}
