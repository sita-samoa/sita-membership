import {
  mdiAccountCircle,
  mdiMonitor,
  mdiGithub,
  mdiLock,
  mdiAlertCircle,
  mdiSquareEditOutline,
  mdiTable,
  mdiViewList,
  mdiTelevisionGuide,
  mdiResponsive,
  mdiPalette,
  mdiReact,
} from "@mdi/js";

export default [
  {
    route: 'dashboard.index',
    icon: mdiMonitor,
    label: 'Dashboard',
  },
  {
    route: 'members.signup',
    label: 'Sign Up',
    icon: mdiTable,
  },
  {
    route: 'members.index',
    label: 'Members',
    icon: mdiSquareEditOutline,
  },
  {
    route: 'demo',
    icon: mdiMonitor,
    label: 'Demo Dashboard',
  },
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
