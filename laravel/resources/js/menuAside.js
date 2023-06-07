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
    route: 'dashboard1',
    icon: mdiMonitor,
    label: 'Dashboard',
  },
  {
    route: 'dashboard.index',
    icon: mdiMonitor,
    label: 'Old Dashboard',
  },
  {
    route: 'tables',
    label: 'Tables',
    icon: mdiTable,
  },
  // {
  //   route: "/forms",
  //   label: "Forms",
  //   icon: mdiSquareEditOutline,
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
  {
    label: 'Dropdown',
    icon: mdiViewList,
    menu: [
      {
        label: 'Item One',
      },
      {
        label: 'Item Two',
      },
    ],
  },
  {
    href: 'https://github.com/sita-samoa/sita-membership',
    label: 'GitHub',
    icon: mdiGithub,
    target: '_blank',
  },
]
