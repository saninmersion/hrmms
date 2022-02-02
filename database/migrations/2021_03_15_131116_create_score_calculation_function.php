<?php

use App\Infrastructure\Constants\DBTables;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Class CreateScoreCalculationFunction
 */
class CreateScoreCalculationFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $applicationForTypeSql = "
            do
            $$
                begin
                    create type application_for as enum ('enumerator', 'supervisor');
                exception
                    when duplicate_object then null ;
                end;
            $$;
        ";

        $viewApplications  = DBTables::VIEW_APPLICATIONS;
        $calculateScoreSql = "
            create or replace function calculate_score(applicant {$viewApplications}, role application_for)
                returns int
                language plpgsql
                immutable
            as
            $$
            declare
                score integer = 0;
            begin
                -- if not submitted return 0
                if applicant.status is null or applicant.status != 'submitted' then
                    return 0;
                end if;

                -- only calculate score for given roles
                if (role = 'enumerator' and (applicant.application_for = any ('{enumerator_supervisor,enumerator}'::text[])) is false)
                    or (role = 'supervisor' and (applicant.application_for = any ('{enumerator_supervisor,supervisor}'::text[])) is false)
                then
                    return 0;
                end if;

                -- gender
                if applicant.gender = 'female' then
                    score := score + 5;
                end if;

                -- major subject is population or stats (only for supervisor)
                if role = 'supervisor' and applicant.major_subject_supervisor is not null and applicant.major_subject_supervisor <> 'others' then
                    score := score + 10;
                end if;

                -- has training
                if applicant.has_training is true and applicant.training_documents is true then
                    score := score + 10;
                end if;

                -- has experience
                if applicant.has_experience is true and applicant.experience_documents is true then
                    score := score + 10;
                end if;

                -- higher education
                if applicant.degree_transcript_extra is true then
                    score := score + 10;
                end if;

                -- location
                if applicant.first_priority_municipality = applicant.permanent_address_municipality or applicant.first_priority_municipality = applicant.temporary_address_municipality then
                    score := score + 30;
                elsif applicant.first_priority_district = applicant.permanent_address_district or applicant.first_priority_district = applicant.temporary_address_district then
                    score := score + 20;
                elsif applicant.second_priority_municipality = applicant.permanent_address_municipality or applicant.second_priority_municipality = applicant.temporary_address_municipality then
                    score := score + 15;
                elsif applicant.second_priority_district = applicant.permanent_address_district or applicant.second_priority_district = applicant.temporary_address_district then
                    score := score + 10;
                end if;

                return score;
            end;
            $$;
        ";

        DB::unprepared($applicationForTypeSql);
        DB::unprepared($calculateScoreSql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("drop function if exists calculate_score;");
        DB::unprepared("drop type if exists application_for;");
    }
}
