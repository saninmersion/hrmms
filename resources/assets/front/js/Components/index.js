"use strict"

import { PrimaryButton }        from "../../../shared/js/Components/Buttons"
import CheckShortlistStatusForm from "./Application/CheckShortlistStatusForm"
import ApplicationForm          from "./Application/Form"
import ApplicationPreview       from "./Application/Preview"
import DaysRemaining            from "./DaysRemaining"
import LanguageSwitcher         from "./LanguageSwitcher"
import LoginForm                from "./Login/LoginForm"

const components = {
    "application-form": ApplicationForm,
    "application-preview": ApplicationPreview,
    "language-switcher": LanguageSwitcher,
    "login-form": LoginForm,
    "primary-button": PrimaryButton,
    "days-remaining": DaysRemaining,
    "check-shortlist-status-form": CheckShortlistStatusForm,
}

export default components
