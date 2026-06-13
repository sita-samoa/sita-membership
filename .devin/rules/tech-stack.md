---
trigger: model_decision
description: Include at start of each new converstaion
---

# Frontend Tech Stack Overview

1. **Build system**: [Vite](https://vitejs.dev/) (`^4.5.9`)
2. **Frontend framework**: [Vue 3](https://vuejs.org/) (`^3.2.31`) with [Inertia.js Vue 3](https://inertiajs.com/) (`^1.0.0`)
3. **State management**: [Pinia](https://pinia.vuejs.org/) (`^2.1.3`)
4. **Styling**: [Tailwind CSS](https://tailwindcss.com/) (`^3.1.0`) with official plugins for forms, typography, and line-clamp
5. **Component libraries**: [Flowbite](https://flowbite.com/) (`^2.3.0`), [Flowbite Vue](https://flowbite.com/docs/components/vue/) (`^0.1.5`), [Vue Material Design Icons](https://github.com/robcresswell/vue-material-design-icons) (`^5.2.0`)
6. **Linting & formatting**: [ESLint](https://eslint.org/) (`^8.41.0`), [Prettier](https://prettier.io/) (`^2.8.8`), [husky](https://typicode.github.io/husky/) (`^8.0.3`), [lint-staged](https://github.com/okonet/lint-staged) (`^13.2.2`), [pretty-quick](https://github.com/azz/pretty-quick) (`^3.1.3`)
7. **Testing framework**: *Not explicitly listed in package.json* (none detected)
8. **Other major dependencies**:
   - [Axios](https://axios-http.com/) (`^1.7.4`)
   - [Day.js](https://day.js.org/) (`^1.11.7`)
   - [Lodash](https://lodash.com/) (`^4.17.21`)
   - [Chart.js](https://www.chartjs.org/) (`^4.3.0`)
   - [Sentry for Vue](https://docs.sentry.io/platforms/javascript/guides/vue/) (`^8.33.1`)
   - [Vue Recaptcha v3](https://www.npmjs.com/package/vue-recaptcha-v3) (`^2.0.1`)

---

This file summarizes the main frontend technologies and tools used in this project based on `/laravel/package.json`. Please refer to the file for the complete list of dependencies.


# Backend Tech Stack Overview

1. **Main framework**: [Laravel](https://laravel.com/) (`^10.8`)
2. **Programming language**: PHP (`^8.1`)
3. **API & HTTP**: [GuzzleHTTP](https://docs.guzzlephp.org/) (`^7.2`), [Inertia.js Laravel](https://inertiajs.com/server-side-setup) (`^0.6.8`)
4. **Authentication & Security**: [Laravel Jetstream](https://jetstream.laravel.com/) (`^3.1`), [Laravel Sanctum](https://laravel.com/docs/10.x/sanctum) (`^3.2`)
5. **Database & Data**:
   - [Maatwebsite Excel](https://docs.laravel-excel.com/) (`^3.1`)
   - [Laraveldaily Invoices](https://laraveldaily.com/package/laravel-invoices/) (`^3.1`)
   - [Monarobase Country List](https://github.com/Monarobase/country-list) (`^3.4`)
   - [OwenIt Laravel Auditing](https://github.com/owen-it/laravel-auditing) (`^13.5`)
6. **Monitoring & Error Reporting**: [Sentry Laravel](https://docs.sentry.io/platforms/php/guides/laravel/) (`^4.6`)
7. **Backup & Utilities**: [Spatie Laravel Backup](https://spatie.be/docs/laravel-backup) (`^8.1`), [TightenCo Ziggy](https://github.com/tighten/ziggy) (`^1.0`)
8. **Development tools**:
   - [Laravel Sail](https://laravel.com/docs/10.x/sail) (`^1.18`)
   - [Laravel Pint](https://laravel.com/docs/10.x/pint) (`^1.0`)
   - [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) (`^3.7`)
   - [Rector](https://github.com/rectorphp/rector) (`^0.16.0`), [Rector Laravel](https://github.com/driftingly/rector-laravel) (`^0.20.0`)
   - [FakerPHP Faker](https://fakerphp.github.io/) (`^1.9.1`)
   - [Mockery](https://github.com/mockery/mockery) (`^1.4.4`)
   - [Nunomaduro Collision](https://github.com/nunomaduro/collision) (`^7.0`)
   - [Spatie Laravel Ignition](https://spatie.be/docs/laravel-ignition) (`^2.0`)
9. **Testing framework**: [PestPHP](https://pestphp.com/) (`^2.0`), [PestPHP Laravel Plugin](https://pestphp.com/docs/laravel) (`^2.0`)

---

This file summarizes the main backend technologies and tools used in this project based on `/laravel/composer.json`. Please refer to the file for the complete list of dependencies.
