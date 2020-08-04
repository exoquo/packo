<template>
  <div class="navbar">
    <hamburger
      id="hamburger-container"
      :is-active="sidebar.opened"
      class="hamburger-container"
      @toggleClick="toggleSideBar"
    />
    <breadcrumb id="breadcrumb-container" class="breadcrumb-container" />
    <div class="right-menu">
      <el-dropdown class="avatar-container right-menu-item hover-effect" trigger="click">
        <div class="avatar-wrapper">
          <i class="el-icon-user" />
          {{ user.name }}
        </div>
        <el-dropdown-menu slot="dropdown">
          <router-link v-show="userId !== null" :to="`/profile/edit`">
            <el-dropdown-item>{{ $t('navbar.profile') }}</el-dropdown-item>
          </router-link>
          <el-dropdown-item divided>
            <span style="display:block;" @click="logout">{{ $t('navbar.logOut') }}</span>
          </el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
      <el-dropdown v-role="['admin']" class="right-menu-item hover-effect" trigger="click">
        <i class="el-icon-setting" />
        <el-dropdown-menu slot="dropdown">
          <router-link :to="`/admin/users`">
            <el-dropdown-item><i class="el-icon-user" /> Benutzer</el-dropdown-item>
          </router-link>
          <el-dropdown-item divided />
          <router-link :to="`/admin/roles`">
            <el-dropdown-item><i class="el-icon-lock" /> Rechte</el-dropdown-item>
          </router-link>
        </el-dropdown-menu>
      </el-dropdown>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Breadcrumb from '@/components/Breadcrumb';
import Hamburger from '@/components/Hamburger';
import Resource from '@/api/resource';
import permission from '@/directive/permission/index.js';
import role from '@/directive/role/index.js';

const userResource = new Resource('users');

export default {
  directives: {
    permission,
    role,
  },
  components: {
    Breadcrumb,
    Hamburger,
  },
  data(){
    return { user: {
      name: '',
    }};
  },
  computed: {
    ...mapGetters(['sidebar', 'name', 'avatar', 'device', 'userId']),
  },
  created(){
    this.getUser(this.$store.getters.userId);
  },
  methods: {
    toggleSideBar() {
      this.$store.dispatch('app/toggleSideBar');
    },
    async getUser(id) {
      const { data } = await userResource.get(id);
      this.user = data;
    },
    async logout() {
      await this.$store.dispatch('user/logout');
      this.$router.push(`/login?redirect=${this.$route.fullPath}`);
    },
  },
};
</script>

<style lang="scss" scoped>
.navbar {
  height: 50px;
  overflow: hidden;
  position: relative;
  background: #fff;
  box-shadow: 0 5px 18px rgba(0, 21, 41, 0.1);
  z-index: 10;

  .hamburger-container {
    line-height: 46px;
    height: 100%;
    float: left;
    cursor: pointer;
    transition: background 0.3s;
    -webkit-tap-highlight-color: transparent;

    &:hover {
      background: rgba(0, 0, 0, 0.025);
    }
  }

  .breadcrumb-container {
    float: left;
  }

  .errLog-container {
    display: inline-block;
    vertical-align: top;
  }

  .right-menu {
    float: right;
    height: 100%;
    line-height: 50px;

    &:focus {
      outline: none;
    }

    .right-menu-item {
      display: inline-block;
      padding: 0 8px;
      height: 100%;
      font-size: 18px;
      color: #5a5e66;
      vertical-align: text-bottom;

      &.hover-effect {
        cursor: pointer;
        transition: background 0.3s;

        &:hover {
          background: rgba(0, 0, 0, 0.025);
        }
      }
    }

    margin-right: 20px;
    .right-menu-item{
      margin-right: 5px;
    }
    .avatar-container {
      .avatar-wrapper {
        position: relative;

        .user-avatar {
          cursor: pointer;
          width: 40px;
          height: 40px;
          border-radius: 4px;
        }

        .el-icon-caret-bottom {
          cursor: pointer;
          position: absolute;
          right: -20px;
          top: 25px;
          font-size: 12px;
        }
      }
    }
  }
}
</style>
