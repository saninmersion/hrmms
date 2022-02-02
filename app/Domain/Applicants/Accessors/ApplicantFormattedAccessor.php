<?php

namespace App\Domain\Applicants\Accessors;

use App\Infrastructure\Constants\ApplicationConstants;
use App\Infrastructure\Constants\Ethnicities;
use App\Infrastructure\Constants\MotherTongues;

/**
 * Trait ApplicantFormattedAccessor
 * @package App\Domain\Applicants\Accessors
 */
trait ApplicantFormattedAccessor
{
    /**
     * @return string
     */
    public function getNameInEnglishFormattedAttribute(): string
    {
        return $this->nameFormatted($this->details->name_in_english ?? null);
    }

    /**
     * @return string
     */
    public function getNameInNepaliFormattedAttribute(): string
    {
        return $this->nameFormatted($this->details->name_in_nepali ?? null);
    }

    /**
     * @return string
     */
    public function getEthnicityFormattedAttribute(): string
    {
        if ( !isset($this->details->ethnicity) ) {
            return '';
        }

        if ( $this->details->ethnicity === Ethnicities::ETHNICITY_OTHER ) {
            return isset($this->details->ethnicity_other) ? $this->details->ethnicity_other : '';
        }

        return $this->details->ethnicity;
    }

    /**
     * @return string
     */
    public function getMotherTongueFormattedAttribute(): string
    {
        if ( !isset($this->details->mother_tongue) ) {
            return '';
        }

        if ( $this->details->mother_tongue === MotherTongues::LANGUAGE_OTHER ) {
            return isset($this->details->mother_tongue_other) ? $this->details->mother_tongue_other : '';
        }

        return $this->details->mother_tongue;
    }

    /**
     * @return string
     */
    public function getDisabilityFormattedAttribute(): string
    {
        if ( !isset($this->details->disability) ) {
            return '';
        }

        return trans("application.disabilities.{$this->details->disability}");
    }

    /**
     * @return string
     */
    public function getMotherNameFormattedAttribute(): string
    {
        return $this->nameFormatted($this->details->mother_name ?? null);
    }

    /**
     * @return string
     */
    public function getFatherNameFormattedAttribute(): string
    {
        return $this->nameFormatted($this->details->father_name ?? null);
    }

    /**
     * @return string
     */
    public function getGrandFatherNameFormattedAttribute(): string
    {
        return $this->nameFormatted($this->details->grand_father_name ?? null);
    }

    /**
     * @return string
     */
    public function getSpouseNameFormattedAttribute(): string
    {
        return $this->nameFormatted($this->details->spouse_name ?? null);
    }

    /**
     * @return string
     */
    public function getMajorSubjectSupervisorFormattedAttribute(): string
    {
        if ( !isset($this->details->qualification->education->supervisor->major_subject) ) {
            return '';
        }

        if ( $this->details->qualification->education->supervisor->major_subject === ApplicationConstants::MAJOR_SUBJECT_OTHERS ) {
            return isset($this->details->qualification->education->supervisor->major_subject_other) ? $this->details->qualification->education->supervisor->major_subject_other : '';
        }

        return $this->details->qualification->education->supervisor->major_subject;
    }

    /**
     * @return string
     */
    public function getMajorSubjectEnumeratorFormattedAttribute(): string
    {
        if ( !isset($this->details->qualification->education->enumerator->major_subject) ) {
            return '';
        }

        if ( $this->details->qualification->education->enumerator->major_subject === ApplicationConstants::MAJOR_SUBJECT_OTHERS ) {
            return isset($this->details->qualification->education->enumerator->major_subject_other) ? $this->details->qualification->education->enumerator->major_subject_other : '';
        }

        return $this->details->qualification->education->enumerator->major_subject;
    }

    /**
     * @return string
     */
    public function getMajorSubjectExtraFormattedAttribute(): string
    {
        if ( !isset($this->details->qualification->education->extra->major_subject) ) {
            return '';
        }

        if ( $this->details->qualification->education->extra->major_subject === ApplicationConstants::MAJOR_SUBJECT_OTHERS ) {
            return isset($this->details->qualification->education->extra->major_subject_other) ? $this->details->qualification->education->extra->major_subject_other : '';
        }

        return $this->details->qualification->education->extra->major_subject;
    }

    /**
     * @param object|null $name
     *
     * @return string
     */
    protected function nameFormatted(?object $name): string
    {
        if ( !$name ) {
            return '';
        }

        $fullName = [];

        if ( isset($name->first_name) && !empty($name->first_name) ) {
            $fullName[] = $name->first_name;
        }

        if ( isset($name->middle_name) && !empty($name->middle_name) ) {
            $fullName[] = $name->middle_name;
        }

        if ( isset($name->last_name) && !empty($name->last_name) ) {
            $fullName[] = $name->last_name;
        }

        return implode(' ', $fullName);
    }
}
