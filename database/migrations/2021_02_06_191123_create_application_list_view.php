<?php

use App\Infrastructure\Constants\DBTables;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Class CreateApplicationListView
 */
class CreateApplicationListView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $applicantsTable     = DBTables::AUTH_APPLICANTS;
        $applicationsTable   = DBTables::APPLICATIONS;
        $districtsTable      = DBTables::DISTRICTS;
        $municipalitiesTable = DBTables::MUNICIPALITIES;
        $viewName            = DBTables::VIEW_APPLICATIONS;

        $sql = <<<_SQL_
select aa.id
     , case
           when ta.status <> 'draft' then concat('NSCA-', LPAD(ta.id::varchar, 7, '0'))
           else null
    end::varchar(12)                                                                                     as submission_number
     , aa.created_at
     , ta.application_date
     , case
           when ta.for_enumerator is true and ta.for_supervisor is true then 'enumerator_supervisor'
           when ta.for_enumerator is true and ta.for_supervisor is false then 'enumerator'
           when ta.for_enumerator is false and ta.for_supervisor is true then 'supervisor'
           else null
    end::varchar(25)                                                                                     as application_for
     , ta.status

     -- priority locations
     , pd1.title_en::varchar(100)                                                                        as first_priority_district
     , pm1.title_en::varchar(100)                                                                        as first_priority_municipality
     , (ta.locations -> 'first' ->> 'ward')::smallint                                                    as first_priority_ward
     , pd2.title_en::varchar(100)                                                                        as second_priority_district
     , pm2.title_en::varchar(100)                                                                        as second_priority_municipality
     , (ta.locations -> 'second' ->> 'ward')::smallint                                                   as second_priority_ward

     -- personal info
     , (aa.details ->> 'name_in_english')::jsonb                                                         as name_in_english
     , (aa.details ->> 'name_in_nepali')::jsonb                                                          as name_in_nepali
     , (aa.details ->> 'gender')::varchar(10)                                                            as gender
     , (aa.details ->> 'ethnicity')::varchar(50)                                                         as ethnicity
     , (aa.details ->> 'mother_tongue')::varchar(50)                                                     as mother_tongue
     , (aa.details ->> 'disability')::varchar(50)                                                        as disability
     , (aa.details ->> 'dob_ad')::date                                                                   as dob_ad
     , aa.dob_bs::varchar(10)                                                                            as dob_bs

     -- citizenship
     , aa.citizenship_number
     , (cd.title_en)::varchar(100)                                                                       as citizenship_issued_district
     , (aa.citizenship_issued_date_bs)::varchar(10)                                                      as citizenship_issued_date_bs

     -- permanent address
     , (pd.title_en)::varchar(100)                                                                       as permanent_address_district
     , (pm.title_en)::varchar(100)                                                                       as permanent_address_municipality
     , (aa.permanent_address ->> 'ward')::smallint                                                       as permanent_address_ward

     -- temporary address
     , (td.title_en)::varchar(100)                                                                       as temporary_address_district
     , (tm.title_en)::varchar(100)                                                                       as temporary_address_municipality
     , (aa.current_address ->> 'ward')::smallint                                                         as temporary_address_ward

     , (aa.mobile_number)::varchar(10)                                                                   as mobile_number


     -- three generation info
     , (aa.details ->> 'mother_name')::jsonb                                                             as mother_name
     , (aa.details ->> 'father_name')::jsonb                                                             as father_name
     , (aa.details ->> 'grand_father_name')::jsonb                                                       as grand_father_name
     , (aa.details ->> 'spouse_name')::jsonb                                                             as spouse_name


     -- qualification for supervisor
     , case
           when ta.for_supervisor is false then null
           when
                   (aa.details -> 'qualification' ->> 'has_education_qualification')::bool
                   and aa.details ->> 'files_for_supervisor_education' is not null then true
           else false
    end                                                                                                  as degree_transcript_supervisor
     , case
           when ta.for_supervisor is false then null
           else aa.details -> 'qualification' -> 'education' -> 'supervisor' ->> 'major_subject'
    end::varchar(50)                                                                                     as major_subject_supervisor
     , case
           when ta.for_supervisor is false then null
           else aa.details -> 'qualification' -> 'education' -> 'supervisor' ->> 'major_subject_other'
    end::varchar(250)                                                                                    as major_subject_others_supervisor
     , case
           when ta.for_supervisor is false then null
           when aa.details -> 'qualification' -> 'education' -> 'supervisor' ->> 'grading_system' = 'percentage' then null
           else aa.details -> 'qualification' -> 'education' -> 'supervisor' ->> 'grade'
    end::varchar(20)                                                                                     as grade_supervisor
     , case
           when ta.for_supervisor is false then null
           when aa.details -> 'qualification' -> 'education' -> 'supervisor' ->> 'grading_system' = 'grade' then null
           else aa.details -> 'qualification' -> 'education' -> 'supervisor' ->> 'percentage'
    end::varchar(20)                                                                                     as percentage_supervisor


     -- qualification for enumerator
     , case
           when ta.for_enumerator is false then null
           when
                   (aa.details -> 'qualification' ->> 'has_education_qualification')::bool
                   and aa.details ->> 'files_for_enumerator_education' is not null then true
           else false
    end                                                                                                  as degree_transcript_enumerator
     , case
           when ta.for_enumerator is false then null
           when aa.details -> 'qualification' -> 'education' -> 'enumerator' ->> 'grading_system' = 'percentage' then null
           else aa.details -> 'qualification' -> 'education' -> 'enumerator' ->> 'grade'
    end::varchar(20)                                                                                     as grade_enumerator
     , case
           when ta.for_enumerator is false then null
           when aa.details -> 'qualification' -> 'education' -> 'enumerator' ->> 'grading_system' = 'grade' then null
           else aa.details -> 'qualification' -> 'education' -> 'enumerator' ->> 'percentage'
    end::varchar(20)                                                                                     as percentage_enumerator


     , case
           when aa.details ->> 'files_for_extra_education' is not null then true
           else false
    end                                                                                                  as degree_transcript_extra
     , (aa.details -> 'qualification' -> 'education' -> 'extra' ->> 'major_subject')::varchar(50)        as major_subject_extra
     , (aa.details -> 'qualification' -> 'education' -> 'extra' ->> 'major_subject_other')::varchar(250) as major_subject_others_extra


     , (aa.details -> 'qualification' ->> 'has_training')::bool                                          as has_training
     , case
           when aa.details ->> 'training_documents' is not null then true
           else false
    end                                                                                                  as training_documents
     , (aa.details -> 'qualification' -> 'training' ->> 'institute')::varchar(250)                       as training_institute
     , (aa.details -> 'qualification' -> 'training' ->> 'period')::varchar(5)                            as training_period_in_days


     , (aa.details -> 'qualification' ->> 'has_experience')::bool                                        as has_experience
     , case
           when aa.details ->> 'experience_documents' is not null then true
           else false
    end                                                                                                  as experience_documents
     , (aa.details -> 'qualification' -> 'experience' ->> 'organization')::varchar(255)                  as experience_organization
     , (aa.details -> 'qualification' -> 'experience' ->> 'period_month')::varchar(5)                    as experience_period_month
     , (aa.details -> 'qualification' -> 'experience' ->> 'period_day')::varchar(5)                      as experience_period_day

