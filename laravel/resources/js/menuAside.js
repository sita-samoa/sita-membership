import { mdiMonitor, mdiGithub, mdiViewList, mdiClipboardText } from '@mdi/js'

export default [
  {
    route: 'dashboard.index',
    icon: mdiMonitor,
    label: 'Dashboard',
    permissionKey: null,
  },
  {
    route: 'members.signup',
    label: 'Sign Up',
    icon: mdiClipboardText,
    permissionKey: null,
  },
  {
    route: 'members.index',
    label: 'Members',
    icon: mdiViewList,
    permissionKey: 'canReadAny',
  },
  // {
  //   route: 'demo',
  //   icon: mdiChartTimelineVariant,
  //   label: 'Demo',
  // },
  // {
  //   route: "/ui",
  //   label: "UI",
  //   icon: mdiTelevisionGuide,
  // },
  // {
  //   route: "/responsive",
  //   label: "Responsive",
  //   icon: mdiResponsive,
  // },
  // {
  //   route: "/",
  //   label: "Styles",
  //   icon: mdiPalette,
  // },
  // {
  //   route: "/profile",
  //   label: "Profile",
  //   icon: mdiAccountCircle,
  // },
  // {
  //   route: "/login",
  //   label: "Login",
  //   icon: mdiLock,
  // },
  // {
  //   route: "/error",
  //   label: "Error",
  //   icon: mdiAlertCircle,
  // },
  // {
  //   label: 'Teams',
  //   icon: mdiViewList,
  //   menu: [
  //     {
  //       label: 'Manage',
  //     },
  //     {
  //       label: 'Item Two',
  //     },
  //   ],
  // },
  {
    href: 'https://github.com/sita-samoa/sita-membership',
    label: 'GitHub',
    icon: mdiGithub,
    target: '_blank',
  },
]
