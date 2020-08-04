/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const adminRoutes = {
  path: '/admin',
  component: Layout,
  redirect: '/admin/users',
  name: 'Administrator',
  hidden: true,
  meta: {
    title: 'administrator',
    icon: 'el-icon-setting',
    permissions: ['view menu administrator'],
  },
  children: [
    /** User managements */
    {
      path: 'users/edit/:id(\\d+)',
      component: () => import('@/views/users/UserProfile'),
      name: 'UserProfile',
      meta: { title: 'userProfile', noCache: true, permissions: ['manage user'] },
      hidden: true,
    },
    {
      path: 'users',
      component: () => import('@/views/users/List'),
      name: 'UserList',
      hidden: true,
      meta: {
        title: 'users',
        icon: 'el-icon-user',
        permissions: ['manage user'],
      },
    },
    /** Role and permission */
    {
      path: 'roles',
      component: () => import('@/views/role-permission/List'),
      name: 'RoleList',
      hidden: true,
      meta: {
        title: 'rolePermission',
        icon: 'el-icon-lock',
        permissions: ['manage permission'],
      },
    },
  ],
};

export default adminRoutes;