from {$applicantsTable} as aa
         left outer join {$applicationsTable} ta on aa.id = ta.applicant_id
         left outer join {$districtsTable} as pd1 on pd1.code = (ta.locations -> 'first' ->> 'district')::int
         left outer join {$municipalitiesTable} as pm1 on pm1.code = (ta.locations -> 'first' ->> 'municipality')::int
         left outer join {$districtsTable} as pd2 on pd2.code = (ta.locations -> 'second' ->> 'district')::int
         left outer join {$municipalitiesTable} as pm2 on pm2.code = (ta.locations -> 'second' ->> 'municipality')::int
         left outer join {$districtsTable} as cd on cd.code = aa.citizenship_issued_district_code
         left outer join {$districtsTable} as pd on pd.code = (aa.permanent_address ->> 'district')::int
         left outer join {$municipalitiesTable} as pm on pm.code = (aa.permanent_address ->> 'municipality')::int
         left outer join {$districtsTable} as td on td.code = (aa.current_address ->> 'district')::int
         left outer join {$municipalitiesTable} as tm on tm.code = (aa.current_address ->> 'municipality')::int

_SQL_;

        $this->dropView();

        DB::statement(
            "create materialized view {$viewName}
            as
            {$sql}
            with data;"
        );

        DB::statement("create unique index idx_applicant_id_on_application_view on {$viewName} (id);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropView();
    }

    protected function dropView()
    {
        $viewName = DBTables::VIEW_APPLICATIONS;

        DB::statement("drop index if exists idx_applicant_id_on_application_view;");
        DB::statement("drop materialized view if exists {$viewName};");
    }
}
