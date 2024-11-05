import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.setTheme(this.getPreferredTheme());
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            let storedTheme = this.getStoredTheme()
            if (storedTheme !== 'light' && storedTheme !== 'dark') {
                this.setTheme(this.getPreferredTheme())
            }
        });


        this.element.textContent = 'Hello Stimulus! Edit me in assets/controllers/theme_controller.js';
    }

    initialize() {
        this.showActiveTheme(this.getPreferredTheme())

        document.querySelectorAll('[data-bs-theme-value]')
            .forEach(toggle => {
                toggle.addEventListener('click', () => {
                    const theme = toggle.getAttribute('data-bs-theme-value')
                    this.setStoredTheme(theme)
                    this.setTheme(theme)
                    this.showActiveTheme(theme, true)
                })
            })
    }

    getStoredTheme = () => localStorage.getItem('theme')
    setStoredTheme = theme => localStorage.setItem('theme', theme)

    getPreferredTheme = () => {
        const storedTheme = getStoredTheme()
        if (storedTheme) {
            return storedTheme
        }

        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
    }

    setTheme = theme => {
        if (theme === 'auto') {
            document.documentElement.setAttribute('data-bs-theme', (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'))
        } else {
            document.documentElement.setAttribute('data-bs-theme', theme)
        }
    }



    showActiveTheme = (theme, focus = false) => {
        const themeSwitcher = document.querySelector('#bd-theme')

        if (!themeSwitcher) {
            return
        }

        const themeSwitcherText = document.querySelector('#bd-theme-text')
        const activeThemeIcon = document.querySelector('.theme-icon-active use')
        const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
        const svgOfActiveBtn = btnToActive.querySelector('svg use').getAttribute('href')

        document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
            element.classList.remove('active')
            element.setAttribute('aria-pressed', 'false')
        })

        btnToActive.classList.add('active')
        btnToActive.setAttribute('aria-pressed', 'true')
        activeThemeIcon.setAttribute('href', svgOfActiveBtn)
        const themeSwitcherLabel = `${themeSwitcherText.textContent} (${btnToActive.dataset.bsThemeValue})`
        themeSwitcher.setAttribute('aria-label', themeSwitcherLabel)

        if (focus) {
            themeSwitcher.focus()
        }
    }
}