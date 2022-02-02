"use strict"

export default () => {
    const body = document.querySelector("body")
    const scrollEnabled = document.querySelector(".scroll-enabled")

    if (scrollEnabled) {
        const sectionArr = [...document.querySelectorAll(".card-js-scroll")]
        const sideBarLinks = [...document.querySelectorAll(".sidebar__link")]

        sideBarLinks.forEach((sideBarLink) => {
            sideBarLink.addEventListener("click", function(event) {
                event.preventDefault()

                const currentLinkIndex = sideBarLinks.indexOf(this)
                const currentSection = sectionArr[currentLinkIndex]
                const cordsCurrentSection = currentSection.getBoundingClientRect()
                const currentY = window.scrollY
                const scrollPoint = currentY + cordsCurrentSection.top - 32
                window.scroll({ top: scrollPoint, left: 0, behavior: "smooth" })
            })
        })
    }

    const dropdownMenus = document.querySelectorAll(".dropdown-menu")

    if (dropdownMenus.length) {
        dropdownMenus.forEach(dropdownMenu => {
            const dropdownBtn = dropdownMenu.querySelector(".dropdown-btn")
            dropdownBtn.addEventListener("click", function() {
                dropdownMenu.classList.add("dropdown--show")
            })
        })

        window.addEventListener("click", function(e) {
            if (!e.target.classList.contains("dropdown-btn")) {
                dropdownMenus.forEach(dropdownMenu => dropdownMenu.classList.remove("dropdown--show"))
            }
        })
    }

    const modalCloseIcon = document.querySelector(".ic-close--modal")

    if (modalCloseIcon) {
        modalCloseIcon.addEventListener("click", function() {
            document.querySelector(".show-modal").classList.remove("show-modal")
        })
    }

    const mobileNavTrigger = document.querySelector(".burger")
    const mobileNavCloser = document.querySelector(".close-menu")

    if (mobileNavTrigger) {
        mobileNavTrigger.addEventListener("click", function() {
            body.classList.add("show--nav")
        })
    }

    if (mobileNavCloser) {
        mobileNavCloser.addEventListener("click", function() {
            body.classList.remove("show--nav")
        })
    }

    const tabs = document.querySelectorAll(".tab")

    function tabify(tab) {
        const tabList = tab.querySelector(".tab__list")

        if (tabList) {
            const tabItems = [...tabList.children]
            const tabContent = tab.querySelector(".tab__content")
            const tabContentItems = [...tabContent.children]

            function setTab(index) {
                tabItems.forEach((x) => x.classList.remove("is--active"))
                tabContentItems.forEach((x) => x.classList.remove("is--active"))

                tabItems[index].classList.add("is--active")
                tabContentItems[index].classList.add("is--active")
            }

            tabItems.forEach((x, index) =>
                x.addEventListener("click", () => setTab(index)),
            )
        }
    }

    if (tabs.length) {
        tabs.forEach(tabify)
    }

    const btnCardShadow = document.querySelector(".card-shadow button")
    const cardShadowWrapper = document.querySelector(".card-shadow-wrap")

    if (btnCardShadow) {
        btnCardShadow.addEventListener("click", function() {
            cardShadowWrapper.classList.add("card--show")
        })
    }
}
