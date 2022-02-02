<?php


namespace App\Domain\Applicants\Accessors;


use App\Infrastructure\Support\Helper;

trait ApplicationListViewFormattedAccessor
{

    /**
     * @return string
     */
    public function getSubmittedDateFormattedAttribute(): string
    {
        if ( !$this->application_date ) {
            return '';
        }

        return $this->application_date->toDateTimeString();
    }

    /**
     * @return string
     */
    public function getApplicationForFormattedAttribute(): string
    {
        if ( !$this->application_for ) {
            return '';
        }

        return trans("application.application-type-{$this->application_for}");
    }

    /**
     * @return string
     */
    public function getStatusFormattedAttribute(): string
    {
        if ( !$this->status ) {
            return '';
        }

        return trans("application.application-status.{$this->status}");
    }

    /**
     * @return string
     */
    public function getNameInEnglishFormattedAttribute(): string
    {
        /** @var object $name */
        $name = $this->name_in_english;

        return Helper::formatFullName($name);
    }

    /**
     * @return string
     */
    public function getNameInNepaliFormattedAttribute(): string
    {
        /** @var object $name */
        $name = $this->name_in_nepali;

        return Helper::formatFullName($name);
    }

    /**
     * @return string
     */
    public function getDobAdFormattedAttribute(): string
    {
        if ( !$this->dob_ad ) {
            return '';
        }

        return $this->dob_ad->toDateString();
    }

    /**
     * @return string
     */
    public function getGenderFormattedAttribute(): string
    {
        if ( !$this->gender ) {
            return '';
        }

        return trans("application.gender-{$this->gender}");
    }

    /**
     * @return string
     */
    public function getEthnicityFormattedAttribute(): string
    {
        if ( !$this->ethnicity ) {
            return '';
        }

        return trans("caste.{$this->ethnicity}");
    }

    /**
     * @return string
     */
    public function getMotherTongueFormattedAttribute(): string
    {
        if ( !$this->mother_tongue ) {
            return '';
        }

        return trans("mother_tongue.{$this->mother_tongue}");
    }

    /**
     * @return string
     */
    public function getDisabilityFormattedAttribute(): string
    {
        if ( !$this->disability ) {
            return '';
        }

        return trans("application.disabilities.{$this->disability}");
    }

    /**
     * @return string
     */
    public function getMotherNameFormattedAttribute(): string
    {
        /** @var object $name */
        $name = $this->mother_name;

        return Helper::formatFullName($name);
    }

    /**
     * @return string
     */
    public function getFatherNameFormattedAttribute(): string
    {
        /** @var object $name */
        $name = $this->father_name;

        return Helper::formatFullName($name);
    }

    /**
     * @return string
     */
    public function getGrandFatherNameFormattedAttribute(): string
    {
        /** @var object $name */
        $name = $this->grand_father_name;

        return Helper::formatFullName($name);
    }

    /**
     * @return string
     */
    public function getSpouseNameFormattedAttribute(): string
    {
        /** @var object $name */
        $name = $this->spouse_name;

        return Helper::formatFullName($name);
    }

    /**
     * @return string
     */
    public function getHasDegreeForSupervisorFormattedAttribute(): string
    {
        if ( is_null($this->degree_transcript_supervisor) ) {
            return '';
        }

        return $this->degree_transcript_supervisor ? trans('general.yes_i_have') : trans('general.no_i_have_not');
    }

    /**
     * @return string
     */
    public function getMajorSubjectSupervisorFormattedAttribute(): string
    {
        if ( !$this->major_subject_supervisor ) {
            return '';
        }

        return trans("application.major_subjects.{$this->major_subject_supervisor}");
    }

    /**
     * @return string
     */
    public function getHasDegreeForEnumeratorFormattedAttribute(): string
    {
        if ( is_null($this->degree_transcript_enumerator) ) {
            return '';
        }

        return $this->degree_transcript_enumerator ? trans('general.yes_i_have') : trans('general.no_i_have_not');
    }

    /**
     * @return string
     */
    public function getHasHigherDegreeFormattedAttribute(): string
    {
        if ( is_null($this->degree_transcript_extra) ) {
            return '';
        }

        return $this->degree_transcript_extra ? trans('general.yes_i_have') : trans('general.no_i_have_not');
    }

    /**
     * @return string
     */
    public function getMajorSubjectHigherFormattedAttribute(): string
    {
        if ( !$this->major_subject_extra ) {
            return '';
        }

        return trans("application.major_subjects.{$this->major_subject_extra}");
    }

    /**
     * @return string
     */
    public function getHasTrainingFormattedAttribute(): string
    {
        if ( is_null($this->has_training) ) {
            return '';
        }

        return $this->has_training ? trans('general.yes_i_have') : trans('general.no_i_have_not');
    }

    /**
     * @return string
     */
    public function getHasTrainingDocumentsFormattedAttribute(): string
    {
        if ( is_null($this->training_documents) ) {
            return '';
        }

        return $this->training_documents ? trans('general.yes_i_have') : trans('general.no_i_have_not');
    }

    /**
     * @return string
     */
    public function getHasExperienceFormattedAttribute(): string
    {
        if ( is_null($this->has_experience) ) {
            return '';
        }

        return $this->has_experience ? trans('general.yes_i_have') : trans('general.no_i_have_not');
    }

    /**
     * @return string
     */
    public function getHasExperienceDocumentsFormattedAttribute(): string
    {
        if ( is_null($this->experience_documents) ) {
            return '';
        }

        return $this->experience_documents ? trans('general.yes_i_have') : trans('general.no_i_have_not');
    }
}
